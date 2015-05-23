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

	public function registerUser($filter = array()){
		$response = array();

		$user = isset($filter['user'])?$filter['user']:-1;
		$password = isset($filter['pass'])?$filter['pass']:-1;
		$filtersql = "";
		
		
		if($user != -1 && $password != -1 ){
			$filtersql .= sprintf(" AND user= '%s' AND password= '%s'",
				$this->dbObject->real_escape_string($user),
				$this->dbObject->real_escape_string($password));
		}
		
		$sql = sprintf("SELECT `nombre` FROM `test` WHERE 1 %s",
			   $filtersql);
		
		
		
		$result = $this->dbObject->query($sql);

		if($result){
			while($row = $result->fetch_assoc()){
				$response['nombre'][] = $row['nombre'];
			}
		}
		return $response;

	}

}

?>