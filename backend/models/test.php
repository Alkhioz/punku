<?php
class usuario
{
	private $pdo;
	
	public $username;
	public $password;
    
    public $id;
    public $cedula;
    public $Nombre;
    public $Apellido;  
    public $Correo;
    public $Clave;
    public $Telefono;
    public $Direccion;
    public $Rol;

    public $Foto;
    public $Nivel;
    public $Tipo;

	public function __CONSTRUCT()
	{
		try
		{
			$this->pdo = Database::StartUp();     
		}
		catch(Exception $e)
		{
			die($e->getMessage());
		}
	}


	public function RegistrarEstudiante(usuario $data)
	{
		try 
		{
		
		$stmt = $this->pdo->prepare("INSERT INTO usuario (cedula,nombre,apellidos,email,contrasena,direccion,telefono,rol) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->execute(
				array( 
                    $data->cedula,
        			$data->Nombre,
        			$data->Apellido,  
        			$data->Correo,
        			$data->Clave,
        			$data->Direccion,
        			$data->Telefono,
        			$data->Rol
                )
			);

         $stmt = $this->pdo->prepare("SELECT id_usuario FROM usuario WHERE cedula= ?");
		 $stmt->execute(
				array( 
                    $data->cedula
                )
			);
		$stmt->bindColumn('1', $id);
		$stmt->fetch(PDO::FETCH_BOUND);
		

		$stmt = $this->pdo->prepare("INSERT INTO estudiante (nivel,id_usuario) 
		        VALUES (?, ?)");
		$stmt->execute(
				array( 
                    $data->Nivel,
                    $id
                )
			);

		
        $confirmacion = preg_replace('/[^A-Za-z0-9\-]/', '', crypt( time(), substr($data->Nombre.' '.$data->cedula, 0, 2)));

        $stmt = $this->pdo->prepare("INSERT INTO usuario_verificar (idUsuario, verificacion) 
		        VALUES (?, ?)");
		$stmt->execute(
				array( 
                    $id,
                    $confirmacion
                )
			);

		$headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

        //dirección del remitente 
        $headers .= "From: noreply <noreply@ingenio.fci.utm.edu.ec>\r\n"; 
        //ASunto
        $asunto = "Confirmacion de cuenta Ingenio"; 
        $cuerpo = "Codigo de confirmacion: ".$confirmacion;
        //Cuerpo del correo que recibián copia 
        mail($data->Correo,$asunto,$cuerpo,$headers);

      
		} catch (Exception $e) 
		{
			//die($e->getMessage());
			//header('Location: /signup');
		}
	}

	public function GetMiembros($id){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare('SELECT usuario.nombre as unombre, usuario.apellidos as uapellido, miembro_grupo.fecha_registro as ufregistro from miembro_grupo INNER JOIN usuario ON (usuario.id_usuario = miembro_grupo.idUsuario) INNER JOIN grupo ON (grupo.id_grupo = miembro_grupo.idGrupo) INNER JOIN proyecto ON (proyecto.id_proyecto = grupo.id_proyecto and usuario.id_usuario != proyecto.id_usuario) where miembro_grupo.idGrupo = ?');
			$stmtuser->execute(array( 
				$id
			)); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}
	public function ListarGruposAdmin(){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare('SELECT usuario.nombre as unombre, usuario.apellidos as uapellido, docente.nombre as dnombre, docente.apellidos as dapellido, proyecto.nombre as pnombre, proyecto.objetivos as pobjetivo, proyecto.descripcion as pdescripcion, grupo.id_proyecto as gidproyecto, miembro_grupo.idGrupo as mgidgrupo, grupo.fecha_registro as gfregistro 
				from miembro_grupo
				INNER JOIN usuario ON (usuario.id_usuario = miembro_grupo.idUsuario)
				INNER JOIN grupo ON (grupo.id_grupo = miembro_grupo.idGrupo)
				INNER JOIN proyecto ON (proyecto.id_proyecto = grupo.id_proyecto 
                        AND usuario.id_usuario = miembro_grupo.idUsuario
                        AND usuario.id_usuario = proyecto.id_usuario)
				INNER JOIN usuario as docente ON (grupo.id_docente = docente.id_usuario)');
			$stmtuser->execute(); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function RegistrarDocente(usuario $data)
	{
		try 
		{
		
		$stmt = $this->pdo->prepare("INSERT INTO usuario (cedula,nombre,apellidos,email,contrasena,direccion,telefono,rol) 
		        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
		$stmt->execute(
				array( 
                    $data->cedula,
        			$data->Nombre,
        			$data->Apellido,  
        			$data->Correo,
        			$data->Clave,
        			$data->Direccion,
        			$data->Telefono,
        			$data->Rol
                )
			);

		$stmt = $this->pdo->prepare("SELECT id_usuario FROM usuario WHERE cedula= ?");
		$stmt->execute(
				array( 
                    $data->cedula
                )
			);
		$stmt->bindColumn('1', $id);
		$stmt->fetch(PDO::FETCH_BOUND);
		

		$confirmacion = preg_replace('/[^A-Za-z0-9\-]/', '', crypt( time(), substr($data->Nombre.' '.$data->cedula, 0, 2)));

        $stmt = $this->pdo->prepare("INSERT INTO usuario_verificar (idUsuario, verificacion) 
		        VALUES (?, ?)");
		$stmt->execute(
				array( 
                    $id,
                    $confirmacion
                )
			);
	    $headers = "MIME-Version: 1.0\r\n"; 
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n"; 

        //dirección del remitente 
        $headers .= "From: noreply <noreply@ingenio.fci.utm.edu.ec>\r\n"; 
        //ASunto
        $asunto = "Confirmacion de cuenta Ingenio"; 
        $cuerpo = "Codigo de confirmacion: ".$confirmacion;
        //Cuerpo del correo que recibián copia 
        mail($data->Correo,$asunto,$cuerpo,$headers);



		} catch (Exception $e) 
		{
			//die($e->getMessage());
		}
	}

    public function CambiarFoto($data)
	{
		try 
		{
			$sql = "UPDATE usuario SET 
						foto      		= ?
				    WHERE id_usuario = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->Foto, 
                        $data->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

	public function CambiarClave($data)
	{
		try 
		{
			$sql = "UPDATE usuario SET 
						contrasena      		= ?
				    WHERE id_usuario = ?";

			$this->pdo->prepare($sql)
			     ->execute(
				    array(
                        $data->Clave, 
                        $data->id
					)
				);
		} catch (Exception $e) 
		{
			die($e->getMessage());
		}
	}

		public function get_data($correo){	

		try {

			$stmtuser = $this->pdo->prepare('SELECT id_usuario, nombre, apellidos ,rol,foto FROM usuario WHERE email = :correo LIMIT 1');
			$stmtuser->execute(array('correo' => $correo));
			
			$row = $stmtuser->fetch();
			return $row;

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	    }

	public function is_logged_in(){
   		if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true){
   			return true;
   		}		
   	}
   	public function no_esta_verificado(){


   		 $stmt = $this->pdo->prepare("SELECT idUsuario FROM usuario_verificar WHERE idUsuario= ?");
		 $stmt->execute(
				array( 
                    $this->get_data($_SESSION['correo'])['id_usuario']
                )
			);
		$stmt->bindColumn('1', $id);
		$stmt->fetch(PDO::FETCH_BOUND);

		if(!is_null($id)){
			return true;
		}else {
			return false;
		}
		
   	}



	public function VerificarUsuario($codigo){	
    	

		try {

		$stmt = $this->pdo->prepare("SELECT verificacion FROM usuario_verificar WHERE idUsuario= ?");
		$stmt->execute(
				array( 
                    $this->get_data($_SESSION['correo'])['id_usuario']
                )
			);
		$stmt->bindColumn('1', $codigobase);
		$stmt->fetch(PDO::FETCH_BOUND);

		if($codigobase == $codigo){
			$stmt = $this->pdo->prepare("DELETE FROM usuario_verificar WHERE idUsuario = ? ");
			$stmt->execute(array(
			    $this->get_data($_SESSION['correo'])['id_usuario']
			));
		}

			

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

   		private function get_password($dato){	

		try {

			$stmtuser = $this->pdo->prepare('SELECT contrasena FROM usuario WHERE email = :correo');
			$stmtuser->execute(array(':correo' => $dato));
			
			$row = $stmtuser->fetch();
			return $row['contrasena'];

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	private function get_name($correo){	

		try {

			$stmtuser = $this->pdo->prepare('SELECT id_usuario, nombre, apellidos ,rol,foto FROM usuario WHERE email = :correo');
			$stmtuser->execute(array('correo' => $correo));
			
			$row = $stmtuser->fetch();
			return $row;

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function login($login){	
        
        $data = $this->get_name($login->username);
        $user = $data['nombre'];
		$hashed = $this->get_password($login->username);
		$salt = substr ($user, 0, 2);
        $password_crypt = crypt ($login->password, $salt);

		if($password_crypt == $hashed ){
		    
		    $_SESSION['loggedin'] = true;
		    //$_SESSION['rol'] = "usuario";
		    $_SESSION['correo'] = $login->username;
            //$_SESSION['nombre'] = $data['nombre'];
            //$_SESSION['apellido'] = $data['apellidos'];
            //$_SESSION['id'] = $data['id_usuario'];
            //$_SESSION['foto'] = $data['foto'];

            return true;
		}		
	}
    public function ListarSolicitudes(){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare("SELECT solicitud_estudiante.id as id, solicitud_estudiante.idGrupo as gid,usuario.foto as ufoto, usuario.nombre as unombre, usuario.apellidos as uapellido ,proyecto.nombre as pnombre
				FROM solicitud_estudiante 
				INNER JOIN usuario ON (usuario.id_usuario = solicitud_estudiante.idEmisor)
				INNER JOIN grupo ON (grupo.id_grupo = solicitud_estudiante.idGrupo)
				INNER JOIN proyecto ON (grupo.id_proyecto = proyecto.id_proyecto)
				WHERE solicitud_estudiante.idReceptor = ? ORDER BY solicitud_estudiante.id DESC");
			$stmtuser->execute(array( 
				$this->get_data($_SESSION['correo'])['id_usuario']
			)); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function ListarSolicitudesDocentes(){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare("SELECT solicitud_docente.id as id, solicitud_docente.idProyecto as gid,usuario.foto as ufoto, usuario.nombre as unombre, usuario.apellidos as uapellido ,proyecto.nombre as pnombre
				FROM solicitud_docente 
				INNER JOIN usuario ON (usuario.id_usuario = solicitud_docente.idEmisor)
				INNER JOIN proyecto ON (proyecto.id_proyecto = solicitud_docente.idProyecto)
				WHERE solicitud_docente.idReceptor = ? ORDER BY solicitud_docente.id DESC");
			$stmtuser->execute(array( 
				$this->get_data($_SESSION['correo'])['id_usuario']
			)); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function ListarDocentes(){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare("SELECT docente.id_usuario as did, docente.nombre as dnombre, docente.apellidos as dapellido FROM usuario as docente WHERE docente.rol = 'docente'");
			$stmtuser->execute(); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function ListarNoticias(){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare("SELECT contenido as contenido FROM noticia order by id_noticia desc limit 5");
			$stmtuser->execute(); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function ListarNoticiasDos(){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare("SELECT SUBSTRING(contenido, 1, 30) as contenido FROM noticia order by id_noticia desc limit 5");
			$stmtuser->execute(); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function GenerarSolicitudEstudiante($idGrupo){	
    	$resultado = array();

		try {

			$stmt = $this->pdo->prepare("Select usuario.id_usuario from usuario 
						INNER JOIN proyecto ON (usuario.id_usuario = proyecto.id_usuario) 
						INNER JOIN grupo ON (grupo.id_proyecto = proyecto.id_proyecto)
						where grupo.id_grupo = ? ");
			$stmt->execute(array(
			    $idGrupo
			)); 

			$stmt->bindColumn('1', $idReceptor);
			$stmt->fetch();

			$stmtuser = $this->pdo->prepare("INSERT INTO solicitud_estudiante (idGrupo, idEmisor, idReceptor)
			 VALUES (?, ?, ?)");
			$stmtuser->execute(array(
			    $idGrupo, 
				$this->get_data($_SESSION['correo'])['id_usuario'],
				$idReceptor
			)); 
		  	
			return $stmtuser->fetchAll(PDO::FETCH_OBJ);

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function GenerarSolicitudDocente($idGrupo, $idReceptor){	
    	$resultado = array();

		try {

			$stmtuser = $this->pdo->prepare("INSERT INTO solicitud_docente (idProyecto, idEmisor, idReceptor)
			 VALUES (?, ?, ?)");
			$stmtuser->execute(array(
			    $idGrupo, 
				$this->get_data($_SESSION['correo'])['id_usuario'],
				$idReceptor
			));

			return $stmtuser->fetchAll(PDO::FETCH_OBJ); 
		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function RechazarSolicitudEstudiante($id){	
    	$resultado = array();

		try {

			$stmt = $this->pdo->prepare("DELETE FROM solicitud_estudiante WHERE id = ? ");
			$stmt->execute(array(
			    $id
			));

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function AceptarSolicitudEstudiante($id){	
    	$resultado = array();

		try {

			$stmt = $this->pdo->prepare("SELECT id, idGrupo, idEmisor 
				FROM solicitud_estudiante WHERE idGrupo = ? ");
			$stmt->execute(array(
			    $id
			));
			$stmt->bindColumn('1', $idSolicitud);
        	$stmt->bindColumn('2', $idGrupo);
        	$stmt->bindColumn('3', $idEmisor);
        	$stmt->fetch(PDO::FETCH_BOUND);

            $stmt = $this->pdo->prepare("INSERT INTO miembro_grupo (idGrupo, idUsuario, fecha_registro)
            	VALUES (?, ?, ?) ");
            $stmt->execute(array(
			    $idGrupo,
			    $idEmisor,
			    date('Y-m-d',time())
			));

			$stmt = $this->pdo->prepare("DELETE FROM solicitud_estudiante WHERE id = ? ");
			$stmt->execute(array(
			    $idSolicitud
			));

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

	public function RechazarSolicitudDocente($id){	
    	$resultado = array();

		try {

			$stmt = $this->pdo->prepare("DELETE FROM solicitud_docente WHERE id = ? ");
			$stmt->execute(array(
			    $id
			));

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}
	public function CrearNoticia($cont){	
    	$resultado = array();

		try {

			$stmt = $this->pdo->prepare("INSERT INTO noticia(contenido) values (?) ");
			$stmt->execute(array(
			    $cont
			));

		} catch(PDOException $e) {
		    echo '<p class="error">'.$e->getMessage().'</p>';
		}
	}

}