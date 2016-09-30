<?php

abstract class ATask
{
	public $TerminalV;
	public $amountV = 0;
	public $Matrix = array();
	public $RandMin = 1; //100
	public $RandMax = 100; //500
	public $start = 0;
	public $jsonMatrix;
	public $tabuArray = array();
	public $taskTerminal;
	public $task;
	
	
	abstract public function selectTV();
	abstract public function fillMatrix();
	
	/*
	public function fillJson(){
		$k=0;
		for ($i=0; $i < $this->amountV; $i++) { 
			for ($j=0; $j < $this->amountV; $j++) { 
				if($i!=$j && $this->Matrix[$i][$j]!=TaskGenerator::$BigNum)
				{
					$this->jsonMatrix[$k]= array("source"=>$i, "target"=>$j, "value"=>$this->Matrix[$i][$j]);
					$k++;
				}
			}
		}
		return json_encode($this->jsonMatrix);
	}*/
/*
	public function createFile(){
		$fp = fopen('miserables.json', 'w');
		
		fwrite($fp, "{");
		fwrite($fp, ' "nodes":[');
		for ($i=0; $i < $this->amountV; $i++) {
			if($i+1 == $this->amountV){
				if (in_array($i, $this->tabuArray)) {
					fwrite($fp, '{"name":"Terminal'.$i.'","group":1}');
					break;
				}else{
					fwrite($fp, '{"name":"NonTerminal'.$i.'","group":2}');
					break;
				}
			}
			if (in_array($i, $this->tabuArray)) {
				fwrite($fp, '{"name":"Terminal'.$i.'","group":1},');
			}else{
				fwrite($fp, '{"name":"NonTerminal'.$i.'","group":2},');
			}		
		}
		fwrite($fp, '],');
		fwrite($fp, '"links":');
		//for ($i=0; $i < $this->amountV; $i++) { 
			fwrite($fp, $this->fillJson());
		//}
		//fwrite($fp, "]");
		fwrite($fp, "}");
		fclose($fp);
	}
	*/
	public function BuildTask(){
		 
		 $this->fillMatrix();
		 //$this->fillJson();
		 $this->selectTV();
		 //$this->createFile($task); 
	}
}
/*
class CustomTask extends ATask{
	 	
	public function selectTV(){

		 for($i=0; $i<$this->TerminalV; $i++)
		 {
		 	array_push($this->tabuArray, $i);
		 }
		 $this->taskTerminal = $this->tabuArray;
	}
	
	
	public function fillMatrix(){
		$num = $_POST['task'];
		$lines = file("Class/TestData/Test".$num.".txt");
		foreach($lines as $numLine => $line)
		{
			if($numLine != 0){
			$values = explode(" ",$line);
			foreach($values as $numString => $num)
			{
				$this->Matrix[$numLine-1][$numString] = (int)$num;
			} 
			$this->amountV = $numLine;
			}else
				$this->TerminalV = $line;
			
		}
	}
}*/
/*
class FullTask extends ATask{

	
	function __construct($TV,$amountV) {
		$this->TerminalV = $TV;
		$this->amountV = $amountV;
	}
	
	
	
	/*
	 * 
	 
	 public function selectTV()
	 {
	 	 $tempMax = $this->amountV-1;	 
		 $temp = rand(0, $tempMax);  
		 $temp2 =0;
		 $flag = true;
		 
			while($flag)	
		 {
		 	if(!in_array($temp, $this->tabuArray))
			{
		 		array_push($this->tabuArray,$temp); 
	
				$temp2++;
			}else{
				$temp = rand(0, $tempMax); 
			}
			
			if($this->TerminalV == $temp2)
				$flag = false;
		 }
		} 
	 

	

	public function fillMatrix()
	{
		for ($i=0; $i < $this->amountV; $i++) {
			for ($j=0; $j < $this->amountV; $j++) {
					if ($i==$j) {
						$this->Matrix[$i][$j] = TaskGenerator::$BigNum;
						$this->start++;
					} elseif($this->start > $j) {
						$this->Matrix[$i][$j] = $this->Matrix[$j][$i];
					} else{
						$this->Matrix[$i][$j] = rand($this->RandMin,$this->RandMax);
					}
				//echo $this->Matrix[$i][$j].' | | ';
			} 
			//echo "<br>";		 
		}
		$this->start=0;
	}
		
}*/
?>