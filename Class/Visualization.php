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
		//$this->Graph_visualization();
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
		//echo "AAA".
		for ($i=0; $i < count($route)-1; $i++) { 
			if($this->task_matrix[$route[$i]][$route[$i+1]]!=TaskGenerator::$BigNum)
			{
				if($i!=$this->firstRouteCount ){
					$this->jsonMatrix[$k]= array("source"=>$i, "target"=>$i+1, "value"=>$this->task_matrix[$route[$i]][$route[$i+1]]);
				}elseif($route[$i+1] < $i){
					$key = array_search($route[$i+1], $names);
					$this->jsonMatrix[$k]= array("source"=>$i, "target"=>$key, "value"=>$this->task_matrix[$i][$route[$i+1]]);
					//$this->jsonMatrix[$k+1]= array("source"=>$route[$i+1], "target"=>0, "value"=>$this->task_matrix[$route[$i+1]][$route[0]]);
				}
				/*elseif($i == count($route)-2){
					$this->jsonMatrix[$k]= array("source"=>$route[$i+1], "target"=>0, "value"=>$this->task_matrix[$route[$i+1]][$route[0]]);
				}*/
				
			$k++;
			}
		}
		return json_encode($this->jsonMatrix);//str_replace(array('[',), '', htmlspecialchars(json_encode($this->jsonMatrix), ENT_NOQUOTES));
		
	}
	
	public function Graph_visualization(){
		$fp = fopen('miserables.json', 'w');
		
		fwrite($fp, "{");
		fwrite($fp, ' "nodes":[');
		for ($i=0; $i < $this->task->amountV; $i++) {
			if($i+1 == $this->task->amountV){
				if (in_array($i, $this->task->tabuArray)) {
					fwrite($fp, '{"name":"Terminal'.$i.'","group":1}');
					break;
				}else{
					fwrite($fp, '{"name":"NonTerminal'.$i.'","group":2}');
					break;
				}
			}
			if (in_array($i, $this->task->tabuArray)) {
				fwrite($fp, '{"name":"Terminal'.$i.'","group":1},');
			}else{
				fwrite($fp, '{"name":"NonTerminal'.$i.'","group":2},');
			}		
		}
		fwrite($fp, '],');
		fwrite($fp, '"links":');
			fwrite($fp, $this->fillJsonTask());
		//fwrite($fp, "}");
		fclose($fp);
	}
	
	public function Route_visualization($route){
		$names = array_unique($route); 
		
		$fp = fopen('miserables.json', 'w');
		
		fwrite($fp, "{");
		fwrite($fp, ' "nodes":[');
		for ($i=0; $i < count($names); $i++) {
			if($i+1 == count($names)){
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
		//for ($i=0; $i < $task->amountV; $i++) { 
	//	$fp = file_put_contents('miserables.json', ",".$this->fillJson($route)."}" , FILE_APPEND);
			fwrite($fp, $this->fillJson($route,$names));
		//}
		//fwrite($fp, "]");
		fwrite($fp, "}");
		fclose($fp);
	}
}

?>