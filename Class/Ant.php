<?

class Ant{
	public $N; //кількість мурах	
	public $Pg;//коеф глобального випаровування
  public $Mu;//коеф локального випаровування
	public $CF=0; //Цільова функція
	public $task;//задача
	public $alpha;//коеф жадібності мурах
	public $beta;//коеф стадності мурах
	public $pkm;//ймовірність переходу з вершини у вершину
	public $pheromone;//матриця феромонів
	public $start;//початкова термінальна вершина з якої мураха починає рух
	public $antMatrix = array();//зберігається кількість мурах, що пройшли по ребрам
	public $route;	//шлях мурахи
	public $tabuList; //вершини в яких вже була мураха
	public $recordRoute = array();//поточний рекорд
	 
	 public function __construct($task,/*$start,*/&$pheromone, &$antMatrix, $recordRoute, $alpha, $beta, $decay){
		$this->alpha = $alpha; //1.1
		$this->beta = $beta;	//3
		$this->Pg = $decay;//0.9
		$this->Mu = 0.1;//0.5
		$this->route = array();
		$this->tabuList = array();
		$this->recordRoute = $recordRoute;
		$this->antMatrix = &$antMatrix;
		
		//мураха має задачу
		$this->task = $task;
		
		//мураха отримала початкову вершину
		$this->start = $this->startPoint();
		
		//мураха орієнтується по феромону, що залишили попередні мурахи
		$this->pheromone = &$pheromone;
		
		//вносимо початкову вершину до шляху мурахи
		array_push($this->route,$this->start);
		
		//мураха не повернеться в початкову вершину
		array_push($this->tabuList, $this->start);		

	}
	 
	public function startPoint()
	{			
		
		$start = mt_rand(0, count($this->task->tabuArray)-1);
	
		$start = $this->task->tabuArray[$start];
		
		return $start;
	}
	 
	 
	public function sumAnt($start, $move)
	{
			return $this->antMatrix[$start][$move];
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
		$count = count($Route)-1;
		for($i=0; $i < $count; $i++)
		{
			//мураха пройшла по ребру
			$this->antMatrix[$Route[$i]][$Route[$i+1]] +=(float)1/$this->CF;
			$this->antMatrix[$Route[$i+1]][$Route[$i]] = $this->antMatrix[$Route[$i]][$Route[$i+1]];
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
			if($this->pheromone[$start][$move] > 0.001 
			&& !in_array($start, $this->task->tabuArray)
			&& !in_array($move, $this->task->tabuArray)) 		
			 {
				$this->pheromone[$start][$move] =  $this->Pg*$this->pheromone[$start][$move]+
				 (1-$this->Pg) * $this->sumAnt($start, $move);
				 	
				$this->pheromone[$move][$start] = $this->pheromone[$start][$move];
			 }
	}
	
	
	public function addPheromone($start, $move)
	{
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
		 	if($this->pheromone[$start][$move] > 0.001 
		 	&& !in_array($start, $this->task->tabuArray) 
			&& !in_array($move, $this->task->tabuArray)) 		
			 {
				 $this->pheromone[$start][$move] = (float)$this->Mu * $this->pheromone[$start][$move];
				 $this->pheromone[$move][$start] = $this->pheromone[$start][$move];	
			 }					 
	}

	
	
	public function updatePheromone($addPheromone, $Route)
	{
		$count = count($Route)-1;
		for ($i=0; $i < $count; $i++) {
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
				//доступна вершина
			 if ($value != TaskGenerator::$BigNum) {
					//якщо потрапив в діапазон - роби крок
				 if((float)$move <= (float)$value)
				 {
				 	$move = $key;
				 	return $move;
				 }
			 }
		 }
	 }
	
	 
	 public function selectEdge() 
	 {
	 
	 $this->pkm = array();
	 
			//розглядаються всі можливі шляхи
	 		//for ($i=0; $i < $this->task->amountV; $i++) {
	 		while($i < $this->task->amountV){	 			
				//вершина без циклу, та немає шляху до відсутнього ребра, не повертається назад
	 			if($this->pheromone[$this->start][$i]!= TaskGenerator::$BigNum && $this->pheromone[$this->start][$i]!=0
				&& $this->task->Matrix[$this->start][$i]!=TaskGenerator::$BigNum && $this->task->Matrix[$this->start][$i]!=0
				&& !in_array($i,$this->tabuList)) 
				{	 		
						//чисельник
					 $temp = (float)pow($this->pheromone[$this->start][$i],$this->alpha) * (float)pow((1/$this->task->Matrix[$this->start][$i]),$this->beta);
					
					//знаменник
					$temp1 = (float)$this->sumEdges();
					
					//імовірнісне правило	
					$temp2 = (float)$temp /(float)$temp1;
						
					//Створюємо імовірнісний масив для вибору шляху
					$this->pkm[$i]=floatval($temp2)+current($this->pkm); 
					
					//після маніпуляцій над масивом повертаємо вказівник в кінець масиву 		
					end($this->pkm);		 
					
				}
				++$i;
			 }
		 
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
				return (float)$sumEdge;
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
									$move = $i;
							}
						}
			}
			
			return $move; 			
		}
		
		
		public function selectionRule($r, $q)
		{
			if($r < $q)
			{
				$this->selectEdge();	
				$this->move = $this->moveAnt();
			}else
			{
				$this->move = $this->maxRule();
			}
		}
}


?>