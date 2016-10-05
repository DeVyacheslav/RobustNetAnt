<?php
/**
 * 
 */
class RandomSolver extends ASolver {
	
	public function run(TaskGenerator $generator){	
		$task = $generator->genFullTask($_POST['Terminal'],$_POST['amountV']);
		if($_POST['method']=='ant')
		{
			$this->antRand($generator);
		}elseif($_POST['method']=='d'){
			$task = $generator->genFullTask($_POST['Terminalr'],$_POST['amountVr']);
			$this->dijkstra($task);
		}
	}
	
	
	public function antRand(TaskGenerator $generator)
	{
		if(isset($_POST['Terminal']) && isset($_POST['amountV']))
		{
			$task = $generator->genFullTask($_POST['Terminal'],$_POST['amountV']);
			$this->AntAlgorithm($task);
		}
		else 
		{
			$task = $generator->genFullTask(2,4);
			$this->AntAlgorithm($task);
		}
	}
}