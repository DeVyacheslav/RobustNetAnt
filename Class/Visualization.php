<?

/**
 * 
 */
class Visualization {
	public $route = array();
	public $jsonMatrix;
	public $jsonM;
	
	function __construct($route, $task, $task_matrix) {
		$this->fillJson($route, $task_matrix);
		$this->createFile($route, $task);
	}
	
	public function fillJson($route,$task_matrix)
	{
		$k=0;
		for ($i=0; $i < count($route)-1; $i++) { 
			if($task_matrix[$route[$i]][$route[$i+1]]!=TaskGenerator::$BigNum)
			{
				$this->jsonMatrix[$k]= array("source"=>$i, "target"=>($i+1), "value"=>$task_matrix[$route[$i]][$route[$i+1]]);
			//}
			$k++;
			}
		}
		$this->jsonM = json_encode($this->jsonMatrix);
	}
	
	public function createFile($route, $task){
		$fp = fopen('miserables.json', 'w');
		
		fwrite($fp, "{");
		fwrite($fp, ' "nodes":[');
		for ($i=0; $i < count($route); $i++) {
			if($i+1 == count($route)){
				if (in_array($route[$i], $task->tabuArray)) {
					fwrite($fp, '{"name":"Terminal'.$route[$i].'","group":1}');
					break;
				}else{
					fwrite($fp, '{"name":"NonTerminal'.$route[$i].'","group":2}');
					break;
				}
			}
			if (in_array($route[$i], $task->tabuArray)) {
				fwrite($fp, '{"name":"Terminal'.$route[$i].'","group":1},');
			}else{
				fwrite($fp, '{"name":"NonTerminal'.$route[$i].'","group":2},');
			}		
		}
		fwrite($fp, '],');
		fwrite($fp, '"links":');
		//for ($i=0; $i < $task->amountV; $i++) { 
			fwrite($fp, $this->jsonM);
		//}
		//fwrite($fp, "]");
		fwrite($fp, "}");
		fclose($fp);
	}
}

?>