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
		$this->setAntNum();
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

