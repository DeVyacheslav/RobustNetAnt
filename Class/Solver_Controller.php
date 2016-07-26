<?

/**
 * 
 */
class Solver_Controller {
	private $solver;
	
	function __construct($solver) {
		$this->solver = $solver;
	}
	
	public function getRoute($route, $task)
	{
		foreach ($route as $obj) {
					if(in_array($obj, $task->tabuArray))
					{
						echo $obj."T ";
					}else
						{
							echo $obj." ";
						}
				}
	}
	
	public function inputData()
	{
		$this->solver->alpha = $_GET['alpha'];
		$this->solver->beta = $_GET['beta']; 
		$this->solver->pg = $_GET['decay'];
	}
}

