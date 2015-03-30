<?php

	class CSesion{
		
		public function __construct()
		{
			
		}
		
		public function haySesion()
		{
			return (isset($_SESSION));	
		}
		
		public function crearSesion()
		{
			if (!$this->haySesion())
			    session_start();
		}
		
		
		public function destruirSesion()
		{
			if ($this->haySesion())
				session_destroy();
		}
		
		public function get($nombre)
		{
			if ($this->existe($nombre))
			 	return $_SESSION[$nombre];
			
			return false;
		}
		
		public function set($nombre,$valor)
		{
			if ($this->haySesion())
			{
				$_SESSION[$nombre]=$valor;
				return true;
			}
			
			return false;
		}
		
		public function existe($nombre)
		{
			return ($this->haySesion() &&
			        isset($_SESSION[$nombre]));
		}
		
		public function borrar($nombre)
		{
			if ($this->existe($nombre))
				unset($_SESSION[$nombre]);
		}
		
	}
