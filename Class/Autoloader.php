<?php
function autoloadFiles()
{
	require_once "Class/TaskGenerator.php";
	require_once 'Class/IMethod.php';
	require_once "Class/MethodAnt.php";
	require_once "Class/MethodDij.php";	
	require_once "Class/Visualization.php";
	require_once 'Class/ViewAnt.php';
	require_once 'Class/MethodAntController.php';
	require_once 'Class/DataCheck.php';
	require_once 'Class/ASolver.php';
	require_once 'Class/ATask.php';
	require_once 'Class/TwoOpt.php';
	require_once 'Class/Ant.php';
	require_once 'Class/CustomSolver.php';
	require_once 'Class/RandomSolver.php';
}
