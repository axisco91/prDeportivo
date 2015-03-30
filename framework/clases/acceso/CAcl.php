<?php



abstract class CACL
{
	
	//Metodos
	abstract public function anadirRole($nombre,$puedeAcceder,$puedeConfigurar);
	abstract public function getCodRole($nombre);
	abstract public function anadirUsuario($nombre,$nick,$contrasena,$codRole);
	abstract public function esValido($nick,$contrasena);
	abstract public function getPermisos($nick,&$puedeAcceder,&$puedeAdministrar);
	abstract public function getNombre($nick);
	abstract public function setNombre($nick,$nombre);
	abstract public function dameUsuarios();
	
}

