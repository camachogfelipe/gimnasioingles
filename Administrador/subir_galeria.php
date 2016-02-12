<?php
session_start(); error_reporting(0);
if(!isset($_REQUEST['noscript'])) :
?>
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
endif;
defined( '_GI' ) or define( '_GI', 1 );
require("funciones_globales.php");

if(is_numeric($_REQUEST['op'])) $opcion = $_REQUEST['op'];
else $opcion = 1;

if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
else $id = NULL;

$operacion = new subir_galeria($id);

switch($opcion) {
	case 1 : $operacion->formulario();
			 break;
	case 2 : $operacion->subir();
			 break;
}

class subir_galeria{
	var $id;
	var $consulta;
	var $directorio;
	var $usuario;
	var $nom_usuario;
	var $archivos;
	var $resultados;
	
	function __construct($id) {
		if(!empty($id)) $this->id = $id;
		else exit("No se puede subir un archivo");
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		$this->consulta = new BDManejo();
		$this->consulta->tabla("galerias");
		$this->definir_directorio();
	}
	
	function definir_directorio() {
		$this->consulta->datos("gal_titulo, gal_archivos");
		$this->consulta->opciones("WHERE gal_id = '$this->id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo(); print_r($this->directorio);
		$this->directorio = dirname(__FILE__);
		define('DS', DIRECTORY_SEPARATOR);
		$this->directorio = explode(DS, $this->directorio);
		unset($this->directorio[count($this->directorio)-1]);
		$this->directorio = implode(DS, $this->directorio);
		$this->directorio .= DS.'galerias';
		$find    = array( "á", "é", "í", "ó", "ú"," ", "ñ" );
		$replace = array( "a", "e", "i", "o", "u","_", "n" );
		$this->directorio .= DS.str_ireplace($find, $replace, strtolower($this->resultados[0]['gal_titulo'])).DS;
		if (is_dir($this->directorio)) $this->archivos = $this->resultados[0]['gal_archivos'];
		else {
			exec("mkdir $this->directorio");
			$this->archivos = NULL;
		}
		
	}
	
	function formulario() {
		define('COGPATH_BASE', dirname(__FILE__));
		$parts = explode(DS, COGPATH_BASE);
		define('COGPATH_ROOT', implode(DS, $parts));
		unset($parts[count($parts)-1]);
		define("COGPATH_BROOT", implode(DS, $parts));
		define('COGPATH_BURIROOT', "http://" . $_SERVER['HTTP_HOST']);
		define('COGPATH_CSS', COGPATH_BURIROOT."/");
		define('COGPATH_CSS2', COGPATH_BURIROOT."/css/");
		define('COGPATH_JS', COGPATH_BURIROOT."/scripts/");
		require_once("upload.form.php");
	}
	
	private function normaliza ($cadena){
		$originales = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
		$modificadas = 'aaaaaaaceeeeiiiidnoooooouuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyRr';
		$cadena = utf8_decode($cadena);
		$cadena = strtr($cadena, utf8_decode($originales), $modificadas);
		$cadena = strtolower($cadena);
		return utf8_encode($cadena);
	}
	
	function subir() {
		echo '<div id="subir_archivo">';
		// Script Que copia el archivo temporal subido al servidor en un directorio.
		// Intentamos Subir Archivo
		// (1) Comprobamos que existe el nombre temporal del archivo
		if(isset($_FILES)) :
			$tf = count($_FILES['myfile']['name']);
			for($x = 0; $x < $tf; ++$x) :
				$_FILES['myfile']['name'][$x] = $this->normaliza($_FILES['myfile']['name'][$x]);
				if (!copy($_FILES['myfile']['tmp_name'][$x], $this->directorio.$_FILES['myfile']['name'][$x])) :
					echo '<script> alert("Error al Subir el Archivo: '.$_FILES['myfile']['name'][$x].'\n");</script>';
				else :
					$tipo = $_FILES['myfile']['type'][$x];
					echo "<p>Tipo: ".$tipo;
					echo "<br />Nombre del archivo: ".$archivo = $_FILES['myfile']['name'][$x];
					$size = $_FILES['myfile']['size'][$x];
					$sizemb = number_format($size / 1048576, 2);
					echo "<br />Tama&ntilde;o del archivo: ".$sizemb." Megabytes";
					echo "</p>";
					if(!empty($this->archivos)) $this->archivos .= ";".$archivo;
					else $this->archivos = $archivo;
					$this->consulta->conecta();
					$this->consulta->datos("gal_archivos = '".$this->archivos."', gal_fecha_modificada = '".date("Y-m-d H:i:s")."', usr_id_ultimo_modifico = '".$this->usuario."'");
					$this->consulta->opciones("WHERE gal_id = '".$this->id."'");
					$this->consulta->actualiza();
					$this->consulta->ejecutar_query();
					$this->consulta->desconecta();
					$mensaje = new mensajes_globales("Se ha subido el archivo con exito", 2);
					$mensaje->info();
				endif;
			endfor;
		else :
			$mensaje = new mensajes_globales("El Archivo no ha llegado al Servidor.", 2);
			$mensaje->info();
		endif;
		echo '</div>';
		$this->formulario();
	}
}
?>