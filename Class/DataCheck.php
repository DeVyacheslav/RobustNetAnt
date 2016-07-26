<?

/**
 * 
 */
class DataCheck {
	
	public static function isNumeric($value)
	{
		if(!is_numeric($value))
			throw new InvalidArgumentException("Error you entered wrong data for float field");	
	}
	
	public static function inRange($value, $from, $to)
	{
		if($from >= $value || $value >= $to)
			throw new RangeException("Error data isn't in range from $from to $to");
		
	}
	
	public static	function checkField($value, $from, $to)
	{
		if(isset($value))
		{
			try
			{
				DataCheck::isNumeric($value);
				DataCheck::inRange($value, $from, $to);	
			}catch(Exception $e){
					echo 'Caught exception: ',  $e->getMessage(), "\n";
					die();
			}
				
		}
	}
	
	
}
