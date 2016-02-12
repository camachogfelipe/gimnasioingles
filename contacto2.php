<?php
defined("_GI") or define( '_GI',1 );
require_once("Administrador/funciones_globales.php");
$consulta = new BDManejo(1);
$consulta->conecta();
$consulta->tabla("configuracion");
$consulta->datos("configuracion_valor");
$consulta->opciones("WHERE configuracion_nombre = 'correo_contacto'");
$consulta->leer_datos();
$resultados = $consulta->array_asociativo();
$consulta->libera();
$consulta->desconecta();
//print_r($resultados);

$nombre = $_POST['nombre_completo'];
$motivo = $_POST['motivo'];
$mail = $_POST['mail'];
$asunto = $_POST['asunto'];

$cuerpo = "
<html> 
<head> 
<title>Correo de contacto</title> 
</head> 
<body> 
<p>$asunto</p>
Atentamente,
<p>$nombre<br />$mail</p>
</body> 
</html>";

$headers = "X-Mailer:PHP/".phpversion()."\n";
$headers .= "Mime-Version: 1.0\n";
$headers .= "Content-Type: text/html; charset=iso-8859-1\n"; 

//dirección del remitente 
$headers .= "From: $nombre <$mail>\n";
if(!mail($resultados[0]['configuracion_valor'], $asunto, $cuerpo, $headers))
	$mensaje = new mensajes_globales("No se pudo enviar el formulario por favor intentelo más tarde", 1);
else $mensaje = new mensajes_globales('Su mensaje ha sido enviado satisfactoriamente.', 1);
$mensaje->info();

echo $cuerpo;

?>