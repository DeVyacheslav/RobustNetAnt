<?php 

/**
 * 
 */
class MethodDij{
	public $flag = array();
	public $path = array();
	public $s;
	public $l = array();
	public $xn;
	public $xk;
	public $p;
	public $c = array();
	public $partial_path = array();
	public $route = array();
	public $route1 = array();
	public $cost1 = 0;
	public $cost2 = 0;
	
	
	function __construct($task) {
		$this->c = $task->Matrix;
		
		$time_pre = microtime(true);
		$count = count($task->tabuArray)-1;
		for($i=0; $i < $count; $i++){
			$xn = $i;
			$xk = $i+1;
			$this->Dijkstra($task, $xn, $xk);
			$this->partial_path = explode(",",$this->path[$this->p]);
			array_push($this->route,$this->partial_path );
		
		}
		
		$this->getRoute($this->route);

			
		//exit;
		$this->partial_path = array();
		
		$xn = $task->tabuArray[0];
		
		$xk = end($task->tabuArray);
		
		/*$this->c[$xn][$this->route[0][1]]= TaskGenerator::$BigNum;
		$this->c[$this->route[0][1]][$xn]= TaskGenerator::$BigNum;*/
		//exit;
		for ($i=0; $i < $count; $i++) {
			if(in_array($this->route1[$i], $task->tabuArray) || in_array($this->route1[$i+1], $task->tabuArray))	 
			{
				$this->c[$this->route1[$i]][$this->route1[$i+1]]= TaskGenerator::$BigNum;
				$this->c[$this->route1[$i+1]][$this->route1[$i]]= TaskGenerator::$BigNum;
			}
		}
		
		
		//echo "".$this->route[0][1]."xn".$xn."ASDASD".$this->c[$xn][$this->route[0][1]];
		
		//exit;
		$this->Dijkstra($task, $xn, $xk);
		
		$this->partial_path = explode(",",$this->path[$this->p]);
		
		echo "<br>Path 2: ";
		$count2 =count($this->partial_path);
		for ($i=0; $i < $count2 ; $i++) {
			if($i!=0) 
				echo ", ".$this->partial_path[$i];
			else {
				echo $this->partial_path[$i];
			}
			$this->cost2 += $this->c[$this->partial_path[$i]][$this->partial_path[$i+1]];
		}
		echo "<br>Cost 2: ".$this->cost2;
		//$this->getRoute($this->partial_path);
		
		$time_post = microtime(true);
		$solver_d_time = $time_post - $time_pre;
		$cost =$this->cost1+$this->cost2;
		echo "<br> Total cost: ".$cost;
		$count3 =count($this->route1)-1;
		for ($i=0; $i < $count2; $i++) {
			for ($j=0; $j < $count3; $j++) {
				 //echo "<br>".$this->route1[$j]."==".$this->partial_path[$i]." and ".$this->route1[$j+1]."==".$this->partial_path[$i+1];
				if($this->route1[$j]==(int)$this->partial_path[$i] && $this->route1[$j+1]==(int)$this->partial_path[$i+1])
					{

							$cost-=$this->c[(int)$this->partial_path[$i]][(int)$this->partial_path[$i+1]];
							break;
					}
			}
			
		}
		
		/*var_dump($this->partial_path);
		echo "REVERS";*/
		$this->partial_path=array_reverse($this->partial_path);
		//var_dump($this->partial_path);
		for ($i=0; $i < $count2; $i++) {
			for ($j=0; $j < $count3; $j++) {
				 //echo "<br>".$this->route1[$j]."==".$this->partial_path[$i]." and ".$this->route1[$j+1]."==".$this->partial_path[$i+1];
				if($this->route1[$j]==(int)$this->partial_path[$i] && $this->route1[$j+1]==(int)$this->partial_path[$i+1])
					{

							$cost-=$this->c[(int)$this->partial_path[$i]][(int)$this->partial_path[$i+1]];
							
							break;
						
					}
			}
		}
		echo "<br>Network cost: ".$cost;
		echo "<br>Time: ".$solver_d_time;
		
		$visualization = new Visualization($this->route1+$this->partial_path, $task, $task->Matrix);
	}
	
	public function getRoute($route)
	{
		echo "Path 1: ".$route[0][0];
		array_push($this->route1,(int)$route[0][0]);
		for ($i=0; $i < count($route); $i++) { 
			for ($j=0; $j < count($route[$i]); $j++) {
				if($j!=0) 
					{
						echo ", ".$route[$i][$j];
						array_push($this->route1,(int)$route[$i][$j]);
					}
				$this->cost1 += $this->c[$route[$i][$j]][$route[$i][$j+1]];
			}
		}
		echo "<br> Cost 1: ".$this->cost1;
		//var_dump($this->route1);
	}
	
	
	public function Dijkstra($task, $xn, $xk)
	{
		$this->xn = $xn;//$task->tabuArray[0];
		$this->xk = $xk;//end($task->tabuArray);

		$n = $task->amountV;

		for($i=0;$i< $n;$i++)
		{
			if(!in_array($i, $this->partial_path))
				$this->flag[$i]=0;
			else {
				$this->flag[$i]=1;
			}
			$this->l[$i]= TaskGenerator::$BigNum;	
		}
		//var_dump($this->l);
		
		$time_pre = microtime(true);
		$this->l[$this->xn]=0;
		$this->flag[$this->xn]=1;
		$this->p=$this->xn;

		$this->s= strval($this->xn);
		
		for($i=0;$i<$n;$i++)
		{
			$this->path[$i] =   $this->s;
		}
		
		do
		{
		for($i=0;$i<$n;$i++)
			if(($this->c[$this->p][$i]!=TaskGenerator::$BigNum)&&(!$this->flag[$i])&&($i!=$this->p))
			{
				if($this->l[$i]>$this->l[$this->p]+$this->c[$this->p][$i])
				{
					$this->s = strval($i);
					$this->path[$i] = $this->path[$this->p];
					$this->path[$i] .=  ",".$this->s;	 				
				}
				
				$this->l[$i] = $this->Minim($this->l[$i],($this->l[$this->p]+$this->c[$this->p][$i]));
				//echo "<br>p".$this->p." i ".$i." NEW L ".$this->l[$i];
				
			}		
			$this->p=$this->Min($n);
			$this->flag[$this->p]=1;
			//echo "<br> BLOCKED ".$this->p;
   
	 /* $num++;
    if($num>= 40)
			break;*/
		
		}while($this->p!=$this->xk);
		
	}
	public function Min($n)
	{
		$result = rand(0,$n-1);	
		for($i=0;$i<$n;$i++)
		 if(!($this->flag[$i]) && $this->c[$this->p][$i]!=TaskGenerator::$BigNum) 
			 {
			 	$result=$i;
				//echo "<br>selected ".$i;
				//break;
			 }
		for($i=0;$i<$n;$i++)
			if(($this->l[$result]>$this->l[$i])&&!($this->flag[$i])) 
			{
				$result=$i;
			 //echo "<br>better select ".$i;
			}	
					//echo "<br> flag".$this->flag[$result]." p: ".$this->p." cost".$this->l[$result]." Next target ".$result;
		return $result;
	}
	
	public function Minim($x, $y)
	{
		//echo "<br>".$x." MINIM ".$y;
		if($x<$y)
			return $x;
		return $y;
	}
	
	
	
}

?>