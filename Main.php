<?php
require_once "Class/Autoloader.php";
include "index.html";
	autoloadFiles();
	
	inputValidation(new DataCheck);

	if($_POST['customtask']=='ct')
	{	
		$obj = new CustomSolver;
		runSolver($obj);
	}elseif($_POST['customtask']=='ft'){
		$obj = new RandomSolver;
		runSolver($obj);
	}

function runSolver(ASolver $solver)
{
	$generator = new TaskGenerator;
	$solver->run($generator);
}

function inputValidation(ICheck $checker)
{
	//$checker = new DataCheck();
	if($_POST['customtask']!='ct')
	{
		$checker->checkField($_POST['amountV'], 3, 1000);	
		$checker->checkField($_POST['Terminal'], 2, $_POST['amountV']);	
	}		
	$checker->checkField($_POST['alpha'], 0, 10);
	$checker->checkField($_POST['beta'], 0, 10);
	$checker->checkField($_POST['decay'], 0, 10);
	$checker->checkField($_POST['run'], 0, 100);
	$checker->checkField($_POST['numCol'], 10, 10000);
}

?>