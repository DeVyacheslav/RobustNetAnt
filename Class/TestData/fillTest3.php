<?php
		$lines = file("data.txt");
		$lines2 = file("data2.txt");
		$matrix = array();
		for($i=0; $i < 38; $i++)
		{
			for($j=0; $j < 38; $j++)
			{
			  $matrix[$i][$j] = 999;
			}
		}
		foreach($lines as $numLine => $line)
		{
		
			$values = explode(" ",$line);
			
			
			foreach($values as $numString => $num)
			{
					
						$matrix[$numLine][trim($num)] = trim($num);
					// echo "<br>numLine".$numLine." num ".$num." matrix".$matrix[$numLine][trim($num)] ;
			} 
		
		}
		
		foreach($lines2 as $numLine => $line)
		{
		
			$values = explode(" ",$line);
			var_dump($values);
			$temp=0;
			foreach($values as $numString => $num)
			{ $j=0;
					for($i=0; $i < 38; $i++)
						{
						  echo "<br>numLine".$numLine." num ".$i." matrix".$matrix[$numLine][$i] ;
							if(/*$matrix[$numLine][$i] == 1000 &&*/ $i ==$matrix[$numLine][$i] )
							{
								$matrix[$numLine][$i] = trim($num);
								   echo "<br>numLine".$numLine." num ".$i." matrix".$matrix[$numLine][$i] ;
								 
								  break;
							}
							$j++;
						}
					
			} 
		
		}
		$fp = fopen('Filled.txt', 'w');
		for($i=0; $i < 38; $i++)
		{
			for($j=0; $j < 38; $j++)
			{
				if($j!=0)
					fwrite($fp, " ".$matrix[$i][$j]);
					else fwrite($fp, $matrix[$i][$j]);
			}
			fwrite($fp, PHP_EOL);
		}
?>