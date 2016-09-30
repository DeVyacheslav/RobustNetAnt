<?php

abstract class ATask
{
	public $TerminalV;
	public $amountV = 0;
	public $Matrix = array();
	public $RandMin = 1; //100
	public $RandMax = 100; //500
	public $start = 0;
	public $jsonMatrix;
	public $tabuArray = array();
	public $taskTerminal;
	public $task;
	
	
	abstract public function selectTV();
	abstract public function fillMatrix();
	
	
	public function BuildTask(){ 
		 $this->fillMatrix();
		 $this->selectTV();
	}
}
?>