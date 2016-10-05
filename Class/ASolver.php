<?php

/**
 * 
 */
abstract class ASolver {

	abstract public function run(TaskGenerator $generator);
	
	final public function antAlgorithm(ATask $task)
	{
		$best=9999;
		$run = $_POST['run'];
		for($i=0; $i < $run; $i++)
			{
				 // Calculate the percentation
			    $percent = intval($i/$run * 100)."%";
			    
			    // Javascript for updating the progress bar and information
			    echo '<script language="javascript">
			    document.getElementById("progress").innerHTML="<div style=\"width:'.$percent.';background-color:#ddd;\">&nbsp;</div>";
			    document.getElementById("information").innerHTML="'.$i.' task(s) processed.";
			    </script>';
			
			    
			// This is for the buffer achieve the minimum size in order to flush data
			    echo str_repeat(' ',1024*64);
			
			    
			// Send output to browser immediately
			    flush(); 
				$task->BuildTask();
				
				$task_matrix = $task->Matrix;
				
				$solver = new MethodAnt($task);	//a = 1 b=0.2
	
				$controller = new MethodAntController($solver, $task);
				
				$controller->inputData();
				
				$solver->run($controller);
				
				$temp = $solver->record+$solver->record2;
				
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
			// Tell user that the process is completed
			echo '<script language="javascript">document.getElementById("information").innerHTML="Process completed"</script>';
	}
	
	final public function dijkstra(ATask $task)
	{
		$task->BuildTask();
		$solver = new MethodDij($task);
	}
}

