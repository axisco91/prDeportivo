<?php

	class CControlador
	{
		public $accionDefecto="index";
		public $plantilla="main";
		
		public function __construct()
		{
			;
		}
		
		public function ejecutar($accion)
		{
			$accion=strtolower($accion);
			//comprobar si se permite ejecutar la accion
			
			
			//busco el método para la acción	
			$nombreFuncion='accion'.strtoupper(substr($accion,0,1)).
									substr($accion,1);
			
			
			if (!method_exists($this, $nombreFuncion))
			    return false;
			
			//ejecuto la accion
			$this->$nombreFuncion();
			
			return true;
		}
		
		public function dibujaVistaParcial($vista, $variables=array(),$devolver=false)
		{
			//existe la vista
			$ruta=get_class($this);
			$ruta=str_replace('Controlador', '', $ruta);
			$ruta=$_SERVER["DOCUMENT_ROOT"].'/aplicacion/vistas/'.$ruta.'/'.$vista.'.php';
			if (!file_exists($ruta))
			    return false;
			
			//definir las variables
			foreach($variables as $_var=>$_valor)
			{
				$$_var=$_valor;
			}
			//iniciar captura de salida
			ob_start();
			
			//incluir el fichero de la vista
			include($ruta);
			//finalizar captura de salida
			$salida=ob_get_contents();
			
			ob_end_clean();
			//operar segun $devolver
			
			if ($devolver)
			    return $salida;
			   else
			   	{
			   		echo $salida;
			   		return true;
				}
		}
		
		public function dibujaVista($vista, $variables=array(),$titulo="aplicacion")
		{
			//comprobamos si existe la plantilla
			$ruta=$_SERVER["DOCUMENT_ROOT"].'/aplicacion/vistas/plantillas/'.
						$this->plantilla.'.php';
						
		    if (!file_exists($ruta))
			     return false;
			
			//cargo la vista parcial
			$contenido=$this->dibujaVistaParcial($vista, $variables,true);
			
			//incluyo la plantilla
			include($ruta);
			
		}
		
		
		
		
	}
