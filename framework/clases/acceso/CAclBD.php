<?php



class CAclBD extends CAcl
{
	private $enlaceBD;
	private $hayEnlace;
	
	//Constructor
	function __construct($conexion)
	{
		$this->hayEnlace=false;
		
		if (is_object($conexion))
		{
			$this->hayEnlace=true;
			$this->enlaceBD=$conexion;
		}		
		
	}
	
	//Metodos
	private function existeRole($nombre)
	{
		if (!$this->hayEnlace)
		    return false;
		$nombre=str_replace("'", "''", strtoupper($nombre));
		$nombre=substr($nombre, 0,30);
		
		$sentencia="SELECT codrol ". 
					"	FROM roles ".
    				"	where nombre='$nombre'";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		$fila=$consulta->fila();
		if (!$fila)
		    return false;
		
		return ($fila["codrol"]);
	}
	
	public function existeCodRole($codrole)
	{
		if (!$this->hayEnlace)
		    return false;
		$codrole=intval($codrole);
		
		$sentencia="SELECT codrol ". 
					"	FROM roles ".
    				"	where codrol=$codrole";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		$fila=$consulta->fila();
		if (!$fila)
		    return false;
		
		return ($fila["codrol"]);
	}
	
	
	function anadirRole($nombre,$puedeAcceder,$puedeConfigurar){
		$puedeAcceder= $puedeAcceder?'1':'0';
		$puedeConfigurar=$puedeConfigurar?'1':'0';
					
		if (!$this->hayEnlace)
		   return false;
					
		if ($this->existeRole($nombre))
		   return false;
		
		//todo va bien, inserto el role
		$nombre=str_replace("'", "''", strtoupper($nombre));
		$nombre=substr($nombre, 0,30);
		
		$sentencia="INSERT INTO roles ( ".
        			"		 NOMBRE, PUEDEACCEDER,PUEDECONFIGURAR ".
    				"				)VALUES ( ".
        			"		 '$nombre', $puedeAcceder,$puedeConfigurar ".
        			"				)";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;			
		    
		return true;
	}
	
	function getCodRole($nombre){
		if (!$this->hayEnlace)
		   return false;
					
		if ($codRole=$this->existeRole($nombre)===false)
		   return false;
			
		return $codRole;
	}
	
	function setCodRole($nick, $codRole){
		if (!$this->hayEnlace)
		    return false;
		
		if (!$this->existeUsuario($nick))
		  	return false;
		
		$nick=$this->convertirNick($nick);
		$codRole=intval($codRole);		
		
		$sentencia="update usuarios set ".
     				"		codrol=$codRole".
					"  where nick='$nick'";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		return true;
	}
	
	function anadirUsuario($nombre_apellidos, $dni, $email, $tlf, $fecha_nac, $nick, $contrasena, $codRole){
		if (!$this->hayEnlace)
		    return false;
		
		if ($this->existeUsuario($nick))
		  	return false;
		
		$nick=$this->convertirNick($nick);
		$nombre_apellidos=str_replace("'", "''", $nombre_apellidos);
		$nombre_apellidos=substr($nombre_apellidos, 0,30);
		$fecha_nac=CGeneral::fechaNormalAMysql($fecha_nac);
		$contrasena=str_replace("'", "''", $contrasena);
		$contrasena=substr($contrasena, 0,30);
		$codRole=intval($codRole);
		if (!$this->existeCodRole($codRole))
		    return false;
		
		$sentencia="insert into usuarios (".
					"       nombre_apellidos, dni, email, tlf, fecha_nac, nick, contrasenia, codrol".
					"			) values ( ".
					"       '$nombre_apellidos', '$dni', '$email', '$tlf', '$fecha_nac', '$nick', md5('$contrasena'), $codRole".
					"			)";
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->errno()!=0)
		    return false;
	
		
		return true;						
		
	}

	private function convertirNick($nick)
	{
		$nick=str_replace("'", "''", strtoupper($nick));
		return $nick;
	}
	public function existeUsuario($nick)
	{
		if (!$this->hayEnlace)
		    return false;
		$nick=$this->convertirNick($nick);
		
		$sentencia="SELECT nick ". 
					"	FROM usuarios ".
    				"	where nick='$nick'";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		$fila=$consulta->fila();
		if ($fila)
		    return true;
		else 
			return false;
				
	}
	
	function esValido($nick,$contrasena){
		if (!$this->hayEnlace)
		    return false;
		
		if (!$this->existeUsuario($nick))
		  	return false;
		
		$nick=$this->convertirNick($nick);
		$contrasena=str_replace("'", "''", $contrasena);
		$contrasena=substr($contrasena, 0,30);
		 
		$sentencia="SELECT nick ". 
					"	FROM usuarios ".
    				"	where nick='$nick' and ".
					"			md5('$contrasena')=contrasenia";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		$fila=$consulta->fila();
		if ($fila)
		    return true;
		else 
			return false;
		
	}
	
	function getPermisos($nick,&$puedeAcceder,&$puedeAdministrar){
		if (!$this->hayEnlace)
		    return false;
		
		if (!$this->existeUsuario($nick))
		  	return false;
		$nick=$this->convertirNick($nick);
		
		$sentencia="select r.puedeacceder, r.puedeconfigurar ".
     				"		from usuarios u ".
          			"			join roles r using (codrol) ".
     				"		where u.nick='$nick'";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		$fila=$consulta->fila();
		if (!$fila)
		    return false;		
		
		$puedeAdministrar=$fila["puedeconfigurar"];
		$puedeAcceder=$fila["puedeacceder"];
		
			
		return true;
	}
	
	function getNombre($nick){
		if (!$this->hayEnlace)
		    return false;
		
		if (!$this->existeUsuario($nick))
		  	return false;
		
		$nick=$this->convertirNick($nick);
		
		$sentencia="select * ".
     				"		from usuarios  ".
          			"		where nick='$nick'";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		$fila=$consulta->fila();
		if (!$fila)
		    return false;		
		
		return ($fila["nombre"]);
		
	}
	
	function setNombre($nick, $nombre_apellidos){
		if (!$this->hayEnlace)
		    return false;
		
		if (!$this->existeUsuario($nick))
		  	return false;
		
		$nick=$this->convertirNick($nick);
		$nombre=str_replace("'", "''", $nombre);
		$nombre=substr($nombre, 0,30);
		
		
		$sentencia="update usuarios set ".
     				"		nombre_apellidos='$nombre_apellidos'".
					" where nick='$nick'";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
	
		
		return true;
		
	}
	
	public function dameUsuarios()
	{
		
		$usu=array();
		$sentencia="SELECT us.nombre_apellidos, us.dni, us.email, us.tlf, us.fecha_nac, us.nick ".
					"		r.nombre as nombre_rol ".
					"	FROM usuarios us ".
         			"		join roles r using (codrol)".
					"	order by nombre_apellidos";
		
		$consulta=$this->enlaceBD->crearConsulta($sentencia);
		if ($consulta->error()!=0)
		    return false;
		
		while ($fila=$consulta->fila())
		{
			$usu[]=array("NOMBRE_APELLIDOS"=>$fila["nombre"],
						 "DNI"=>$fila["dni"],
						 "EMAIL"=>$fila["email"],
						 "TLF"=>$fila["tlf"],
						 "FECHA_NAC"=>$fila["fecha_nac"],
			             "NICK"=>$fila["nick"],
						 "ROLE"=>$fila["nombre_rol"]);
		}
		$consulta->free();
		
		return $usu;
		
		
	}
	
}

