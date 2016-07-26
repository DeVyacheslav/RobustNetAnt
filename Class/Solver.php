<?
include_once 'TaskGenerator.php';
include_once 'Ant.php';
include_once 'TwoOpt.php';
include_once 'Solver_Controller.php';
/**
 * 
 */
class Solver {
	private $genCF = 9999;
	private $genRoute = array();
	private $currentRecord = 9999;
	private $currentRecordRoute = array();
	private $numRecord = 20;
	private $pheromone;
	private $start;
	private $bigTask = false;
	private $firstRoute = array();
	private $secondRoute = array();
	private $firstCost = 9999;
	private $secondCost = 9999;
	private $antNum;
	private $generation;
	private $antMatrix = array();
	private $startList = array();
	private $closeCost;
	private $meanCF;
	private $CFlist = array();
	private $numCF = 0;
	private $temp = 0;
	private $alpha;
	private $beta;
	private $q;
	private $timer;
	private $task;
	/*
	 * Конструктор
	 */ 
	function __construct(ATask $task) {
		$this->task = $task;
		DataCheck::checkField($_GET['numCol'], 10, 10000);
		$this->generation = $_GET['numCol'];
		$this->fillAntMatrix($task);
		$this->setAntNum($task);
		$this->createPheromone($task);
		
	}


	public function Run()
	{
		
		$this->q = 0.5;
		
		$time_pre = microtime(true);
		
		$this->antFirstRoute(false);
		
		$time_post = microtime(true);
		
		$exec_time = $time_post - $time_pre;

		$time_pre = microtime(true);
		$TwoOpt = new TwoOpt($this->task, $this->currentRecordRoute, $this->currentRecord);
		$TwoOpt = new TwoOpt($this->task, $this->secondRoute, $this->secondCost);
		$time_post = microtime(true);
		
		$TwoOpt_time = $time_post - $time_pre;
		
		$this->timer = $exec_time+$TwoOpt_time;
		
	}


	public function __get($name)
	{
		if (property_exists($this, $name)) {
			return $this->$name;
		}
	}
	
	public function __set($name, $val)
	{	
		if (property_exists($this, $name)) {
			$this->$name = $val;	
		}
	}
	
	public function getCost()
	{
		$cost=$this->currentRecord +$this->secondCost;
		for ($i=0; $i < count($this->secondRoute)-1; $i++) {
			for ($j=0; $j < count($this->currentRecordRoute)-1; $j++) {
				$from = $this->secondRoute[$i];
				$to = $this->secondRoute[$i+1];
				
				if($this->currentRecordRoute[$j] == $from 
				&& $this->currentRecordRoute[$j+1] == $to)
					{

							$cost-=$this->task->Matrix[$from][$to];
					}
			}
		}
		$this->secondRoute=array_reverse($this->secondRoute);
		for ($i=0; $i < count($this->secondRoute)-1; $i++) {
			for ($j=0; $j < count($this->currentRecordRoute)-1; $j++) {
				$from = $this->secondRoute[$i];
				$to = $this->secondRoute[$i+1];
				if($this->currentRecordRoute[$j] == $from 
				&& $this->currentRecordRoute[$j+1] == $to)
					{

							$cost-=$this->task->Matrix[$from][$to];
							break;
						
					}
			}
		}
		return $cost;
	}

	public function setAntNum()
{
	if($task->TerminalV > 3)
		{
			$this->bigTask = true;
			
			if($task->amountV <=15 && $this->task->TerminalV <10)
				$this->antNum = pow($this->task->TerminalV,2);
			else
				$this->antNum = $task->TerminalV*3;
						
			$this->setNumRecord($this->task);
		}else
		{			
			$this->antNum = $this->task->TerminalV;	
		}
}

	public function setPheromone($row, $col, $value)
	{
		$this->pheromone[$row][$col] = $value;
	}
	
	/*
	 * Мурашиний алгоритм
	 */ 
	public function antAlgorithm(Ant &$ant)
	{
		while(array_diff($this->task->tabuArray, $ant->route))
		{
			$r = (float)mt_rand()/(float)mt_getrandmax();
			
			if($r < $this->q)
			{
				
				$ant->selectEdge();	
				$ant->moveAnt();
			}else
				{
					$ant->maxRule();
				}
			$ant->addAntRoute();
			if($ant->start == $ant->move)
			{
				$ant->CF = 9999;
				$ant->updatePheromone(2, $ant->route);
				break;	
			}			
						
			$ant->addToCF();
			
			$ant->setNextMove();
		}
	}
	
	/*
	 * Знаходження першого маршруту
	 */ 
	public function antFirstRoute($reuse)
	{
			$bestAnt=0;
			$this->generetaion = 500;
			if($reuse)
			{			
				$this->blockEdges();
			}
			
				while($this->generation !=0)
				{

					for ($i=0; $i < $this->antNum; $i++) {
						
						//кожна мурашка буде починати з випадково обраноъ термынальноъ вершини	
						$this->startPoint();

						$ant = new Ant($this->task,$this->start,$this->pheromone,$this->antMatrix, $this->currentRecordRoute, $this->alpha, $this->beta, $this->Pg);	

						$this->antAlgorithm($ant);	
						
						if($ant->CF != 9999)
						{
							$this->getMeanCF($ant->CF);
							array_push($this->CFlist, $ant->CF);
						
							
							if($ant->CF < $this->genCF)
							{
								
								$ant->countAnts($ant->route);	
								$bestAnt++;
								
								$this->genCF = $ant->CF;
								
								$this->genRoute = $ant->route;
								
								$ant->updatePheromone(1, $ant->route);							
								
								
								$TwoOpt = new TwoOpt($this->task, $this->genRoute,$this->genCF);
							}elseif($this->meanCF <= $ant->CF )
							{
								//echo "<br>".$this->meanCF."<=".$ant->CF;	
								$ant->updatePheromone(2, $ant->route);
							}
						}
					}

					$this->CFlist = array();
					if(!$reuse)
					{
						if($this->genCF < $this->currentRecord)
						{
							
							$this->currentRecord = $this->genCF;	
							$this->currentRecordRoute = $this->genRoute;
							$this->setNumRecord($this->task);
						}
					}else
					{
						if($this->genCF < $this->secondCost)
						{
							$this->secondCost = $this->genCF;	
							$this->secondRoute = $this->genRoute;
							$this->setNumRecord($this->task);
						}
					}

					$ant->updatePheromone(0, $ant->route);
					
					if ($this->numRecord == 0) 
					{							
						if(!$reuse)
						{
							$this->startList = array();
							$this->setNumRecord($this->task);
							$this->genCF =9999;
							$this->temp = 0;
							$this->antFirstRoute(true);
						}
						
						break;
					}
					$this->numRecord--;
					$this->genCF = 9999;
					$this->startList = array();
					$this->generation--;
				}
	}


	
	public function blockEdges()
	{
		$this->generation = $_GET['numCol'];
		$tempCF=9999;	
		$tempRoute = array();	
		
		
		for($i=0; $i < count($this->currentRecordRoute)-1; $i++)
		{
			$from = $this->currentRecordRoute[$i];
			$to = $this->currentRecordRoute[$i+1]; 
			if(in_array($from, $this->task->tabuArray) 
			||in_array($from, $this->task->tabuArray) )
			{
				$this->setPheromone($from, $to, TaskGenerator::$BigNum);
				$this->setPheromone($to, $from, TaskGenerator::$BigNum);
				$this->task->Matrix[$from][$to] = TaskGenerator::$BigNum;
				$this->task->Matrix[$to][$from] = TaskGenerator::$BigNum;
			}

		}
			for($i=0; $i< $this->task->amountV; $i++)
			{
				$temp =0;
				
				for($j=0; $j< $this->task->amountV; $j++)
				{	
							if($this->task->Matrix[$i][$j]!=TaskGenerator::$BigNum)
							{	
								$temp++;
								$tempi = $i;
								$tempj = $j;
							}						
				}	
				if($temp == 1 && !in_array($tempi, $this->task->tabuArray)&& !in_array($tempj, $this->task->tabuArray)/* && $task->Matrix[$tempi][$tempj] != TaskGenerator::$BigNum &&$task->Matrix[$tempj][$tempi] != TaskGenerator::$BigNum*/)
				{
					$this->setPheromone($tempi, $tempj, TaskGenerator::$BigNum);
					$this->setPheromone($tempj, $tempi, TaskGenerator::$BigNum);
					$this->task->Matrix[$tempi][$tempj] = TaskGenerator::$BigNum;
					$this->task->Matrix[$tempj][$tempi] = $this->task->Matrix[$tempi][$tempj];
					$i=0;
				}
			}
			$this->task->tabuArray = array(end($this->currentRecordRoute), $this->currentRecordRoute[0]);
			
	}


/*
 * Вибір початкової вершини
 */ 
	public function startPoint()
	{			
		
		$this->start = mt_rand(0, count($this->task->tabuArray)-1);
	
		$this->start = $this->task->tabuArray[$this->start];
		
	}
	
	public function getMeanCF($CF)
	{
		
		if(count($this->CFlist)>0)
		{
			$this->meanCF = (float)$this->temp/count($this->CFlist);
			$this->temp += $CF;
		}
		
	}
	
	/*
	 *  Ініціалізації матриці кількості мурашок
	 */ 
	public function fillAntMatrix()
	{
		for($i=0; $i < $this->task->amountV; $i++)
		{
			for($j=0; $j < $this->task->amountV; $j++)
				{
					$this->antMatrix[$i][$j] = 0;
					$this->antMatrix[$j][$i] = $this->antMatrix[$i][$j];
				}
		}
	}
	
	/*
	 *  Оновлення кількості поколінь без покращення
	 */
	public function setNumRecord()
	{
		if($this->bigTask)
		{
			$this->numRecord = 10 /*$this->task->TerminalV*/;
		}else
		{
			$this->numRecord = 10;
		}
	}
	
	
	/*
	 * Ініціалізація матриці феромонів
	 * 
	 */
	 public function createPheromone()
	 {
		 for ($i=0; $i < $this->task->amountV; $i++) { 
			 for ($j=0; $j < $this->task->amountV; $j++) {
				 	if($this->task->Matrix[$i][$j] != TaskGenerator::$BigNum) {//перевірка на відсутні ребра
				 		 $this->pheromone[$i][$j] = 0.5;
				 	}
				 	else{
							 $this->this->pheromone[$i][$j] = TaskGenerator::$BigNum;//немає ребра - немає феромону
					}	
			 }
		 }
	 }
}


?>