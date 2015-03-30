<?php

	//ruta al fichero de Sistema base
	$pedrosa=dirname(__FILE__).'/framework/Sistema.php';
	
	//ruta al fichero de configuración
	$configuracion=dirname(__FILE__).'/aplicacion/config/config.php';
	
	//incluye los ficheros de sistema y de configuracion
	require_once($pedrosa);
	require_once($configuracion);
	
	
	//crea la aplicación y la ejecuta
	Sistema::crearAplicacion($config)->ejecutar();
	
	