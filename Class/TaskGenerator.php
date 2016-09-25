<?

/**
 * 
 */
class TaskGenerator {
public static $BigNum = 999;

	  public function genCustomTask()
	 {
			return new CustomTask();
	 }
	 
	 public function genFullTask($TV,$amountV)
	 {
			return new FullTask($TV,$amountV);
	 }
}
?>