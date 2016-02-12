<?php
error_reporting(0);
require_once("clases/galerias.class.php");
$cargar = new galerias();

if(isset($_GET['i'])) {
	$op = $_GET['i'];
	if(!is_numeric($op)) $op = 1;
}
?>
<script>
$(document).ready(function()  
{
	redondear();
});
</script>
<div id="div_ins_izq_sup">
	<h1>
    	<img src="imagenes/lapiz.png" width="94" height="54" align="absmiddle" />
        Galerías de fotos
	</h1>
    <div id="menu_izq">
    	<?php $datos = $cargar->menu($op); ?>
    </div>
    <div id="contenido3">
    	<?php
			if($datos != false) :
        		$contenido = $cargar->contenido($op);
		?>
    		<h4><?php echo $contenido[0]['gal_titulo'] ?></h4>
        	<?php echo $contenido[0]['gal_descripcion'] ?>
        	<h6>Fecha de creaci&oacute;n: <?php echo $contenido[0]['gal_fecha_creada'] ?></h6>
        	<h6>Fecha de la &uacute;ltima actualizaci&oacute;n: <?php $f = explode(" ",$contenido[0]['gal_fecha_modificada']); echo $f[0] ?></h6>
        	<?php
				$fotos = explode(";",$contenido[0]['gal_archivos']);
				$find    = array( "á", "é", "í", "ó", "ú"," ", "ñ" );
				$replace = array( "a", "e", "i", "o", "u","_", "n" );
				$directorio .= str_ireplace($find, $replace, strtolower($contenido[0]['gal_titulo']));
				foreach($fotos as $valor) {
					echo '<a href="galerias/'.$directorio.'/'.$valor.'" class="lytebox" data-lyte-options="group:'.$contenido[0]['gal_titulo'].'" data-title="'.$contenido[0]['gal_titulo'].'"><img src="galerias/'.$directorio.'/'.$valor.'" width="94" height="64" align="absmiddle" /></a>'."\n";
				}
			else : echo "En actualización";
			endif;
		?>
    </div>
</div>
<div id="div_index_der_sup">
	<div id="BannerNoticias"><?php
		require_once("clases/noticias.class.php");
		$noticias = new noticias(); $noticias->muestra_contenido();
    ?></div>
</div>