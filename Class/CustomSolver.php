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
