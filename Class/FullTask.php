<?
class FullTask extends ATask{

	
	function __construct($TV,$amountV) {
		$this->TerminalV = $TV;
		$this->amountV = $amountV;
	}
	
	
	
	/*
	 * 
	 */
	 public function selectTV()
	 {
	 	 $tempMax = $this->amountV-1;	 
		 $temp = rand(0, $tempMax);  
		 $temp2 =0;
		 $flag = true;
		 
			while($flag)	
		 {
		 	if(!in_array($temp, $this->tabuArray))
			{
		 		array_push($this->tabuArray,$temp); 
	
				$temp2++;
			}else{
				$temp = rand(0, $tempMax); 
			}
			
			if($this->TerminalV == $temp2)
				$flag = false;
		 }
		 $this->taskTerminal = $this->tabuArray;
	} 
	 

	
	/*
	 * * 
	 */
	public function fillMatrix()
	{
		for ($i=0; $i < $this->amountV; $i++) {
			for ($j=0; $j < $this->amountV; $j++) {
					if ($i==$j) {
						$this->Matrix[$i][$j] = TaskGenerator::$BigNum;
						$this->start++;
					} elseif($this->start > $j) {
						$this->Matrix[$i][$j] = $this->Matrix[$j][$i];
					} else{
						$this->Matrix[$i][$j] = rand($this->RandMin,$this->RandMax);
					}
				//echo $this->Matrix[$i][$j].' | | ';
			} 
			//echo "<br>";		 
		}
		$this->start=0;
	}
		
}