<?

Class MySqliUtils {

	private $con;

	public static function getConnection($host, $dbname = 'AlohaDB'){


		if($host == 'localhost')
			$con = new mysqli($host,'root','',$dbname);
		elseif($host == 'remote')
			$con = new mysqli($host,'','',$dbname);

		if ($con->connect_error) {

		    die('Error de Conexión (' . $con->connect_errno . ') '
		            . $con->connect_error);
		}

		return $con;
	}

}

?>