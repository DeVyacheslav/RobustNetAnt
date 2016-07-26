<?
include_once "Solver.php";

class Ant{
	//кількість мурах
	public $N; 
	
	//кращі ЦФ 
	public $NBest; 
	
	//коеф випаровування
	public $Pg;
	
	
	//public $Pl;//??
	public $Mu;//??
	
	//Цільова функція
	public $CF=0; 
	public $r;//??
	
	//задача
	public $task;
	
	//жадібність мурах
	public $alpha;
	
	//стадність мурах
	public $beta;
	
	//ймовірність переходу з вершини у вершину
	public $pkm;
	
	//матриця феромонів
	public $pheromone;
	
	//початкова термінальна вершина з якої мураха починає рух
	public $start;
	
	//зберігається кількість мурах, що пройшли по ребрам
	public $antMatrix = array();
	
	//шлях мурахи
	public $route;
	
	//вершини в яких вже була мураха
	public $tabuList; 
	
	public $currentRecordRoute = array();
	 
	 public function __construct($task,$start,&$pheromone, &$antMatrix, $currentRecordRoute, $alpha, $beta, $decay){
		$this->alpha = $alpha; //1.1
		$this->beta = $beta;	//3
		$this->Pg = $decay;//0.9
		$this->Pl = 0.9;
		$this->Mu = 0.1;//0.5
		$this->route = array();
		$this->tabuList = array();
		$this->currentRecordRoute = $currentRecordRoute;
		$this->antMatrix = &$antMatrix;
		//echo $this->beta;
		//echo "Ant has been created!";	
		
		//мураха має задачу
		$this->task = $task;
		
		//мураха отримала початкову вершину
		$this->start = $start;
		
		//мураха орієнтується по феромону, що залишили попередні мурахи
		$this->pheromone = &$pheromone;
		
		//вносимо початкову вершину до шляху мурахи
		array_push($this->route,$this->start);
		
		//мураха не повернеться в початкову вершину
		array_push($this->tabuList, $this->start);		

	}
	 
	 
	public function sumAnt($start, $move)
	{
	/*	for($i=0; $i< )
		{
			
		}*/
		//if($value !=0)
			return $this->antMatrix[$start][$move];
		//else return 10;
	}
	
	public function addAntRoute()
	{
		//вносимо обрану вершину до маршруту
		array_push($this->route,$this->move);
		
		//забороняємо повертатись у відвідані вершини
		array_push($this->tabuList, $this->move);
	}

	public function countAnts($Route)
	{
		/*if(in_array($this->start, $this->currentRecordRoute) 
		&& in_array($this->move, $this->currentRecordRoute))
		{*/
		for($i=0; $i < count($Route)-1; $i++)
		{
			//мураха пройшла по ребру
			$this->antMatrix[$Route[$i]][$Route[$i+1]] +=(float)1/$this->CF;
			$this->antMatrix[$Route[$i+1]][$Route[$i]] = $this->antMatrix[$Route[$i]][$Route[$i+1]];
			//echo " Start ".$this->start." Moving to ".$this->move." Antmatrix".$this->antMatrix[$this->start][$this->move];
			//}
			//$this->addToCF($i, ($i+1));
		}
	}
	
	public function addToCF()
	{
		//рахуємо цільову функцію
		$this->CF += $this->task->Matrix[$this->start][$this->move];
	}
	
	public function setNextMove()
	{
		//обрана вершина стає початком для наступного кроку мурахи
		
		$this->start = $this->move;
		
	}
	
	
	
	public function globalUpdate($start, $move)
	{
		//for ($i=0; $i < count($Route)-1; $i++) {
			/*if(!in_array($i, $this->currentRecordRoute) 
			&& !in_array($i+1, $this->currentRecordRoute) 
			 && !in_array($i, $this->task->tabuArray)
			&& !in_array($i+1, $this->task->tabuArray))
			{		*/
			if($this->pheromone[$start][$move] > 0.001 
			&& !in_array($start, $this->task->tabuArray)
			&& !in_array($move, $this->task->tabuArray)) 		
			 {
			 	// echo "<br> Before".$this->pheromone[$Route[$i]][$Route[$i+1]];
				$this->pheromone[$start][$move] =  $this->Pg*$this->pheromone[$start][$move]+
				 (1-$this->Pg) * $this->sumAnt($start, $move);
				// echo "<br> After".$this->pheromone[$Route[$i]][$Route[$i+1]];			
				$this->pheromone[$move][$start] = $this->pheromone[$start][$move];
			 }
				//}
		//}
	}
	
	
	public function addPheromone($start, $move)
	{
		//for ($i=0; $i < count($Route)-1; $i++) {
			if(!in_array($start, $this->task->tabuArray) 
			&& !in_array($move, $this->task->tabuArray))
			{
				$this->pheromone[$start][$move] = $this->pheromone[$start][$move]
				+ $this->Mu * (1 - $this->pheromone[$start][$move]);
				$this->pheromone[$move][$start] = $this->pheromone[$start][$move];
			}
	}
	
	
	public function reducePheromone($start, $move)
	{
		// for ($i=0; $i < count($Route)-1; $i++) {
		 	if($this->pheromone[$start][$move] > 0.001 
		 	&& !in_array($start, $this->task->tabuArray) 
			&& !in_array($move, $this->task->tabuArray)) 		
			 {
				 $this->pheromone[$start][$move] = (float)$this->Pl * $this->pheromone[$start][$move];
				 $this->pheromone[$move][$start] = $this->pheromone[$start][$move];	
			 }					
		 //}	 
	}

	
	
	public function updatePheromone($addPheromone, $Route)
	{
		for ($i=0; $i < count($Route)-1; $i++) {
			switch ($addPheromone) {
				case '0':
					//оновлюємо феромон
					$this->globalUpdate($Route[$i],$Route[$i+1]);		
					break;
				case '1':
					//додаємо феромон
					$this->addPheromone($Route[$i],$Route[$i+1]);	
					break;
				case '2':
					//зменшуємо феромон
					$this->reducePheromone($Route[$i],$Route[$i+1]);
					break;
				default:
					
					break;
			}
		}
	} 
	


	public function moveAnt()
	 {
			//створюємо значення рулетки
		 $move = (float)rand()/(float)getrandmax();
		 
		 //сортування масиву, недоступні значення $BigNum в кінці, тому вони не потраплять до розгляду
		 asort($this->pkm);
		 foreach ($this->pkm as $key => $value) {
			 	//echo $value;
				//доступна вершина
			 if ($value != TaskGenerator::$BigNum /*&& !in_array($key, $this->tabuList)*/) {
					//якщо потрапив в діапазон - роби крок
				 if((float)$move <= (float)$value)
				 {
				 	$this->move = $key;
				/*	echo "<br>";
				 echo "Random number: ".$move."<=".$value;
				 echo "<br>";
				 echo "Moving to node: ".$this->move;*/
				 break;
				 }
			 }
		 }
		 
	 }
	
	 
	 public function selectEdge() //pk(m)
	 {//echo '<br>';
	 
	 $this->pkm = array();
	 
			//розглядаються всі можливі шляхи
	 		for ($i=0; $i < $this->task->amountV; $i++) { 			
			//	echo "<br> START ".$this->start." TO ".$i." PHROMONE: ".$this->pheromone[$this->start][$i];
				//вершина без циклу, та немає шляху до відсутнього ребра, не повертається назад
	 			if($this->pheromone[$this->start][$i]!= TaskGenerator::$BigNum && $this->pheromone[$this->start][$i]!=0
				&& $this->task->Matrix[$this->start][$i]!=TaskGenerator::$BigNum && $this->task->Matrix[$this->start][$i]!=0
				&& !in_array($i,$this->tabuList)) 
				{	 		
			//	echo "<br>".$this->task->Matrix[$this->start][$i];
						//чисельник
					 $temp = (float)pow($this->pheromone[$this->start][$i],$this->alpha) * (float)pow((1/$this->task->Matrix[$this->start][$i]),$this->beta);
					
					//знаменник
					$temp1 = (float)$this->sumEdges();
					
					//імовірнісне правило	
					$temp2 = (float)$temp /(float)$temp1;
					
					//знаходження попереднього значення імовірності, обходимо недоступні вершини					
					while(current($this->pkm) == TaskGenerator::$BigNum)
					{
						//рух по масиву з права на ліво
						prev($this->pkm);
					} 				
					//Створюємо імовірнісний масив для вибору шляху
					array_push($this->pkm,floatval($temp2)+current($this->pkm)); 
					
					//після маніпуляцій над масивом повертаємо вказівник в кінець масиву 		
					end($this->pkm);		 
					
				}else
			 	{
			 		 //не існує шляху - не можливо обрати
			 		 array_push($this->pkm, TaskGenerator::$BigNum); 				
			 	}
			/*
				echo '<br>';
				echo $i." PKM: ".$this->pkm[$i];
				echo '<br>';*/
			 }
		 //
		 
	 }

//сумуємо доступні ребра
		public function sumEdges()
		{
			for ($i=0; $i < $this->task->amountV; $i++) { 
					//вершина не може бути забороненою, та не в табу списку
					if($this->task->Matrix[$this->start][$i]!=TaskGenerator::$BigNum &&$this->task->Matrix[$this->start][$i]!=0
					&& !in_array($i,$this->tabuList))
					{
						//сумування
						(float)$sumEdge += (float)pow($this->pheromone[$this->start][$i],$this->alpha) * (float)pow((1/$this->task->Matrix[$this->start][$i]),$this->beta);
					}
					
			}
			//if($sumEdge!=0)
				return (float)$sumEdge;
			//else return 10;
		}


		public function maxRule()
		{
			$max = 0;
			for ($i=0; $i < $this->task->amountV; $i++) {
				if($this->pheromone[$this->start][$i]!= TaskGenerator::$BigNum && $this->pheromone[$this->start][$i]!=0
						&& $this->task->Matrix[$this->start][$i]!=TaskGenerator::$BigNum && $this->task->Matrix[$this->start][$i]!=0
						&& !in_array($i,$this->tabuList)) 
						{
					 		$temp = $this->pheromone[$this->start][$i] * (float)pow((1/$this->task->Matrix[$this->start][$i]),$this->beta);
							if($temp > $max)
							{
									$max = $temp;
									$this->move = $i;
							}
						}
			} 			
		}
}
?>