<?
include_once 'Task_interface.php';

/**
 * 
 */
class TaskGenerator {
public static $BigNum = 999;

	  public static function GenCustomTask()
	 {
			return new CustomTask();
	 }
	 
	 public static function GenFullTask($TV,$amountV)
	 {
			return new FullTask($TV,$amountV);
	 }
}
?>