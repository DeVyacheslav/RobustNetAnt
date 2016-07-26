<?php
include_once "Class/TaskGenerator.php";
include_once "Class/Solver.php";
include_once "Class/Solver_D.php";	
include_once "Class/Visualization.php";
include_once 'Class/ViewSolver.php';
include_once 'Class/Solver_Controller.php';
include_once 'Class/DataCheck.php';
include_once 'Class/CustomSolver.php';
include_once 'Class/RandomSolver.php';
include_once 'Class/AbstractSolver.php';
	
//try{
		
	inputValidation();

	if($_GET['customtask']=='ct')
	{	
		$obj = new CustomSolver;
		$obj->Run();
	}elseif($_GET['customtask']=='ft'){
		$obj = new RandomSolver;
		$obj->Run();
	}
/*}catch(Exception $e){
		echo 'Caught exception: ',  $e->getMessage(), "\n";
}*/


function inputValidation()
{
	DataCheck::checkField($_GET['amountV'], 3, 1000);	
	DataCheck::checkField($_GET['Terminal'], 2, $_GET['amountV']);			
	DataCheck::checkField($_GET['alpha'], 0, 10);
	DataCheck::checkField($_GET['beta'], 0, 10);
	DataCheck::checkField($_GET['decay'], 0, 10);
	DataCheck::checkField($_GET['run'], 0, 100);
}

?>