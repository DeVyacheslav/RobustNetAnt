<?

/**
 * 
 */
class ViewSolver {
	private $solver;
	private $task;
	private $controller;
	function __construct($solver, $controller, $task) {
		$this->solver = $solver;
		$this->controller = $controller;
		$this->task = $task;	
		
	}
	
	public function outputSolve()
	{
		echo "Alpha: ",$this->solver->alpha," Beta: ",$this->solver->beta,"<br>";
		echo	'<table style="width:100%">';
		echo "<tr>";
		echo "<td width = '40%'>Route 1 Cost(".$this->solver->currentRecord."): ";	
		$this->controller->getRoute($this->solver->currentRecordRoute,$this->task);
		echo "</td>";	
		echo "<td width = '30%'>Route 2 Cost(".$this->solver->secondCost."): ";
		$this->controller->getRoute($this->solver->secondRoute,$this->task);
		echo "</td>";		
		echo "<td width = '20%'>Timer (total): ".$this->solver->timer."</td>";
		echo "<td width = '10%'>Networks cost: ".$this->solver->getCost()."</td>";
		echo "</tr>";
		echo "</table>";
	}
	

}
