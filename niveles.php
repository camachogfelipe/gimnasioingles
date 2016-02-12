<?php
require_once("clases/niveles.class.php");
$cargar = new niveles();

if(isset($_GET['i'])) {
	$op = $_GET['i'];
	if(!is_numeric($op)) $op = 1;
}
else $op = 1;
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
        Niveles educativos
	</h1>
    <div id="menu_izq">
    	<?php $cargar->menu($op); ?>
    </div>
    <div id="contenido3">
    	<?php $contenido = $cargar->contenido($op); ?>
    	<h4><?php echo $contenido[0]['niv_nombre'] ?></h4>
        <?php echo $contenido[0]['niv_descripcion'] ?>
        <strong>Rango de edad:</strong> <?php echo $contenido[0]['niv_rango_edad'] ?> años
        <p><strong>Nivel equivalente en otros planteles:</strong> <?php echo $contenido[0]['niv_equivalente'] ?></p>
        <h6>Fecha de la &uacute;ltima actualizaci&oacute;n: <?php echo $contenido[0]['niv_fecha_actualizado'] ?></h6>
    </div>
</div>
<div id="div_index_der_sup">
	<div id="BannerNoticias"><?php
		require_once("clases/noticias.class.php");
		$noticias = new noticias(); $noticias->muestra_contenido();
    ?></div>
</div>