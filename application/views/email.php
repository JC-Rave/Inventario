<!DOCTYPE html>
<html>
<head>
	<title>Inventario TecnoAcademia</title>
</head>
<body>
	<?php if ($pass===false):?>
		<div style="display: flex; justify-content: center; width: 90%;">
			<h1 style="text-align:center;color: #000000">Bienvenido instructor/a <?php echo $nombre.' '.$apellido ?> al inventario de TecnoAcademia Risaralda</h1>
		</div>
		<div style="width: 90%; background-color: rgba(0,0,0,.3);padding: 1.3rem; border-radius: 20px;margin: 5px;">
			<h2 style="margin-bottom: 40px;text-align: center;width: 100%;">Datos Básicos</h2>
			<hr style="width: 100%;">
			<div style="width: 100%;">
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Documento:</strong></span><span style="color: #000000"><?php echo $documento ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Nombre:</strong></span><span style="color: #000000"><?php echo $nombre ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Apellido:</strong></span><span style="color: #000000"><?php echo $apellido ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Teléfono:</strong></span><span style="color: #000000"><?php echo $telefono ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Correo:</strong></span><span style="color: #000000"><?php echo $correo ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Linea:</strong></span><span style="color: #000000"><?php echo $linea ?></span>
				</div>
			</div>
		</div>
		<br>
		<hr>
		<div style="display: flex; justify-content: center; width: 90%; background-color: rgba(0,0,0,.3);margin: 5px;padding: 1.3rem; border-radius: 20px;">
			<div style="width: 100%;text-align: center;">
				<h4>Contraseña: <?php echo $contrasena?></h5>
				<hr>
				<h4>Pagina: <a href="https://tecnoacademia.thebvl.com/">https://tecnoacademia.thebvl.com/</a></h4>
			</div>
		</div>
		<hr>	
	<?php endif; ?>
	<?php if ($pass===true):?>
		<div style="display: flex; justify-content: center; width: 90%;">
			<h1 style="text-align:center;color: #000000">Petición al cambio de contraseña para <?php echo $nombre.' '.$apellido ?></h1>
		</div>
		<div style="width: 90%; background-color: rgba(0,0,0,.3);padding: 1.3rem; border-radius: 20px;margin: 5px;">
			<h2 style="margin-bottom: 40px;text-align: center;width: 100%;">Datos Básicos</h2>
			<hr style="width: 100%;">
			<div style="width: 100%;">
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Documento:</strong></span><span style="color: #000000"><?php echo $documento ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Nombre:</strong></span><span style="color: #000000"><?php echo $nombre ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Apellido:</strong></span><span style="color: #000000"><?php echo $apellido ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Teléfono:</strong></span><span style="color: #000000"><?php echo $telefono ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Correo:</strong></span><span style="color: #000000"><?php echo $correo ?></span>
				</div>
				<div style="text-align: center; margin-bottom: 10px; width: 100%;">
					<span><strong style="color: #000000;margin-right: 15px;">Linea:</strong></span><span style="color: #000000"><?php echo $linea ?></span>
				</div>
			</div>
		</div>
		<br>
		<hr>
		<div style="display: flex; justify-content: center; width: 90%; background-color: rgba(0,0,0,.3);margin: 5px;padding: 1.3rem; border-radius: 20px;">
			<div style="width: 100%;text-align: center;">
				<h4>Cambiar Contraseña: <a href="<?php echo $url; ?>"><?php echo $url; ?></a></h4>
			</div>
		</div>
		<hr>	
	<?php endif; ?>
	
</body>
</html>