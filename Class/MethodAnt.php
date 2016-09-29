<?

/**
 * 
 */
class MethodAnt implements IMethod {
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
	private $Pg;
	private $controller;
	
	/*
	 * Конструктор
	 */ 
	function __construct(ATask $task) {
		$this->task = $task;
		//DataCheck::checkField($_POST['numCol'], 10, 10000);
		$this->generation = $_POST['numCol'];
		//$this->fillAntMatrix();
		//$this->setAntNum();
		$this->createPheromone();
		
	}


	public function run($controller)
	{
		$this->controller = $controller;
		
		$this->q = 0.5;
		
		$time_pre = microtime(true);
		
		$this->findRoute(false);
		
		$time_post = microtime(true);
		
		$exec_time = $time_post - $time_pre;

		/*$time_pre = microtime(true);
		$TwoOpt = new TwoOpt($this->task, $this->currentRecordRoute, $this->currentRecord);
		$TwoOpt = new TwoOpt($this->task, $this->secondRoute, $this->secondCost);
		$time_post = microtime(true);*/
		
		$TwoOpt_time = 0/*$time_post - $time_pre*/;
		
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
	
	
	public function costCalculator($cost, $flag)
	{
		for ($i=0; $i < count($this->secondRoute)-1; $i++) {
			for ($j=0; $j < count($this->currentRecordRoute)-1; $j++) {
				$from = $this->secondRoute[$i];
				$to = $this->secondRoute[$i+1];
				
				if($this->currentRecordRoute[$j] == $from 
				&& $this->currentRecordRoute[$j+1] == $to)
					{

							$cost-=$this->task->Matrix[$from][$to];
							if($flag){
								break;
							}
					}
			}
		}
		return $cost;
	}


	public function getCost()
	{
		$cost = $this->currentRecord +$this->secondCost;

		$cost = $this->costCalculator($cost, false);
		
		$this->secondRoute=array_reverse($this->secondRoute);
		
		$cost = $this->costCalculator($cost, true);

		return $cost;
	}


	
	
	
	/*
	 * Мурашиний алгоритм
	 */ 
	public function antAlgorithm(&$ant)
	{
		while(array_diff($this->task->tabuArray, $ant->route))
		{
			$r = (float)mt_rand()/(float)mt_getrandmax();
			
			$ant->selectionRule($r, $this->q);

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
	 * Знаходження маршруту
	 */ 
	public function findRoute($reuse)
	{
			$this->generation = $_POST['numCol'];
			if($reuse)
			{			
				$this->blockEdges();
			}
			
				while($this->generation !=0)
				{

					for ($i=0; $i < $this->antNum; $i++) {
						
						//кожна мурашка буде починати з випадково обраноъ термынальноъ вершини	
						//$this->startPoint();

						$ant = new Ant(
						$this->task,
						//$this->start,
						$this->pheromone,
						$this->antMatrix, 
						$this->currentRecordRoute, 
						$this->alpha,
						$this->beta, 
						$this->Pg);	

						$this->antAlgorithm($ant);	
						
						if($ant->CF != 9999)
						{
							$this->getMeanCF($ant->CF);
							array_push($this->CFlist, $ant->CF);
						
							
							if($ant->CF < $this->genCF)
							{
								
								$ant->countAnts($ant->route);	
								
								$this->genCF = $ant->CF;
								
								$this->genRoute = $ant->route;
								
								$ant->updatePheromone(1, $ant->route);							
								
								
								//$TwoOpt = new TwoOpt($this->task, $this->genRoute,$this->genCF);
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
							$this->setNumRecord();
						}
					}else
					{
						if($this->genCF < $this->secondCost)
						{
							$this->secondCost = $this->genCF;	
							$this->secondRoute = $this->genRoute;
							$this->setNumRecord();
						}
					}

					$ant->updatePheromone(0, $ant->route);
					
					if ($this->numRecord == 0) 
					{							
						if(!$reuse)
						{
							$this->startList = array();
							$this->setNumRecord();
							$this->genCF =9999;
							$this->temp = 0;
							$this->findRoute(true);
						}
						
						break;
					}
					$this->numRecord--;
					$this->genCF = 9999;
					$this->startList = array();
					$this->generation--;
				}
	}


	
	private function blocker($route)
	{
		for($i=0; $i < count($route); $i++)
		{
			$from = $route[$i];
			$to = $route[$i+1]; 
			if(in_array($from, $this->task->tabuArray) 
			||in_array($from, $this->task->tabuArray) )
			{
				$this->pheromone[$from][$to] = TaskGenerator::$BigNum;
				$this->pheromone[$to][$from] = TaskGenerator::$BigNum;
				$this->task->Matrix[$from][$to] = TaskGenerator::$BigNum;
				$this->task->Matrix[$to][$from] = TaskGenerator::$BigNum;
			}

		}
	}
	
	private function blockEdges()
	{
		$this->generation = $_POST['numCol'];
	
		$this->blocker($this->currentRecordRoute);
		
		$tempRoute = array_reverse($this->currentRecordRoute);
		
		$this->blocker($tempRoute);
			
		$this->task->tabuArray =  array(end($this->currentRecordRoute), $this->currentRecordRoute[0]);
			
	}



/*
 * Вибір початкової вершини
 */ 
	/*moved to ANT
	 * public function startPoint()
	{			
		
		$this->start = mt_rand(0, count($this->task->tabuArray)-1);
	
		$this->start = $this->task->tabuArray[$this->start];
		
	}
	*/
	
	
	public function getMeanCF($CF)
	{
		
		if(count($this->CFlist)>0)
		{
			$this->meanCF = (float)$this->temp/count($this->CFlist);
			$this->temp += $CF;
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
	 private function createPheromone()
	 {
		 $this->pheromone = array_fill(0, $this->task->amountV, array_fill(0, $this->task->amountV, 0.5));
	 }
}


?>