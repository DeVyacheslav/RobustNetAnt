<?php

/**
 * 
 */
abstract class ASolver {

	abstract public function run(TaskGenerator $generator);
	
	final public function antAlgorithm(ATask $task)
	{
		$best=9999;
		
		for($i=0; $i < $_POST['run']; $i++)
			{
				$task->BuildTask();
				
				$task_matrix = $task->Matrix;
				
				$solver = new MethodAnt($task);	//a = 1 b=0.2
	
				$controller = new MethodAntController($solver, $task);
				
				$controller->inputData();
				
				$solver->run($controller);
				
				$temp = $solver->record+$solver->secondCost;
				
				$view = new ViewAnt($solver, $controller, $task);
				
				$view->outputSolve();
				
				if($temp < $best)
				{	
					$best = $temp;
					$paramB = $beta;
				}
			}
			$view->outputTask();
			$view->outputVisualization($task_matrix);
	}
	
	final public function dijkstra(ATask $task)
	{
		$task->BuildTask();
		$solver = new MethodDij($task);
	}
}

