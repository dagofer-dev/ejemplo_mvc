<?php
/*  Fichero web/index.php
 *  Este archivo es el "Front Controller", recibe la acción a realizar
 *  y realiza una llamada al controlador con la acción recibida
 */

// carga del modelo y los controladores
require_once __DIR__ . '/../app/Config.php';
require_once __DIR__ . '/../app/Model.php';
require_once __DIR__ . '/../app/Controller.php';

/*  'map' es un array asociativo cuya función es definir una tabla para
 *  mapear o asociar rutas en acciones de un controlador.
 *  Esta tabla será utilizada para saber qué acción se debe ejecutar 
 */
$map = array(
	'inicio' => array('controller' =>'Controller', 'action' =>'inicio'),
	'listar' => array('controller' =>'Controller', 'action' =>'listar'),
	'insertar' => array('controller' =>'Controller', 'action' =>'insertar'),
	'buscar'=>array('controller' =>'Controller', 'action' =>'buscarPorNombre'),
	'ver' => array('controller' =>'Controller', 'action' =>'ver')
);

// Se comprueba si se está recibiendo el parámetro ctl por GET
// Este parámetro contendrá la acción a realizar
if (isset($_GET['ctl'])) {
	if (isset($map[$_GET['ctl']])) {
		$ruta = $_GET['ctl'];
	}
	else {
		header('Status: 404 Not Found');
		echo '<html><body>Error 404: No existe la ruta '. $_GET['ctl'] .'.</body></html>';
		exit;
	}
}
else {
	// Si no se recibe nada por GET, se accede a la acción por defecto ('inicio')
	$ruta = 'inicio';
}

// En base a la ruta o acción recibida extraemos del mapa el array correspondiente
$controlador = $map[$ruta];

// Ejecucion del controlador asociado a la ruta
// Si existe el método indicado en 'action' en la clase indicada en 'controller'
// Se hará una llamada a dicho método
if (method_exists($controlador['controller'], $controlador['action'])) {
	call_user_func(array(new $controlador['controller'], $controlador['action']));
}
else {
	// Si no existe la acción en la clase, se mostrará un mensaje de error
	header('Status: 404 Not Found');
	echo '<html><body>Error 404: El controlador '. $controlador['controller'].'->'.$controlador['action'] .'no existe.</body></html>';
}