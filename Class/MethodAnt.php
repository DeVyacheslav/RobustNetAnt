<?

/**
 * 
 */
class MethodAnt implements IMethod {
	private $genCF = 9999;
	private $genRoute = array();
	private $record = 9999;
	private $recordRoute = array();
	private $numRecord = 20;
	private $pheromone;
	private $start;
	private $bigTask = false;
	private $firstRoute = array();
	private $recordRoute2 = array();
	private $firstCost = 9999;
	private $record2 = 9999;
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
		$this->fillAntMatrix();
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
		$TwoOpt = new TwoOpt($this->task, $this->recordRoute, $this->record);
		$TwoOpt = new TwoOpt($this->task, $this->recordRoute2, $this->record2);
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

		$count = count($this->recordRoute2)-1;
		$count2 = count($this->recordRoute)-1;
		for ($i=0; $i < $count; $i++) {
			for ($j=0; $j < $count2; $j++) {
				$from = $this->recordRoute2[$i];
				$to = $this->recordRoute2[$i+1];
				
				if($this->recordRoute[$j] == $from 
				&& $this->recordRoute[$j+1] == $to)
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
		$cost = $this->record +$this->record2;

		$cost = $this->costCalculator($cost, false);
		
		$this->recordRoute2=array_reverse($this->recordRoute2);
		
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
				$ant->CF = NULL;
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

				$ant = new Ant(
				$this->task,
				$this->pheromone,
				$this->antMatrix, 
				$this->recordRoute, 
				$this->alpha,
				$this->beta, 
				$this->Pg);	

				$this->antAlgorithm($ant);	
				
				if(!is_null($ant->CF))
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
						$ant->updatePheromone(2, $ant->route);
					}
				}
			}

			$this->CFlist = array();
			if(!$reuse)
			{
				$this->updateRecord($this->genCF, $this->genRoute, 
				$this->record, $this->recordRoute);
			}else
			{
				$this->updateRecord($this->genCF, $this->genRoute, 
				$this->record2, $this->recordRoute2);
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

	private function updateRecord($currentCF, $currentRoute , &$recordCF, &$recordRoute)
	{
		if($currentCF < $recordCF){
			$recordCF = $currentCF;	
			$recordRoute = $currentRoute;
			$this->setNumRecord();
		}
	}
	
	
	
	private function blocker($route)
	{
		$count =count($route)-1;
		for($i=0; $i < $count; $i++)
		{
			$from = $route[$i];
			$to = $route[$i+1]; 
			if(in_array($from, $this->task->tabuArray) 
			||in_array($to, $this->task->tabuArray) )
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
	
		$this->blocker($this->recordRoute);
		
		$tempRoute = array_reverse($this->recordRoute);
		
		$this->blocker($tempRoute);
			
		$this->task->tabuArray =  array(end($this->recordRoute), $this->recordRoute[0]);
			
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
	 
	 	/*
	 *  Ініціалізації матриці кількості мурашок
	 */ 
	private function fillAntMatrix()
	{
		$this->antMatrix = array_fill(0, $this->task->amountV, array_fill(0, $this->task->amountV, 0));
	}
}


?>