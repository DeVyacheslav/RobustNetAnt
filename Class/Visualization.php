<?

/**
 * 
 */
class Visualization {
	public $route = array();
	public $jsonMatrix;
	public $task_matrix;
	public $task;

	
	function __construct($route, $task, $task_matrix) {
		//$this->fillJson($route, $task_matrix);
		$this->task = $task;
		$this->task_matrix = $task_matrix;
		$this->Route_visualization($route);
		echo "<script src='//d3js.org/d3.v3.min.js'></script>
		<script src='js/Visualization.js'></script>";
	}
	
	
	
	public function fillJson($route,$names)
	{
		$k=0;
		$count = count($route)-1;
		for ($i=0; $i < $count; $i++) { 
					$key = array_search($route[$i], $names);
					$key2 = array_search($route[$i+1], $names);
					$this->jsonMatrix[$k]= array("source"=>$key, "target"=>$key2, "value"=>$this->task_matrix[$route[$i]][$route[$i+1]]);
				
			$k++;
		}
		$last = array_search($route[$count],$names);
		//звязати початок і кінець
		$this->jsonMatrix[$k]= array("source"=>$last, "target"=>0, "value"=>$this->task_matrix[$route[$count]][$route[0]]);	
		return json_encode($this->jsonMatrix);
		
	}
	
	
	
	public function Route_visualization($route){
		$names = array_unique($route); 
		$names = array_values($names);
		$fp = fopen('miserables.json', 'w');
		
		fwrite($fp, "{");
		fwrite($fp, ' "nodes":[');
		$count = count($names);
		for ($i=0; $i < $count; $i++) {
			if($i == $count-1){
				if (in_array($names[$i], $this->task->taskTerminal)) {
					fwrite($fp, '{"name":"Terminal'.$names[$i].'","group":1}');
					break;
				}else{
					fwrite($fp, '{"name":"NonTerminal'.$names[$i].'","group":2}');
					break;
				}
			}
			if (in_array($names[$i], $this->task->taskTerminal)) {
				fwrite($fp, '{"name":"Terminal'.$names[$i].'","group":1},');
			}else{
				fwrite($fp, '{"name":"NonTerminal'.$names[$i].'","group":2},');
			}		
		}
		fwrite($fp, '],');
		fwrite($fp, '"links":');

		fwrite($fp, $this->fillJson($route,$names));

		fwrite($fp, "}");
		fclose($fp);
	}
}

?>