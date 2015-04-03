<?

include ('utils/Mysqli.php');

class DBAccess {
	private $dbObject;
	private static $instance;
	
	/**
	*	@brief Devuelve una instancia de la clase
	*	@return Instancia DBAccess
	*/
	public static function getInstance(){
		if(!self::$instance instanceof self){
			self::$instance = new self;
		}
		return self::$instance;
	}

	private function __construct(){
		// Posibles valores para getConnection 1er parametro: 'localhost o remote ' y 2º parametro: nombre de la base de datos
		$this->dbObject = MySqliUtils::getConnection('localhost'); 
	}
	
	public function __destruct(){
		if(is_object($this->dbObject)){
			$this->dbObject->close();
		}
	}

	public function test(){

		$array = array();
		
		$sql = sprintf("SELECT `nombre` FROM `test`");
		
		$result = $this->dbObject->query($sql);

		if($result){
			while($row = $result->fetch_assoc()){
				$array['nombre'][] = $row['nombre'];
			}
		}
	
		return $array;

	}




}

?>