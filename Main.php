<?php
include "index.html";

	spl_autoload_register(function ($class) {
	    include 'Class/' . $class . '.php';
	});
	
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
	$errors =[];
	if($_POST['customtask']!='ct')
	{
		$errors['amountV'] = $checker->checkField($_POST['amountV'], 3, 1000);	
		$errors['Terminal'] = $checker->checkField($_POST['Terminal'], 2, $_POST['amountV']);	
	}		
	$errors['alpha'] = $checker->checkField($_POST['alpha'], 0, 10);
	$errors['beta'] = $checker->checkField($_POST['beta'], 0, 10);
	$errors['decay'] = $checker->checkField($_POST['decay'], 0, 10);
	$errors['run'] = $checker->checkField($_POST['run'], 0, 100);
	$errors['numCol'] = $checker->checkField($_POST['numCol'], 10, 10000);
	
	foreach($errors as $k => $error){
		if($error !== false){
			echo "$k: $error";
			die();
		}
	}
}

?>