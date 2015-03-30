<!DOCTYPE html>
<html>
	<head>
		<title><?php echo $titulo;?></title>
		<!-- definiciones comunes a todo el sitio -->
		<link type="text/css" href="/estilos/principal.css" rel="stylesheet"/>
		
		<?php //echo $defHead; ?>
	</head>
	<body>
		<div id="todo">
			<div id="cabecera">
				<a href="<?php echo Sistema::app()->generaURL(
									array());?>">
					<img src="/imagenes/logo.jpg" width="50px" height="50px">
				</a>APLICACION USANDO EL FRAMEWORK
			</div>
						
			<div id="conte">
				
				<?php echo $contenido; 
				
				   
				?>
			</div>
			<div id="pie"> &euro; Ies pedro espinosa
			</div>
		</div>
	</body>
</html>
