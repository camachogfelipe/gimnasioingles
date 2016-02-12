<?php
if(isset($_GET['i'])) {
	$op = $_GET['i'];
	if(!is_numeric($op)) $op = 1;
}
else $op = 1;
?>
<script type="text/javascript" src="scripts/jquery.validate.js"></script>
<script type="text/javascript" src="scripts/jquery.validate.additional-methods.js"></script>
<script type="text/javascript" src="scripts/contacto.js"></script>
<script>
$(document).ready(function()  
{
	redondear();
});
</script>
<div id="div_ins_izq_sup">
	<h1>
    	<img src="imagenes/lapiz.png" width="94" height="54" align="absmiddle" />
        Contáctenos
	</h1>
    <div id="contenido2">
		<form action="contacto2.php" method="post" name="contacto" id="contacto">
			<table width="100%" border="0" cellspacing="2" cellpadding="3">
				<tr>
					<td align="right"><label for="nombre_completo"><strong>Nombre completo</strong></label></td>
					<td><input name="nombre_completo" type="text" size="50"></td>
				</tr>
				<tr>
					<td align="right"><strong>Asunto</strong></td>
					<td><input name="motivo" type="text" size="50"></td>
				</tr>
				<tr>
					<td align="right"><label for="mail"><strong>Mail de contacto</strong></label></td>
					<td><input name="mail" type="text" size="50"></td>
				</tr>
				<tr>
					<td align="right"><label for="asunto"><strong>Mensaje</strong></label></td>
					<td><textarea name="asunto" cols="40" rows="6"></textarea></td>
				</tr>
				<tr>
					<td colspan="2" align="center">
						<button id="button" class="submit" type="submit">Enviar</button> 
                        <button id="button" class="reset" type="reset">Limpiar</button>
					</td>
				</tr>
			</table>
		</form>
		<div id="resultado"></div>
        <p>Calle 144 No. 18ª – 28, Bogotá<br>4769822 / 3153551794<br>drodriguez85@gimnasioingles.com</p>
    </div>
</div>
<div id="div_index_der_sup">
	<div id="BannerNoticias"><?php
		require_once("clases/noticias.class.php");
		$noticias = new noticias(); $noticias->muestra_contenido();
    ?></div>
</div>