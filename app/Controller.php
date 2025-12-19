<?php
/*  Fichero app/Controller.php
 *  Esta clase contendrá los métodos o acciones indicados en el mapa del archivo 'index.php'
 *  Cada método asigna a la variable 'params' los datos necesarios en la plantilla
 *  que se incluye a continuación. Será una plantilla de la carpeta 'templates', correspondiente
 *  a la acción que se debe realizar.
 */
class Controller
{
	public function inicio() {
		$params = array(
			'mensaje' => 'Bienvenido a la aplicación de alimentos',
			'fecha' => date('d-m-y')
		);
		require __DIR__ . '/templates/inicio.php';
	}
	
	
	public function listar() {
		$m = new Model(Config::$mvc_bd_nombre,
					   Config::$mvc_bd_usuario,
					   Config::$mvc_bd_clave,
					   Config::$mvc_bd_hostname);
		// A la clave 'alimentos' se le asigna el valor resultante de realizar la consulta
		// 'buscarAlimentos' del modelo. El resultado de la consulta se pasará a su vez
		// a la plantilla 'mostrarAlimentos'
		$params = array('alimentos' => $m->buscarAlimentos());
		require __DIR__ . '/templates/mostrarAlimentos.php';
	}

	
	public function insertar() {
		$params = array(
			'nombre' => '',
			'energia' => '',
			'proteina' => '',
			'hc' => '',
			'fibra' => '',
			'grasa' => ''
		);
		$m = new Model(Config::$mvc_bd_nombre,
					   Config::$mvc_bd_usuario,
					   Config::$mvc_bd_clave,
					   Config::$mvc_bd_hostname);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			// Se comprueban los campos del formulario, si son válidos se inserta el alimento y se redirige a 'listar'
			if ($m->validarDatos($_POST['nombre'], $_POST['energia'],$_POST['proteina'], $_POST['hc'], $_POST['fibra'], $_POST['grasa'])) {
				$m->insertarAlimento($_POST['nombre'], $_POST['energia'], $_POST['proteina'], $_POST['hc'], $_POST['fibra'], $_POST['grasa']);
				header('Location: index.php?ctl=listar');
			}
			else {
				// Si los campos del formulario no son válidos, se muestra el mensaje de error
				$params = array(
					'nombre' => $_POST['nombre'],
					'energia' => $_POST['energia'],
					'proteina' => $_POST['proteina'],
					'hc' => $_POST['hc'],
					'fibra' => $_POST['fibra'],
					'grasa' => $_POST['grasa']
				);
				$params['mensaje'] = 'No se ha podido insertar el alimento. Revisa el formulario';
			}
		}
		require __DIR__ . '/templates/formInsertar.php';
	}
	
	
	public function buscarPorNombre() {
		$params = array(
			'nombre' => '',
			'resultado' => array()
		);
		$m = new Model(Config::$mvc_bd_nombre,
					   Config::$mvc_bd_usuario,
					   Config::$mvc_bd_clave,
					   Config::$mvc_bd_hostname);
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$params['nombre'] = $_POST['nombre'];
			$params['resultado'] = $m->buscarAlimentosPorNombre($_POST['nombre']);
		}
		require __DIR__ . '/templates/buscarPorNombre.php';
	}
	
	
	public function ver() {
		if (!isset($_GET['id'])) {
			throw new Exception('Pagina no encontrada');
		}
		$id = $_GET['id'];
		$m = new Model(Config::$mvc_bd_nombre,
					   Config::$mvc_bd_usuario,
					   Config::$mvc_bd_clave,
					   Config::$mvc_bd_hostname);
		$params = $m->buscarAlimentoPorId($id);
		require __DIR__ . '/templates/verAlimento.php';
	}
}