<?php


	class CAcceso 
	{
		private $_sesion;
		public function __construct($sesion)
		{
			$this->_sesion=$sesion;
			
			if (!$this->_sesion->haySesion())	
					$this->_sesion->crearSesion();
			
		}
		
		
		public function registrarUsuario($nick, $nombre,
							$puedeAcceder, $puedeConfigurar)
		{
			if (!$this->_sesion->haySesion())	
				return false;
			
			$this->_sesion->set("Validado",true);
			$this->_sesion->set("Nick",$nick);
			$this->_sesion->set("Nombre",$nombre);
			$this->_sesion->set("PuedeAcceder",$puedeAcceder);
			$this->_sesion->set("PuedeConfigurar",$puedeConfigurar);
			return true;
		}
		
		public function quitarRegistroUsuario()
		{
			if (!$this->_sesion->haySesion())	
				return false;

			$this->_sesion->set("Validado",false);
			return true;
		}
		
		public function hayUsuario()
		{
			return ($this->_sesion->existe("Validado") &&
			        $this->_sesion->get("Validado"));
		}
		
		public function puedeAcceder()
		{
			if ($this->hayUsuario())
			    return ($this->_sesion->get("PuedeAcceder"));
			
			return false;
		}
		
		public function puedeConfigurar()
		{
			if ($this->hayUsuario())
			    return ($this->_sesion->get("PuedeConfigurar"));
			
			return false;
		}
		
		public function getNick()
		{
			if ($this->hayUsuario())
			    return ($this->_sesion->get("Nick"));
			
			return false;
		}
	
		public function getNombre()
		{
			if ($this->hayUsuario())
			    return ($this->_sesion->get("Nombre"));
			
			return false;
		}
	}
