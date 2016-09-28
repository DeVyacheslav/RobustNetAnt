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
		echo "<td width = '40%'>Route 1 Cost(".$this->method->currentRecord."): ";	
		$this->controller->getRoute($this->method->currentRecordRoute,$this->task);
		echo "</td>";	
		echo "<td width = '30%'>Route 2 Cost(".$this->method->secondCost."): ";
		$this->controller->getRoute($this->method->secondRoute,$this->task);
		echo "</td>";		
		echo "<td width = '20%'>Timer (total): ".$this->method->timer."</td>";
		echo "<td width = '10%'>Networks cost: ".$this->method->getCost()."</td>";
		echo "</tr>";
		echo "</table>";
	}
	
	public function outputVisualization($task_matrix)
	{
		$temp = $this->method->secondRoute;
		if($temp[0] == $this->method->currentRecordRoute[0]){
			$temp = array_reverse($temp);
		}
		if(count($temp)!=2)
		{
			array_shift($temp);
			array_pop($temp);
		}
		foreach($this->method->currentRecordRoute as $val){
			echo $val." ";
		}
		foreach($temp as $val){
			echo $val." ";
		}
		$bestRoute = array_merge($this->method->currentRecordRoute, $temp);	

		$visualization = new Visualization($bestRoute, count($this->method->currentRecordRoute), $this->task, $task_matrix);
		echo "<script src='//d3js.org/d3.v3.min.js'></script>
		<script src='js/Visualization.js'></script>";
	}
}
