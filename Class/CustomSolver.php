<?

function CustomSolver()
{
	if($_GET['method']=='ant')
	{
		AntAlgorithm(0);
	}elseif($_GET['method']=='d'){
		Dijkstra(0);
	}
}
	
	