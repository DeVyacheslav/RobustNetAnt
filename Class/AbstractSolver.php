<?

/**
 * 
 */
abstract class ASolver {

	abstract public function Run();
	 
	final public static function GenTask($value)
	{
		switch ($value) {
		case '0':
			$task = TaskGenerator::GenCustomTask();
			break;
		
		case '1':
			$task = TaskGenerator::GenFullTask($_GET['Terminal'],$_GET['amountV']);
			break;
			
		case '2':
			$task = TaskGenerator::GenFullTask(2,4);
			break;
		default:
			
			break;
			}

		return $task;
	}
	
	final public static function AntAlgorithm($genTask)
	{
		$best=9999;
		
		for($i=0; $i < $_GET['run']; $i++)
			{

				$task = ASolver::GenTask($genTask);

				$task->BuildTask();
				
				$task_matrix = $task->Matrix;
				
				$solver = new Solver($task);	//a = 1 b=0.2
	
				$controller = new Solver_Controller($solver);
				
				$controller->inputData();
				
				$solver->Run();
				
				$temp = $solver->currentRecord+$solver->secondCost;
				
				$view = new ViewSolver($solver, $controller, $task);
				
				$view->outputSolve();
				
				if($temp < $best)
				{	
					$best = $temp;
					$paramB = $beta;
					$bestRoute = array_merge($solver->currentRecordRoute, $solver->secondRoute);	
					$visualization = new Visualization($bestRoute, $task, $task_matrix);
				}
			}	
	}
	
	final public static function Dijkstra($genTask)
	{
		$task = ASolver::GenTask($genTask);
		$task->BuildTask();
		$solver = new Solver_D($task);
	}
}


/**
 * 
 */
class CustomSolver extends ASolver {
	public function Run()
	{
		if($_GET['method']=='ant')
		{
			ASolver::AntAlgorithm(0);
		}elseif($_GET['method']=='d'){
			ASolver::Dijkstra(0);
		}
	}

}

/**
 * 
 */
class RandomSolver extends ASolver {
	
	public function Run(){
		if($_GET['method']=='ant')
		{
			RandomSolver::AntRand();
		}elseif($_GET['method']=='d'){
			ASolver::Dijkstra(1);
		}
	}
	
	
	public static function AntRand()
	{
		if(isset($_GET['Terminal']) && isset($_GET['amountV']))
		{
			ASolver::AntAlgorithm(1);
		}
		else 
		{
			ASolver::AntAlgorithm(2);
		}
	}
}
