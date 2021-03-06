<?

/**
 * 
 */
class ViewAnt {
	private $method;
	private $task;
	private $controller;
	
	function __construct(IMethod $method, $controller, ATask $task) {
		$this->method = $method;
		$this->controller = $controller;
		$this->task = $task;	
		
	}
	
	public function outputTask()
	{
		echo "Alpha: ",$this->method->alpha," Beta: ",$this->method->beta," Decay: ",$this->method->Pg,"<br>";
	}
	
	public function outputSolve()
	{
	//	echo "Alpha: ",$this->method->alpha," Beta: ",$this->method->beta,"<br>";
		echo	'<table style="width:100%">';
		echo "<tr>";
		echo "<td width = '45%'>Route 1 Cost(".$this->method->record."): ";	
		$this->controller->getRoute($this->method->recordRoute,$this->task);
		echo "</td>";	
		echo "<td width = '35%'>Route 2 Cost(".$this->method->record2."): ";
		$this->controller->getRoute($this->method->recordRoute2,$this->task);
		echo "</td>";		
		echo "<td width = '10%'>Timer (total): ".number_format($this->method->timer,2)."</td>";
		echo "<td width = '10%'>Networks cost: ".$this->method->getCost()."</td>";
		echo "</tr>";
		echo "</table>";
	}
	
	public function outputVisualization($task_matrix)
	{
		$temp = $this->method->recordRoute2;
		if($temp[0] == $this->method->recordRoute[0]){
			$temp = array_reverse($temp);
		}

		array_shift($temp);
		array_pop($temp);

		$bestRoute = array_merge($this->method->recordRoute, $temp);	

		$visualization = new Visualization($bestRoute, $this->task, $task_matrix);
		
	}
}
