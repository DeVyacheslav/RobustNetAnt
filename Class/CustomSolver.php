<?php

/**
 * 
 */
class CustomSolver extends ASolver {
	public function run(TaskGenerator $generator)
	{
		$task = $generator->genCustomTask();
		if($_POST['method']=='ant')
		{
			$this->antAlgorithm($task);
		}elseif($_POST['method']=='d'){
			$this->dijkstra($task);
		}
	}

}
/*
class CustomTask extends ATask{
	 	
	public function selectTV(){

		 for($i=0; $i<$this->TerminalV; $i++)
		 {
		 	array_push($this->tabuArray, $i);
		 }
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