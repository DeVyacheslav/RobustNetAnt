<?

/**
 * 
 */
class Visualization {
	public $route = array();
	public $jsonMatrix;
	public $task_matrix;
	public $task;
	public $firstRouteCount;

	
	function __construct($route, $firstPart, $task, $task_matrix) {
		//$this->fillJson($route, $task_matrix);
		$this->task = $task;
		$this->firstRouteCount = $firstPart;
		$this->task_matrix = $task_matrix;
		$this->Route_visualization($route);
	}
	public function fillJsonTask(){
		$k=0;
		for ($i=0; $i < $this->task->amountV; $i++) { 
			for ($j=0; $j < $this->task->amountV; $j++) { 
				if($i!=$j && $this->task_matrix[$i][$j]!=TaskGenerator::$BigNum)
				{
					$this->jsonMatrix[$k]= array("source"=>$i, "target"=>$j, "value"=>$this->task_matrix[$i][$j]);
					$k++;
				}
			}
		}
		
		return str_replace(array(']'), '', htmlspecialchars(json_encode($this->jsonMatrix), ENT_NOQUOTES));
		//json_encode($this->jsonMatrix);
	}
	
	
	public function fillJson($route,$names)
	{
		$k=0;
		$count = count($route)-1;
		var_dump($names);
		var_dump($route);
		for ($i=0; $i < $count; $i++) { 
					$key = array_search($route[$i], $names);
					$key2 = array_search($route[$i+1], $names);
					$this->jsonMatrix[$k]= array("source"=>$key, "target"=>$key2, "value"=>$this->task_matrix[$route[$i]][$route[$i+1]]);
				
			$k++;
		}
		$last = array_search($route[$count],$names);
		echo "<br>key= $last, key2= 0, route= $route[$last], route+1= ".$route[0];
		//звязати початок і кінець
		$this->jsonMatrix[$k]= array("source"=>$last, "target"=>0, "value"=>$this->task_matrix[$route[$last]][$route[0]]);	
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