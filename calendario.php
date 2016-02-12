<?php
define( '_GI', 1 );
error_reporting(0);
require_once("clases/calendario.class.php");
$calendario = new calendario();

if(isset($_REQUEST['op'])) {
	$calendario->detalle_evento($_REQUEST['id']);
}
else {

$meses_txt=array("","Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio", "Agosto","Septiembre","Octubre","Noviembre","Diciembre");

// Función que informa si un día pertenece al fin de semana
function festivo($dia) { return ($dia>5)?true:false; }
   
$hoy=getdate();
$dia = $hoy['mday'];
if(isset($_REQUEST['m'])) $fecha['mes'] = $_REQUEST['m'];
else $fecha['mes'] = $hoy['mon'];
if(isset($_REQUEST['y'])) $fecha['year'] = $_REQUEST['y'];
else $fecha['year'] = $hoy['year'];

// obtenemos el día de la semana del primer día del mes
$primer_dia= $calendario->actualiza_dia_semana(date("w",mktime(0,0,0,$fecha['mes'],1,$fecha['year'])));

// obtenemos el último día del mes
$ultimo_dia=date("t",mktime(0,0,0,$fecha['mes'],1,$fecha['year']));
?>
<script language="javascript">
redondear();
</script>

	    <table width="99%" border="0" cellspacing="3" cellpadding="0" align="center" id="calendario">
        	<caption align="top">
        	<a href="#mes_anterior" onclick="recargar('calendario', '<?php $calendario->mes_anterior($fecha); ?>', 'contenido2')"><img src="imagenes/cometa_izq.png" alt="" width="59" height="30" align="left" /></a><?php echo ucwords($meses_txt[$fecha['mes']])." ".$fecha['year'] ?><a href="#mes_siguiente" onclick="recargar('calendario', '<?php $calendario->mes_siguiente($fecha); ?>', 'contenido2')"><img src="imagenes/cometa_der.png" alt="" width="59" height="30" align="right" /></a>
        	</caption>
        	<thead>
            	<th width="14.3%">Lunes</th>
                <th width="14.3%">Martes</th>
                <th width="14.3%">Miercoles</th>
                <th width="14.3%">Jueves</th>
                <th width="14.3%">Viernes</th>
                <th width="14.3%">Sabado</th>
                <th width="14.2%">Domingo</th>
			</thead>
            <tbody>
            <?php
				$contador_de_dias=1;
				$a = 2;
				while ($contador_de_dias <= $ultimo_dia) {
					echo "<tr>\n";
					for ($i=0; $i<7; $i++) {
						if ($i < $primer_dia || $contador_de_dias > $ultimo_dia) echo "<td>&nbsp;</td>";
						else {
							if(festivo($i)) echo '<td class="festivo"><div id="numero_cal" class="numerof">';
							else {
								if($contador_de_dias == $hoy['mday'] and $fecha['mes'] == $hoy['mon']) echo '<td id="hoy">';
								else echo '<td>';
								echo '<div id="numero_cal" class="numerod">';
							}
							echo $contador_de_dias."</div>";
							$fecha['dia'] = $contador_de_dias;
							$calendario->eventos_dia($fecha);
							$contador_de_dias++;
						}
					}
					echo "</tr>\n";
					// la siguiente semana comienza por lunes (dia 0)
					$primer_dia=0;
					$a++;
				}
			?>
            </tbody>
		</table>
<?php } ?>