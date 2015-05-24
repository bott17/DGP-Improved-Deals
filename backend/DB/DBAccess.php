<?

include ('utils/MySqli.php');

class DBAccess {
	private $dbObject;
	private static $instance;
	private $local;
	
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
		$this->local = 1;
	}
	
	public function __destruct(){
		if(is_object($this->dbObject)){
			$this->dbObject->close();
		}
	}

	public function registerUser($filter = array()){
		$response = array();

		$email 		= isset($filter['email'])?$filter['email']:-1;
		$password 	= isset($filter['password'])?$filter['password']:-1;
		$nombre 	= isset($filter['name'])?$filter['name']:-1;
		$apellidos 	= isset($filter['surname'])?$filter['surname']:-1;
		$telefono 	= isset($filter['phone'])?$filter['phone']:-1;
		$hostelero 	= isset($filter['hostelero'])?$filter['hostelero']:-1;
		$empresa 	= isset($filter['company-name'])?$filter['company-name']:-1;
		$nif 		= isset($filter['nif'])?$filter['nif']:-1;
		$filtersql 	= "";
		
		//INSERT INTO `AlohaDB`.`Usuario` (`Email`, `Password`, `Nombre`, `Apellidos`, `Telefono`, `Foto`, `Hostelero`, `Empresa`, `NIF`)
		// VALUES ('hostelero@demicorason.com', 'hostelero', 'Hostelero', 'Hostelerín Hostelerete', '662015816', 'invitado.jpg', '1', 
		//'Hosteleros SA', 'A1234567A');
		
		if($email != -1 && $password != -1 && $nombre != -1 && $apellidos != -1 && $telefono != -1 && $hostelero != -1 && 
			(($hostelero==0)||($empresa!=-1&&$nif!=-1))){
			$filtersql .= sprintf("'%s','%s','%s','%s','invitado.jpg','%d','%d'",
				$this->dbObject->real_escape_string($email),
				$this->dbObject->real_escape_string($password),
				$this->dbObject->real_escape_string($nombre),
				$this->dbObject->real_escape_string($apellidos),
				intval($telefono),
				intval($hostelero));
				if($hostelero==0) $filtersql .= sprintf(",NULL,NULL");
				else $filtersql .= sprintf(",'%s','%s'",
				$this->dbObject->real_escape_string($empresa),
				$this->dbObject->real_escape_string($nif));
		}
		
		$sql = sprintf("INSERT INTO `Usuario` (`Email`, `Password`, `Nombre`, `Apellidos`,`Foto` ,`Telefono`, `Hostelero`, `Empresa`, `NIF`) 
				VALUES (%s)",
			   $filtersql);
		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);
		
		$result = $this->dbObject->query($sql);

		if($result==1){
			$response=1;
		}else $response=0;
		return $response;

	}

}

?>