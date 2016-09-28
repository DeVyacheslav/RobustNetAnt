<?
/**
 * 
 */
class TwoOpt {
	public $route = array();
	public  $new_route = array();
	public $bestRoute = array();
	public $bestCF;
	
	function __construct($task, &$route, &$bestCF) {
		$this->bestRoute = &$route;
		// Get tour size
   $size = count($this->bestRoute);
		$this->bestCF = &$bestCF;
    // repeat until no improvement is made 
    $improve = 0;
 
    while ( $improve < 10)
    {
        
				
					for ( $i = 1; $i < $size - 1; $i++ ) 
					{
							for ( $k = $i + 1; $k < $size; $k++) 
							{
									
									$this->TwoOptSwap($task, $i, $k);
									foreach ($this->new_route as $key => $value) {
										if ($key != count($this->new_route) /*&& $task->Matrix[$value][$value+1] != TaskGenerator::$BigNum && $task->Matrix[$value][$value+1] !='' && $value != ''*/ ) {
												//echo "<br>Key".$key." Value".$value." Matrix value".$task->Matrix[$value][$value+1];
												$new_distance += $task->Matrix[$value][$value+1];
										 }	
									 }
									
								 // echo "tut";

									if ( $new_distance < $this->bestCF /*&& in_array($this->new_route, $task->tabuArray)*/ /*&& !array_diff($task->tabuArray, $this->new_route)*/) 
									{
											// Improvement found so reset
											$improve = 0;
	
											$this->bestRoute = $this->new_route;

										 $this->bestCF = $new_distance;

									}
							}
					}
	 
					$improve ++;
			
		}
	}
	public function TwoOptSwap($task, $i, $k)
	{
		$size = count($this->bestRoute);

			$this->new_route[0] = $this->bestRoute[0];
			// 1. take route[1] to route[i-1] and add them in order to new_route
			for ( $c = 1; $c <= $i - 1; ++$c )
			{
				 $this->new_route[$c] = $this->bestRoute[$c]; //new_tour.SetCity( $c, tour.GetCity( $c ) );
			}
		//	echo "<br> 1 new route"; print_r(array_values($this->new_route));
			// 2. take route[i] to route[k] and add them in reverse order to new_route
			$dec = 0;
			for ( $c = $i; $c <= $k; ++$c )
			{
					$this->new_route[$c] = $this->bestRoute[$k-$dec];//new_tour.SetCity( $c, tour.GetCity( $k - $dec ) );
					$dec++;
			}
			// 3. take route[k+1] to end and add them in order to new_route
			for ($c = $k + 1; $c < $size; ++$c )
			{
					$this->new_route[$c] = $this->bestRoute[$c];//new_tour.SetCity( $c, tour.GetCity( $c ) );
			}
			
	}
}

?>