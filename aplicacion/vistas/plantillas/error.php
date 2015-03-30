<?php
	header("HTTP/1.1 $numError $mensaje");
	header("Status: $numError $mensaje");
	
	?>
<!DOCTYPE html>
<html>
	<head>
		<title>ERROR</title>
		<!-- definiciones comunes a todo el sitio -->
		<link type="text/css" href="/estilos/principal.css" rel="stylesheet"/>
		
		<?php //echo $defHead; ?>
	</head>
	<body>
		<div id="todo">
		<div id="todo">
			<div id="cabecera">
				<a href="<?php echo Sistema::app()->generaURL(
									array());?>">
					<img src="/imagenes/logo.jpg" width="50px" height="50px">
				</a>APLICACION USANDO EL FRAMEWORK
			</div>
			<div id="barraLogin">
				<div style="float: right;">
					<?php
				if (Sistema::app()->Acceso()->hayUsuario())
				   { 
				    echo "Validado como ".Sistema::app()->Acceso()->getNombre();	
					echo " <a href='".Sistema::app()->generaURL(array("inicial","cerrarSesion"))."'>[cerrar]</a>";
				   }	
					?>
				</div>
				<div style="clear:both;"></div>
				
			</div>
			<div id="barraMenu">
				<ul>
					<li><a href="<?php echo Sistema::app()->generaURL(array());?>">Inicio</a></li>
					<?php
				if (!Sistema::app()->Acceso()->hayUsuario())
				   {
				     ?>
				     <li><a href="<?php echo Sistema::app()->generaURL(array("inicial","login"));?>">Login</a></li>
				     <?php
				   }
				    
				     	
				if (Sistema::app()->Acceso()->hayUsuario() &&
				    Sistema::app()->Acceso()->puedeConfigurar())
				   {
				   	?>
				   	<li><a href="<?php echo Sistema::app()->generaURL(array("articulos","index"));?>">Articulos</a></li>
				   	<li><a href="<?php echo Sistema::app()->generaURL(array("articulos","indexPaginas"));?>">Articulos (paginas)</a></li>
				   	<?php 
				   }	
					?>
			    </ul> 
				
			</div>
			<div id="conte">
				<br />
				Se ha producido el error  <?php echo $mensaje;?>
				<br />
				<br />
				<br />
			</div>
			<div id="pie"> &euro; Ies pedro espinosa
			</div>
		</div>
	</body>
</html>
	
	