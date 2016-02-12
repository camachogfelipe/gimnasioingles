<?php
require_once("clases/institucional.class.php");
$cargar = new institucional();

if(isset($_GET['i'])) {
	$op = $_GET['i'];
	if(!is_numeric($op)) $op = 1;
}
else $op = NULL;
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
        Informacion institucional
	</h1>
    <div id="menu_izq">
    	<?php $op = $cargar->menu($op); ?>
    </div>
    <div id="contenido3">
    	<?php $contenido = $cargar->contenido_institucional($op); ?>
    	<h4><?php $i = count($contenido) - 1; echo $contenido[$i]['inst_titulo'] ?></h4>
        <?php echo $contenido[$i]['inst_descripcion'] ?>
        <h6><?php
			if($contenido[$i]['inst_fecha_modificado'] != "0000-00-00") echo "Fecha de la &uacute;ltima actualizaci&oacute;n: ".$contenido[$i]['inst_fecha_modificado'];
		?></h6>
        <?php if(!empty($contenido[$i]['inst_archivo_pdf'])) echo '<a id="link_archivos" target="_blank" href="documentos/'.$contenido[$i]['inst_archivo_pdf'].'">'.$contenido[$i]['inst_archivo_pdf'].'</a>'; ?>
    </div>
</div>
<div id="div_index_der_sup">
	<div id="BannerNoticias"><?php
		require_once("clases/noticias.class.php");
		$noticias = new noticias(); $noticias->muestra_contenido();
    ?></div>
</div>