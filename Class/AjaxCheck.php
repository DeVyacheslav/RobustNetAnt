<?
include_once 'DataCheck.php';

$q = $_REQUEST['q'];
$input = $_REQUEST['i'];

if($q !=="")
{
	$checker = new DataCheck;
	if(in_array($input, ['alpha','beta','decay','run']))
		$error = $checker->checkField($q, 0, 10);
	if(in_array($input, ['numCol']))
		$error = $checker->checkField($q, 10, 1000);
	if(in_array($input, ['amountV','amountVr','Terminal','Terminalr']))
		$error = $checker->checkField($q, 3, 1000);
	if($error !== false){
		echo "$input: $error";
	}
}
