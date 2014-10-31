<?php
	include 'config.php';
	function create()
	{
		global $status;
		if(!isset($_POST['name']) || strlen(trim($_POST['name'])) == 0 || !isset($_POST['apPaterno']) || strlen(trim($_POST['apPaterno'])) == 0 || !isset($_POST['email']) || strlen(trim($_POST['email'])) == 0)
			$status = array("error" => "Datos incompletos");
		else{
			try{
				$conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = 'INSERT INTO usuario(nombre, ap_paterno, ap_materno, email) values(:name, :ap_paterno, :ap_materno, :email)';
				$query = $conn->prepare($sql);
				$query->execute(array(':name' => $_POST['name'],
					':ap_paterno' => $_POST['apPaterno'],
					':ap_materno' => $_POST['apMaterno'],
					':email' => $_POST['email']));
				$status = array("success" => "Datos guardados correctamente");				
			}catch(PDOException $e){
				$status = array("error" => "Ocurrió un error al intentar guardar los datos del usuario");
			}
		}
	}

	function delete()
	{
		global $status;
		if(!isset($_POST['user']) || strlen(trim($_POST['user'])) == 0)
			$status = array("error" => "Datos incompletos");
		else{
			try{
				$conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
				$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$sql = 'DELETE FROM usuario WHERE id = :user';
				$query = $conn->prepare($sql);
				$query->execute(array(':user' => $_POST['user']));
				$status = array("success" => "Usuario eliminado correctamente");				
			}catch(PDOException $e){
				$status = array("error" => "Ocurrió un error al intentar eliminar los datos del usuario");
			}
		}	
	}

	function read()
	{
		global $data_array, $status;
		try{
			$conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
			$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$sql = 'SELECT id, nombre, ap_paterno, ap_materno, email FROM usuario';
			$query = $conn->prepare($sql);
			$query->execute();
			$result = $query->fetchAll();
			foreach($result as $row){
				$row_array['id'] = $row['id'];
				$row_array['nombre'] = $row['nombre'];
				$row_array['ap_paterno'] = $row['ap_paterno'];
				$row_array['ap_materno'] = $row['ap_materno'];
				$row_array['email'] = $row['email'];
				array_push($data_array, $row_array);
			}
		}catch(PDOException $e){
			$status = array("error" =>  'Error al obtener los datos');
		}
	}

	if(!isset($_POST['method'])){
		$status = array("error" =>  'Opción inválida');
	}else{
		$data_array = array();
		$status = array();
		switch ($_POST['method']) {
			case 'create':
				create();
				break;
			case 'delete':
				delete();
				break;
			default:
				break;
		}
		read();
	}
	echo json_encode(array('response' => $data_array, 'status' => $status));
 ?>