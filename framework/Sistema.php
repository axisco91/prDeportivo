<?php
    defined("RUTA_FRAMEWORK") or define("RUTA_FRAMEWORK",dirname(__FILE__));

	class Sistema
	{
		static private $_clasesBase=array("CAplicacion"=>"/base/CAplicacion.php",
											"CGeneral"=>"/general/CGeneral.php",
											"CValidaciones"=>"/general/CValidaciones.php",
											"CControlador"=>"/mvc/CControlador.php",
											"CActiveRecord"=>"/mvc/CActiveRecord.php",
											"CHTML"=>"/forms/CHTML.php",
											"CBaseDatos"=>"/bd/CBaseDatos.php",
											"CCommand"=>"/bd/CCommand.php",
											"CSesion"=>"/base/CSesion.php",
											"CWidget"=>"/widget/CWidget.php",
											"CGrid"=>"/widget/CGrid.php",
											"CPager"=>"/widget/CPager.php",
											"CAcceso"=>"/acceso/CAcceso.php",
											"CAcl"=>"/acceso/CAcl.php",
											"CAclBD"=>"/acceso/CAclBD.php",
											"CCaja"=>"/widget/CCaja.php"
										);
		static private $_rutasInclude=array();
		static private $_app;
		
		
		static public function autoload($clase)
		{
			if (isset(self::$_clasesBase[$clase])) // existe una entrada en $_clasesBase
			     include(RUTA_FRAMEWORK."/clases".self::$_clasesBase[$clase]);
				else 
				{
					foreach(self::$_rutasInclude as $ruta)
					{
						$ruta.=$clase.".php";
						if (file_exists($ruta))   //existe el fichero en una de las rutas
						   {
						     include($ruta);
							 break;
						   }
					}	
				}
			
			//se comprueba si se ha cargado
			if (!class_exists($clase,false))
				throw new ErrorException("clase $clase no encontrada",0,1);
		}
		
		static public function nuevaRuta($ruta)
		{
			if (substr($ruta, -1)!='/')
				$ruta.="/";
			
			self::$_rutasInclude[]=$ruta;
			
		}
		
		
		static public function crearAplicacion($config)
		{
			if (!self::$_app)
			 	self::$_app=new CAplicacion($config);
			
			return self::$_app;
		}	
		
		static public function app()
		{
			return self::$_app;
		}
		
	}

	spl_autoload_register(array("Sistema","autoload"));
	
	