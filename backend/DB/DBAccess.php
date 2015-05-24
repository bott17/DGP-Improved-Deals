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
		$this->local = 0;
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
		
		
		$sql = sprintf("INSERT INTO `Usuario` (`Email`, `Password`, `Nombre`, `Apellidos`,`Foto` ,`Telefono`, `Hostelero`, `Empresa`, `NIF`) 
				VALUES (%s)",
			   $filtersql);
		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);
		
		$result = $this->dbObject->query($sql);

		if($result==1){
			$response=1;
		}else $response=0;
	} else $response=-1;
		return $response;

	}

	public function search($filter = array()){
		$response = array();

		$fechaini		= isset($filter['dateini'])?$filter['dateini']:-1;
		$fechafin 		= isset($filter['dateend'])?$filter['dateend']:-1;
		$zona	 		= isset($filter['zone'])?$filter['zone']:-1;
		$habitaciones	= isset($filter['rooms'])?$filter['rooms']:-1;
		$tipo	 		= isset($filter['type'])?$filter['type']:-1;
		$pension 		= isset($filter['pension'])?$filter['pension']:-1;
		$garaje 		= isset($filter['garage'])?$filter['garage']:-1;
		$seguridad		= isset($filter['security'])?$filter['security']:-1;
		$aire			= isset($filter['airconditioner'])?$filter['airconditioner']:-1;
		$balcon			= isset($filter['balcony'])?$filter['balcony']:-1;
		$piscina		= isset($filter['swimmingpool'])?$filter['swimmingpool']:-1;
		$internet		= isset($filter['internet'])?$filter['internet']:-1;
		$calefaccion	= isset($filter['heating'])?$filter['heating']:-1;
		$tv				= isset($filter['tv'])?$filter['tv']:-1;
		$jardin			= isset($filter['garden'])?$filter['garden']:-1;
		$telefono		= isset($filter['phone'])?$filter['phone']:-1;
		$filtersql 		= "";
		
		//SELECT * FROM (SELECT * FROM (SELECT DISTINCT Habitaciones.Id_P 
		//FROM Habitaciones LEFT JOIN (Select Id_P,Id_H from Alquileres 
		//WHERE (Alquileres.FechaIni BETWEEN 'INICIO' AND 'FIN')OR(Alquileres.FechaIni BETWEEN 'INICIO' 
		//AND 'FIN')OR(Alquileres.FechaIni<='INICIO' AND Alquileres.FechaFin>='FIN')) AS Ocupadas
		//USING (Id_P,Id_H)
		//WHERE Ocupadas.Id_H IS NULL) as Libres,Propiedad
		//WHERE Libres.Id_P = Propiedad.Id_P) as Proplibres
		//WHERE 1 AND Proplibres.Zona = UNAZONA AND Proplibres.Habitaciones>=HABITACIONES AND 
		
		if($fechaini!=-1 && $fechafin!=-1){
			$filtersql .= sprintf("'%s' AND '%s')OR(Alquileres.FechaIni BETWEEN '%s' AND '%s')OR(
				Alquileres.FechaIni<='%s' AND Alquileres.FechaFin>='%s')) AS Ocupadas USING (Id_P,Id_H)
				 WHERE Ocupadas.Id_H IS NULL) as Libres,Propiedad WHERE Libres.Id_P = Propiedad.Id_P) as Proplibres WHERE 1",
				$this->dbObject->real_escape_string($fechaini),
				$this->dbObject->real_escape_string($fechafin),
				$this->dbObject->real_escape_string($fechaini),
				$this->dbObject->real_escape_string($fechafin),
				$this->dbObject->real_escape_string($fechaini),
				$this->dbObject->real_escape_string($fechafin));
				if($zona!=-1) $filtersql .= sprintf(" AND Proplibres.Zona = '%s'", $this->dbObject->real_escape_string($zona));
				if($tipo!=-1) $filtersql .= sprintf(" AND Proplibres.Tipo = '%s'", $this->dbObject->real_escape_string($tipo));
				if($habitaciones!=-1) $filtersql .= sprintf(" AND Proplibres.Habitaciones = '%d'", intval($habitaciones));
				if($pension!=-1) $filtersql .= sprintf(" AND Proplibres.Pension = '%d'", intval($pension));
				if($garaje!=-1) $filtersql .= sprintf(" AND Proplibres.Garaje = '%d'", intval($garaje));
				if($seguridad!=-1) $filtersql .= sprintf(" AND Proplibres.Seguridad = '%d'", intval($seguridad));
				if($aire!=-1) $filtersql .= sprintf(" AND Proplibres.AireAcondicionado = '%d'", intval($aire));
				if($balcon!=-1) $filtersql .= sprintf(" AND Proplibres.Balcon = '%d'", intval($balcon));
				if($piscina!=-1) $filtersql .= sprintf(" AND Proplibres.Piscina = '%d'", intval($piscina));
				if($internet!=-1) $filtersql .= sprintf(" AND Proplibres.Internet = '%d'", intval($internet));
				if($calefaccion!=-1) $filtersql .= sprintf(" AND Proplibres.Calefaccion = '%d'", intval($calefaccion));
				if($tv!=-1) $filtersql .= sprintf(" AND Proplibres.TV = '%d'", intval($tv));

		$sql = sprintf("SELECT * FROM (SELECT Propiedad.Id_P,Propiedad.Nombre,Propiedad.Descripcion,Propiedad.Estrellas, 
				Propiedad.Direccion,Propiedad.Tipo,Propiedad.Pension,Propiedad.Zona,Propiedad.Precio, 
				Propiedad.Habitaciones,Propiedad.Garaje,Propiedad.Seguridad,Propiedad.AireAcondicionado, 
				Propiedad.Balcon,Propiedad.Piscina,Propiedad.Internet,Propiedad.Calefaccion,Propiedad.TV, 
				Propiedad.Jardin,Propiedad.Telefono,Propiedad.Email FROM (SELECT DISTINCT Habitaciones.Id_P
				FROM Habitaciones LEFT JOIN (Select Id_P,Id_H from Alquileres WHERE (Alquileres.FechaIni BETWEEN %s",
				$filtersql);

		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);

		$result = $this->dbObject->query($sql);

		
		if($result){
		while($row = $result->fetch_assoc()){
				//$row=array_map(‘utf8_encode’, $rowi);
				$response['idproperty'][] 		= $row['Id_P'];
				$response['name'][] 			= $row['Nombre'];
				$response['description'][] 		= $row['Descripcion'];
				$response['stars'][] 			= $row['Estrellas'];
				$response['address'][] 			= $row['Direccion'];
				$response['type'][] 			= $row['Tipo'];
				$response['pension'][] 			= $row['Pension'];
				$response['zone'][] 			= $row['Zona'];
				$response['cost'][] 			= $row['Precio'];
				$response['rooms'][] 			= $row['Habitaciones'];
				$response['garage'][] 			= $row['Garaje'];
				$response['security'][] 		= $row['Seguridad'];
				$response['airconditioner'][] 	= $row['AireAcondicionado'];
				$response['balcony'][] 			= $row['Balcon'];
				$response['swimmingpool'][] 	= $row['Piscina'];
				$response['internet'][] 		= $row['Internet'];
				$response['heating'][] 			= $row['Calefaccion'];
				$response['tv'][] 				= $row['TV'];
				$response['garden'][] 			= $row['Jardin'];
				$response['phone'][] 			= $row['Telefono'];
				$response['email'][] 			= $row['Email'];
			}

		}else $response=0;
	}else $response = -1;
		return $response;

	}

	public function rentRoom($filter = array()){
		$response = array();

		$email 		= isset($filter['email'])?$filter['email']:-1;
		$idp 		= isset($filter['idproperty'])?$filter['idproperty']:-1;
		$fechaini	= isset($filter['dateini'])?$filter['dateini']:-1;
		$fechafin 	= isset($filter['dateend'])?$filter['dateend']:-1;
		$idh=-1;
		$filtersql 	= "";
		
		if($idp!=-1 && $fechaini != -1 && $fechafin != -1){
			$sql2 = sprintf("SELECT Id_H FROM (SELECT DISTINCT Habitaciones.Id_H,Habitaciones.Id_P FROM Habitaciones LEFT JOIN 
				(Select Id_P,Id_H from Alquileres WHERE (Alquileres.FechaIni BETWEEN '%s' AND '%s')
				OR(Alquileres.FechaIni BETWEEN '%s' AND '%s')OR(Alquileres.FechaIni<='%s' 
				AND Alquileres.FechaFin>='%s')) AS Ocupadas USING (Id_P,Id_H) WHERE Ocupadas.Id_H IS NULL) as Libres
				WHERE Libres.Id_P = %d LIMIT 1",
				$this->dbObject->real_escape_string($fechaini),
				$this->dbObject->real_escape_string($fechafin),
				$this->dbObject->real_escape_string($fechaini),
				$this->dbObject->real_escape_string($fechafin),
				$this->dbObject->real_escape_string($fechaini),
				$this->dbObject->real_escape_string($fechafin),
				$this->dbObject->real_escape_string($idp),
				$this->dbObject->real_escape_string($email),
				intval($idp));
			if($this->local==1)$sql2=iconv('UTF-8', 'ISO-8859-1', $sql2);
			$result2 = $this->dbObject->query($sql2);
			$row2 = $result2->fetch_assoc();
			$idh=$row2['Id_H'];


		}
		
		if($email != -1 && $idp != -1 && $idh != -1 && $fechaini != -1 && $fechafin != -1 ){
			$filtersql .= sprintf("'%d','%d','%s','%s','%s'",
				intval($idh),
				intval($idp),				
				$this->dbObject->real_escape_string($fechaini),
				$this->dbObject->real_escape_string($fechafin),
				$this->dbObject->real_escape_string($email));

		
		$sql = sprintf("INSERT INTO `Alquileres` (`Id_H`, `Id_P`, `FechaIni`, `FechaFin`, `EmailInvitado`) 
			VALUES (%s)", $filtersql);
		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);

		$result = $this->dbObject->query($sql);

		if($result==1){
			$response=1;
		}else $response=0;
	} else $response=-1;
		return $response;

	}
	public function logIn($filter = array()){
		$response = array();

		$email			= isset($filter['email'])?$filter['email']:-1;
		$password 		= isset($filter['password'])?$filter['password']:-1;
		$filtersql 		= "";
				
		if($email!=-1 && $password!=-1){
			$filtersql .= sprintf("Email = '%s' AND Password='%s'",
				$this->dbObject->real_escape_string($email),
				$this->dbObject->real_escape_string($password));
		$sql = sprintf("SELECT * FROM `Usuario` WHERE %s;",	$filtersql);

		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);

		$result = $this->dbObject->query($sql);

		
		if($result){
			$row = $result->fetch_assoc();
			if($password==$row['Password']){
				$response['user']['email']	 		= $row['Email'];
				$response['user']['name']	 		= $row['Nombre'];
				$response['user']['surname'] 		= $row['Apellidos'];
				$response['user']['phone']	 		= $row['Telefono'];
				$response['user']['picture'] 		= $row['Foto'];
				$response['user']['hostelero']	 	= $row['Hostelero'];
				$response['user']['company-name']	= $row['Empresa'];
				$response['user']['nif'] 			= $row['NIF'];
			}else $response=-2;

		}else $response=0;
	}else $response = -1;
		return $response;

	}
	public function listSimilar($filter = array()){
		$response = array();

		$zone			= isset($filter['zone'])?$filter['zone']:-1;
		$idp 		= isset($filter['idproperty'])?$filter['idproperty']:-1;
		$filtersql 		= "";
				
		if($zone!=-1 && $idp!=-1){
			$filtersql .= sprintf("Zona = '%s' AND Id_P <> '%d' LIMIT 20",
				$this->dbObject->real_escape_string($zone),
				intval($idp));
		$sql = sprintf("SELECT * FROM `Propiedad` WHERE %s;",	$filtersql);
		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);

		$result = $this->dbObject->query($sql);

		
		if($result){
		while($row = $result->fetch_assoc()){

				$response['idproperty'][] 		= $row['Id_P'];
				$response['name'][] 			= $row['Nombre'];
				$response['description'][] 		= $row['Descripcion'];
				$response['stars'][] 			= $row['Estrellas'];
				$response['address'][] 			= $row['Direccion'];
				$response['type'][] 			= $row['Tipo'];
				$response['pension'][] 			= $row['Pension'];
				$response['zone'][] 			= $row['Zona'];
				$response['cost'][] 			= $row['Precio'];
				$response['rooms'][] 			= $row['Habitaciones'];
				$response['garage'][] 			= $row['Garaje'];
				$response['security'][] 		= $row['Seguridad'];
				$response['airconditioner'][] 	= $row['AireAcondicionado'];
				$response['balcony'][] 			= $row['Balcon'];
				$response['swimmingpool'][] 	= $row['Piscina'];
				$response['internet'][] 		= $row['Internet'];
				$response['heating'][] 			= $row['Calefaccion'];
				$response['tv'][] 				= $row['TV'];
				$response['garden'][] 			= $row['Jardin'];
				$response['phone'][] 			= $row['Telefono'];
				$response['email'][] 			= $row['Email'];
			}
		

		}else $response=0;
	}else $response = -1;
		return $response;

	}	

	public function listPropUser($filter = array()){
		$response = array();

		$email			= isset($filter['email'])?$filter['email']:-1;
		$filtersql 		= "";
				
		if($email!=-1){
			$filtersql .= sprintf("Email = '%s'",
				$this->dbObject->real_escape_string($email));
		$sql = sprintf("SELECT * FROM `Propiedad` WHERE %s;",	$filtersql);
		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);

		$result = $this->dbObject->query($sql);

		
		if($result){
		while($row = $result->fetch_assoc()){

				$response['idproperty'][] 		= $row['Id_P'];
				$response['name'][] 			= $row['Nombre'];
				$response['description'][] 		= $row['Descripcion'];
				$response['stars'][] 			= $row['Estrellas'];
				$response['address'][] 			= $row['Direccion'];
				$response['type'][] 			= $row['Tipo'];
				$response['pension'][] 			= $row['Pension'];
				$response['zone'][] 			= $row['Zona'];
				$response['cost'][] 			= $row['Precio'];
				$response['rooms'][] 			= $row['Habitaciones'];
				$response['garage'][] 			= $row['Garaje'];
				$response['security'][] 		= $row['Seguridad'];
				$response['airconditioner'][] 	= $row['AireAcondicionado'];
				$response['balcony'][] 			= $row['Balcon'];
				$response['swimmingpool'][] 	= $row['Piscina'];
				$response['internet'][] 		= $row['Internet'];
				$response['heating'][] 			= $row['Calefaccion'];
				$response['tv'][] 				= $row['TV'];
				$response['garden'][] 			= $row['Jardin'];
				$response['phone'][] 			= $row['Telefono'];
				$response['email'][] 			= $row['Email'];
			}
		

		}else $response=0;
	}else $response = -1;
		return $response;

	}	

	public function lastProperties(){
		$response = array();

		$ok = True;
		if($ok){
		$sql = sprintf("SELECT * FROM `Propiedad` ORDER BY Propiedad.Id_P DESC LIMIT 6;");
		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);

		$result = $this->dbObject->query($sql);

		
		if($result){
		while($row = $result->fetch_assoc()){

				$response['idproperty'][] 		= $row['Id_P'];
				$response['name'][] 			= $row['Nombre'];
				$response['description'][] 		= $row['Descripcion'];
				$response['stars'][] 			= $row['Estrellas'];
				$response['address'][] 			= $row['Direccion'];
				$response['type'][] 			= $row['Tipo'];
				$response['pension'][] 			= $row['Pension'];
				$response['zone'][] 			= $row['Zona'];
				$response['cost'][] 			= $row['Precio'];
				$response['rooms'][] 			= $row['Habitaciones'];
				$response['garage'][] 			= $row['Garaje'];
				$response['security'][] 		= $row['Seguridad'];
				$response['airconditioner'][] 	= $row['AireAcondicionado'];
				$response['balcony'][] 			= $row['Balcon'];
				$response['swimmingpool'][] 	= $row['Piscina'];
				$response['internet'][] 		= $row['Internet'];
				$response['heating'][] 			= $row['Calefaccion'];
				$response['tv'][] 				= $row['TV'];
				$response['garden'][] 			= $row['Jardin'];
				$response['phone'][] 			= $row['Telefono'];
				$response['email'][] 			= $row['Email'];
			}
		

		}else $response=0;
	}else $response = -1;
		return $response;

	}

	public function lastComments(){
		$response = array();

		$ok = True;
		if($ok){
		$sql = sprintf("SELECT Usuario.Email,Usuario.Nombre,Usuario.Apellidos,Usuario.Foto, Valorar.Id_C,Valorar.Id_P,Valorar.Estrellas,
			Valorar.Fecha,Valorar.Comentario FROM Usuario,Valorar WHERE Usuario.Email=Valorar.Email ORDER BY Valorar.Id_C DESC LIMIT 4");
		if($this->local==1)$sql=iconv('UTF-8', 'ISO-8859-1', $sql);

		$result = $this->dbObject->query($sql);

		
		if($result){
		while($row = $result->fetch_assoc()){

				$response['surname'][] 		= $row['Apellidos'];
				$response['name'][] 			= $row['Nombre'];
				$response['picture'][] 		= $row['Foto'];
				$response['idcomment'][] 			= $row['Id_C'];
				$response['idproperty'][] 			= $row['Id_P'];
				$response['stars'][] 			= $row['Estrellas'];
				$response['date'][] 			= $row['Fecha'];
				$response['comment'][] 			= $row['Comentario'];
				$response['email'][] 			= $row['Email'];
			}
		

		}else $response=0;
	}else $response = -1;
		return $response;

	}
}

?>