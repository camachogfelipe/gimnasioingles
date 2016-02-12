<?php session_start(); error_reporting(0); ?>
<link href="usuarios.css" rel="stylesheet" type="text/css" />
<link href="gik.css" rel="stylesheet" type="text/css" />
<script language="javascript" src="../Scripts/jquery-1.7.min.js" type="text/javascript"></script>
<script type="text/javascript" language="javascript" src="../Scripts/jquery.corner.js"></script>
<script language="javascript" src="../Scripts/gik.js"></script>
<script type="text/javascript" src="../Scripts/jquery.validate.js"></script>
<script type="text/javascript" src="../Scripts/jquery.validate.additional-methods.js"></script>
<script type="text/javascript" src="upload_files/LoadVars.js"><!--// http://www.devpro.it/javascript_id_92.html //--></script>
<script type="text/javascript" src="upload_files/BytesUploaded.js"><!--// http://www.devpro.it/javascript_id_96.html //--></script>
<script type="text/javascript">
	$(document).ready(function() { redondear(); });
	var bUploaded = new BytesUploaded('upload_files/whileuploading.php');
</script>
<?php
defined( '_GI' ) or define( '_GI', 1 );
require("funciones_globales.php");

if(isset($_REQUEST['t'])) $tabla = $_REQUEST['t'];
else $tabla = 1;

if(is_numeric($_REQUEST['op'])) $opcion = $_REQUEST['op'];
else $opcion = 1;

if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
else $id = NULL;

$operacion = new subir_archivo($id, $tabla);

switch($opcion) {
	case 1 : $operacion->formulario();
			 break;
	case 2 : $operacion->subir();
			 break;
}

class subir_archivo{
	var $id;
	var $consulta;
	var $directorio;
	var $usuario;
	var $nom_usuario;
	var $t;
	
	function __construct($id, $tabla) {
		if(!empty($id)) $this->id = $id;
		else exit("No se puede subir un archivo");
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		$this->consulta = new BDManejo();
		$this->t = $tabla;
		$this->definir_tabla();
	}
	
	function definir_tabla() {
		switch($this->t) {
			case 1 : $this->consulta->tabla("institucional");
					 $this->directorio = "Documentos";
					 break;
			case 2 : $this->consulta->tabla("alianzas");
					 $this->directorio = "logos_alianzas";
					 break;
		}
	}
	
	function formulario() {
		echo '<form action="subir_archivo.php?t='.$this->t.'&amp;op=2&amp;s=1&amp;id='.$this->id.'" method="post" enctype="multipart/form-data" name="subir_archivo" onSubmit="bUploaded.start(\'fileprogress\');" id="subir_archivo">';
		echo '<label id="archivo" for="archivo">Archivo: </label><input name="archivo" id="archivo" type="file" size="60" /><br />';
		echo '<input id="button" class="submit" name="submit" type="submit" value="Subir archivo">';
		echo '</form>';
		echo '<div id="fileprogress"></div>';
		echo '<pre>'; include("upload_files/test2.php"); echo '</pre>';
	}
	
	function subir() {
		echo '<div id="subir_archivo">';
		// Script Que copia el archivo temporal subido al servidor en un directorio.
		$tipo = $_FILES['archivo']['type'];
		echo "<p>Tipo: ".$tipo;
		echo "<br />Nombre del archivo: ".$archivo = $_FILES['archivo']['name'];
		$size = $_FILES['archivo']['size'];
		$sizemb = number_format($size / 1048576, 2);
		echo "<br />Tama&ntilde;o del archivo: ".$sizemb." Megabytes";
		echo "</p>";
		// Definimos Directorio donde se guarda el archivo
		$dir = '../'.$this->directorio.'/';
		// Intentamos Subir Archivo
		// (1) Comprobamos que existe el nombre temporal del archivo
		if (isset($_FILES['archivo']['tmp_name']))
		{
			$tipo = $this->verifica_archivo($tipo, $size);
			// (2) - Comprovamos que se trata de un archivo de imágen
			if ($tipo == true)
			{
				// (3) Por ultimo se intenta copiar el archivo al servidor.
				if (!copy($_FILES['archivo']['tmp_name'], $dir.$archivo))
					echo '<script> alert("Error al Subir el Archivo");</script>';
				else
				{
					$this->consulta->conecta();
					if($this->t == 1) {
						$this->consulta->datos("inst_archivo_pdf = '".$archivo."', inst_fecha_modificado = '".date("Y-m-d H:i:s")."', usr_id = '".$this->usuario."'");
						$this->consulta->opciones("WHERE inst_id = '".$this->id."'");
					}
					elseif($this->t == 2) {
						$this->consulta->datos("ali_logo = '".$archivo."'");
						$this->consulta->opciones("WHERE ali_id = '".$this->id."'");
					}
					$this->consulta->actualiza();
					$this->consulta->ejecutar_query();
					$this->consulta->desconecta();
					$mensaje = new mensajes_globales("Se ha subido el archivo con exito", 2);
					$mensaje->info();
					/*echo '<script>top.document.getElementById("iframeupload").style.display = none;</script>';*/
				}
			}
			else {
				$mensaje = new mensajes_globales("El Archivo que se intenta subir NO ES del tipo permitido o su tama&ntilde;o excede los 3.5 Megabytes.", 2);
				$mensaje->info();
				$this->formulario();
			}
		}
		else {
			$mensaje = new mensajes_globales("El Archivo no ha llegado al Servidor.", 2);
			$mensaje->info();
			$this->formulario();
		}
		echo '</div>';
	}
	
	function verifica_archivo($tipo, $size) {
		if($this->t == 1) {
			if ($tipo == 'application/x-pdf' || $tipo == 'application/pdf') {
				if($size <= 20971520) return true;
				else return false;
			}
			else return false;
		}
		elseif($this->t == 2) {
			$tipo = substr($tipo, 0, 5);
			if ($tipo == 'image' and $size <= 20971520) return true;
			else return false;
		}
	}
}
?>