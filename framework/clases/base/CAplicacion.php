<?php
	class CAplicacion
	{
		private $_controlDefecto="inicial";
		private $_BD;
		private $_sesion;
		private $_acceso;
		private $_ACL;
		
		public function __construct($config)
		{
			//se carga la configuracion
			
			//controlador inicial
			if (isset($config["CONTROLADOR"]))
			    {
			      if (is_array($config["CONTROLADOR"]))	
			      		$this->_controlDefecto=$config["CONTROLADOR"][0];
				      else
						$this->_controlDefecto=$config["CONTROLADOR"];
				}
				      
			//rutas para incluir las clases
			if (isset($config["RUTAS_INCLUDE"]))
			{
				foreach ($config["RUTAS_INCLUDE"] as $ruta) 
				{
					if(substr($ruta, 0,1)!='/')
						$ruta=$_SERVER["DOCUMENT_ROOT"]."/".$ruta;
					
					Sistema::nuevaRuta($ruta);
				}
			}
			
			
			if (isset($config["BD"]) && $config["BD"]["hay"]===true)
			{ 
				$this->_BD=new CBaseDatos($config["BD"]["servidor"],
							  				$config["BD"]["usuario"],
							  				$config["BD"]["contra"],
							  				$config["BD"]["basedatos"]);
			}
			
			if (isset($config["SESION"]) && $config["SESION"]["controlAutomatico"])
			{
				$this->_sesion=new CSesion();
				$this->_sesion->crearSesion();
			}  
			
			if (isset($config["ACCESO"]) && $config["ACCESO"]["controlAutomatico"])
			{
				//garantizo que exista la instancia de CSesion	
				if (!is_object($this->_sesion))
				     $this->_sesion=new CSesion();
				
				//creo el objeto CAcceso
				$this->_acceso=new CAcceso($this->_sesion);
				
				if (is_object($this->_BD))
				     $this->_ACL=new CAclBD($this->_BD);
				
			}  
			
		}
		
		public function BD()
		{
			return $this->_BD;
		}
		
		public function Sesion()
		{
			return $this->_sesion;
		}
		
		public function Acceso()
		{
			return $this->_acceso;
		}
		
		public function ACL()
		{
			return $this->_ACL;
		}
		public function analizaURL()
		{
			$ac="";
			$co="";
			
			if (isset($_REQUEST["co"]))
					$co=trim($_REQUEST["co"]);
			
			if (isset($_REQUEST["ac"]))
					$ac=trim($_REQUEST["ac"]);
			
			unset($_REQUEST["co"]);
			unset($_REQUEST["ac"]);
			unset($_GET["co"]);
			unset($_GET["ac"]);
			unset($_POST["co"]);
			unset($_POST["ac"]);
			
			$res=array();
			
			if ($co!="")
			    {
			    	$res[]=$co;
					if ($ac!="")
					    {
					    	$res[]=$ac;
					    }
			    }
				
			if ($res!=array())
			    return $res;
			  else
				return null;	
		
		}
		
		
		public function generaURL($accion,$parametros=null)
		{
			$ruta="";
			
			if ($accion)
			{
				$ruta="co=".$accion[0];
				if (isset($accion[1]))
				   $ruta.="&ac=".$accion[1];
			}
			
			if ($parametros)
			{
				foreach ($parametros as $clave => $valor) 
				{
				   if ($ruta!="")
				   	$ruta.="&";	
				   $ruta.=$clave."=".$valor;	
				}
			}
			$final="/index.php";
			if ($ruta!="")
			   {
			   	$final.="?".$ruta;
			   }
			   
			return $final;
		}
		
		public function ejecutaControlador($accion)
		{
			$contro=$this->_controlDefecto;
			if ($accion)
			{
				$contro=$accion[0];
			}
			$contro=$contro."Controlador";
			
			//compruebo si existe el controlador
			$ruta=$_SERVER["DOCUMENT_ROOT"]."/aplicacion/controladores/".$contro.".php";
			if (!file_exists($ruta))
					{
						$this->paginaError(404,"Pagina no encontrada");
						exit;
					}
			
			if (!class_exists($contro,false))
			      include($ruta);
				
			//creo una instancia para el controlador
			$contro=new $contro();
			
			$acc=$contro->accionDefecto;
			if (isset($accion[1]))
			 	$acc=$accion[1];
			
			if (!$contro->ejecutar($acc))
					{
						$this->paginaError(404,"Pagina no encontrada");
						exit;
					}
		}
		
		
		public function irAPagina($accion,$parametros=null)
		{
			$url=$this->generaURL($accion,$parametros);
			header("location: ".$url);
		}
		
		public function ejecutar()
		{
			$accion=$this->analizaURL();
			if (!$accion)
			{
				$accion=array($this->_controlDefecto);
			}
			
			$this->ejecutaControlador($accion);
		}
		
		
		public function paginaError($numError,$mensaje=null)
		{
			$errores=array(404=>"Pagina no encontrada",
							400=>"Solicitud incorrecta");
							
							
			$numError=intval($numError);
			
			if ($mensaje==null)
			   {
			   	 if (isset($errores[$numError]))
				      $mensaje=$errores[$numError];
				     else
				 	  $mensaje="Se ha producido un error."; 
			   }
			
			$ruta=$_SERVER["DOCUMENT_ROOT"]."/aplicacion/vistas/plantillas/error.php";
			
			if (file_exists($ruta))
			    include($ruta);
			   else 
			    {
			    	echo "Error $numError: $mensaje";				
				}
			exit;
		}
	}
