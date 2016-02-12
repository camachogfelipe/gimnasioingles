<?php
defined( '_GI' ) or define( '_GI', 1 );
require_once("Administrador/funciones_globales.php");

class calendario {
	private $consulta;
	private $fecha;
	var $resultados;
	
	function __construct() {
		$this->consulta = new BDManejo();
		$this->consulta->tabla("calendario");
	}
	
	function actualiza_dia_semana($dia) { return ($dia>0)?$dia-1:6; }
	
	function mes_anterior($fecha) {
		$fecha['mes'] -= 1;
		if($fecha['mes']<1) {
			$fecha['mes'] = 12;
			$fecha['year'] -= 1;
		}
		$this->mostrar_mes($fecha);		
	}

	function mes_siguiente($fecha) {
		$fecha['mes'] += 1;
		if($fecha['mes']>12) {
			$fecha['mes'] = 1;
			$fecha['year'] += 1;
		}
		$this->mostrar_mes($fecha);	
	}
	
	function mostrar_mes($fecha) { echo "m=".$fecha['mes']."&y=".$fecha['year']; }
	
	function eventos_dia($fecha) {
		$this->consulta->datos("cal_id, cal_titulo");
		$this->consulta->opciones("WHERE cal_dia = '$fecha[dia]' and cal_mes = '$fecha[mes]' and cal_year = '$fecha[year]'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		if(!empty($this->resultados)) {
			echo '<div id="eventos"><ul>';
			foreach($this->resultados as $valor) {
				$res = $valor;
				echo '<li><a href="calendario.php?op=true&id='.$res['cal_id'].'" class="lytebox" data-title="'.$res['cal_titulo'].'" ';
				echo 'data-lyte-options="autoResize:true width:600px height:600px scrolling:auto">';
				echo $res['cal_titulo'];
				echo '</a></li>';
			}
			echo '</div>';
		}
	}
	
	function detalle_evento($id) {
		$this->consulta->datos("*");
		$this->consulta->opciones("WHERE cal_id = '$id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		
		echo '<link href="gik.css" rel="stylesheet" type="text/css" />';
		echo '<body id="evento_detalle">';
		echo '<div class="detalle">';
		echo '<h1>'.$this->resultados[0]['cal_titulo'].'</h1>';
		echo $this->resultados[0]['cal_descripcion'];
		echo '<strong>Hora de inicio:</strong>'.$this->resultados[0]['cal_hora_inicio'];
		if(!empty($this->resultados[0]['cal_hora_fin'])) echo '<br /><strong>Hora de finalización:</strong>'.$this->resultados[0]['cal_hora_fin'];
		echo '</div></body>';
	}
	
	function proximos_eventos() {
		$this->consulta->datos("*");
		$this->consulta->opciones("ORDER BY cal_year, cal_mes, cal_dia DESC");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		//echo '<pre style="font-size:8px">';print_r($this->resultados);echo "<pre>";
	}
	
	function normaliza_fecha_semana($fecha) {
		$dias = array("Lunes","Martes","Miercoles","Jueves","Viernes","Sabado","Domingo");
		return $dia = $dias[date(N, strtotime($fecha))];
	}
	
	function normaliza_mes_nombre($mes = NULL) {
		switch($mes) :
			case 1 : return "Enero"; break;
			case 2 : return "Febrero"; break;
			case 3 : return "Marzo"; break;
			case 4 : return "Abril"; break;
			case 5 : return "Mayo"; break;
			case 6 : return "Junio"; break;
			case 7 : return "Julio"; break;
			case 8 : return "Agosto"; break;
			case 9 : return "Septiembre"; break;
			case 10 : return "Octubre"; break;
			case 11 : return "Noviembre"; break;
			case 12 : return "Diciembre"; break;
		endswitch;
	}
	
	function mostrar_proximos_eventos() {
		$i = 0;
		echo '<ul class="eventos_home">';
		foreach($this->resultados as $evento) :
			echo '<li>';
			echo $evento['cal_titulo'];
			if($evento['cal_dia'] > 0) :
				echo ": ";
				echo $dia = $this->normaliza_fecha_semana($evento['cal_year']."-".str_pad($evento['cal_mes'], 2, 0, STR_PAD_LEFT)."-".str_pad($evento['cal_dia'], 2, 0, STR_PAD_LEFT));
				echo " ".str_pad($evento['cal_dia'], 2, 0, STR_PAD_LEFT);
				echo " de ".$mes = $this->normaliza_mes_nombre($evento['cal_mes']);
				echo " de ".$evento['cal_year'];
			endif;
			echo '</li>';
			$i++;
			if($i == 5) break;
		endforeach;
		echo "</ul>";
	}
}
?>