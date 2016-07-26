<?

function RandomSolver()
{
	if($_GET['method']=='ant')
	{
		AntRand();
	}elseif($_GET['method']=='d'){
		Dijkstra(1);
	}
}

function AntRand()
{
	
	if(isset($_GET['Terminal']) && isset($_GET['amountV']))
	{
		AntAlgorithm(1);
	}
	else 
	{
		AntAlgorithm(2);
	}
}