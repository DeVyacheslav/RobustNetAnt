<?php

interface ICheck{
	public function checkField($value, $from, $to);
}
/**
 * 
 */
class DataCheck implements ICheck {
	
	public function isNumeric($value)
	{
		if(!is_numeric($value))
			throw new InvalidArgumentException("Error you entered wrong data for numeric field");	
	}
	
	public function inRange($value, $from, $to)
	{
		if($from >= $value || $value >= $to)
			throw new RangeException("Error data isn't in range from $from to $to");
		
	}
	
	public function checkField($value, $from, $to)
	{
		if(isset($value))
		{
			try
			{
				$this->isNumeric($value);
				$this->inRange($value, $from, $to);	
			}catch(Exception $e){
					echo 'Caught exception: ',  $e->getMessage(), "\n";
					die();
			}
				
		}
	}
	
	
}
