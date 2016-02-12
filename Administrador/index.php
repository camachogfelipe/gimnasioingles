<?php
session_start();
define( '_GI', 1 );
/*unset($_SESSION['usuario']);
unset($_SESSION['nombre']);*/

if(isset($_POST['usuario']) and isset($_POST['clave'])) {
	$user = $_POST['usuario'];
	$clave = md5($_POST['clave']);

	include("funciones_globales.php");

	$consulta = new BDManejo(1);
	$consulta->tabla("usuarios");
	$consulta->datos("usr_login, usr_clave, usr_id, usr_nombre, usr_apellido");
	$consulta->opciones("WHERE usr_login='".$user."' and usr_clave = '".$clave."'");
	$consulta->leer_datos();
	$resultados = $consulta->array_array(2);
	$consulta->libera();
	$consulta->desconecta();
	
	$usuario = strcmp($user, $resultados[0]['usr_login']);
	$password = strcmp($clave, $resultados[0]['usr_clave']);
	if ($usuario == 0 and $password == 0) {
		// Si están en la base de datos del registro de usuario
		$_SESSION['usr_id'] = $resultados[0]['usr_id'];
		$_SESSION['usuario'] = $user;
		$_SESSION['nombre'] = $resultados[0]['usr_nombre']." ".$resultados[0]['usr_apellido'];
	}
}
if(isset($_GET['salir'])) {
	unset($_SESSION['usr_id']);
	unset($_SESSION['usuario']);
	unset($_SESSION['nombre']);
	header("Location: ../");
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Gimnasio Ingles - Acceso adminisrativo</title>
<link href="usuarios.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" media="all" href="../scripts/jsdatepick-calendar/jsDatePick_ltr.min.css" />
<link rel="stylesheet" href="../scripts/lytebox/lytebox.css" type="text/css" media="screen" />
<link rel="shortcut icon" type="image/x-icon" href="../imagenes/favicon.ico">
<script language="javascript" src="../scripts/jquery-1.7.min.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="../scripts/jquery.corner.js"></script>
<script type="text/javascript" language="javascript" src="../scripts/lytebox/lytebox.js"></script>
<script language="javascript" src="../scripts/jquery.form.js"></script>
<script type="text/javascript" src="../scripts/jquery.validate.js"></script>
<script type="text/javascript" src="../scripts/jquery.validate.additional-methods.js"></script>
<script type="text/javascript" src="../scripts/jsdatepick-calendar/jsDatePick.min.1.3.js"></script>
<script language="javascript" src="../scripts/gik_admin.js"></script>
</head>

<body onload="javascript:setFocus()">
<div id="load"><img src="../imagenes/preload.gif" width="157" height="158" class="load" /></div>
<div id="cabezote"><img src="../imagenes/cabezote.png" width="1024" height="200" /></div>
<hr id="hrmenu" />
<div id="contenido">
<?php
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	define('_GIA', 1);
	include("index2.php");
}
else {
?>
	<div id="acceso">
		<div id="main">
			<div id="contenido_usuarios"><h1>Acceso a administradores</h1><hr />
<form action="" id="acceso" name="acceso" method="post">
<table width="70%" border="0" cellspacing="3" cellpadding="0" align="center">
  <tr>
    <td width="32%" align="right">Usuario</td>
    <td width="68%"><input type="text" name="usuario" id="usuario" tabindex="1" /></td>
  </tr>
  <tr>
    <td align="right">Contrase&ntilde;a</td>
    <td><input type="password" name="clave" id="clave" tabindex="2" /></td>
  </tr>
  <tr>
    <td colspan="2">
		<button id="button" class="submit" type="submit" onclick="acceso.submit()">Ingresar</button>
    </td>
  </tr>
</table>
</form>
			</div>
		</div>
	</div>
<?php } ?>
</div>
<div id="pie">
	<p id="texto_pie">Copyright &copy; Gimnasio Ingles Kindergarden 2011. Todos los derechos reservados. Programación Por <a href="http://www.cogroupsas.com">Felipe Camacho</a>, Dise&ntilde;o por Tonica Films, Adpataci&oacute;n para web VideoExpress.org</p>
</div>    
</body>
</html>