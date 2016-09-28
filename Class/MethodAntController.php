<?

/**
 * 
 */
class MethodAntController {
	public $method;
	public $task;
	
	function __construct(IMethod $method, ATask $task) {
		$this->method = $method;
		$this->task = $task;
	}
	
	public function getRoute($route)
	{
		foreach ($route as $obj) {
					if(in_array($obj, $this->task->taskTerminal))
					{
						echo $obj."T ";
					}else
						{
							echo $obj." ";
						}
				}
	}
	
	public function inputData()
	{
		$this->method->alpha = $_POST['alpha'];
		$this->method->beta = $_POST['beta']; 
		$this->method->Pg = $_POST['decay'];
		$this->fillAntMatrix();
		$this->setAntNum();
		//$this->createPheromone();
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
					$this->method->antMatrix[$i][$j] = 0;
					$this->method->antMatrix[$j][$i] = $this->method->antMatrix[$i][$j];
				}
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
				 		 $this->method->pheromone[$i][$j] = 0.5;
					echo $this->method->pheromone[$i][$j];
				 	}
				 	else{
							 $this->method->pheromone[$i][$j] = TaskGenerator::$BigNum;//немає ребра - немає феромону
					}	
			 }
		 }
		 echo "ROSHEL";
	 }
	
	
	public function setAntNum()
	{
		if($this->task->TerminalV > 3)
			{
				$this->method->bigTask = true;
				
				if($this->task->amountV <=15 && $this->task->TerminalV <10)
					$this->method->antNum = pow($this->task->TerminalV,2);
				else
					$this->method->antNum = $this->task->TerminalV*3;
							
				$this->method->setNumRecord();
			}else
			{			
				$this->method->antNum = $this->task->TerminalV;	
			}
	}
	
	

	

	
}

