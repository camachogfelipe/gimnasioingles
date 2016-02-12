<?php
defined( '_GI' ) or define( '_GI', 1 );
require_once("Administrador/funciones_globales.php");

class noticias {
	private $consulta;
	private $contenido;
	
	function __construct() {
		$this->consulta = new BDManejo();
		$this->consulta->tabla("noticias");
	}
	
	function contenido() {
		$this->consulta->datos("not_titulo, not_texto, not_fecha");
		$this->consulta->opciones("WHERE not_activa='S' ORDER BY not_permanente DESC, not_fecha DESC");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
	}
	
	function muestra_contenido() {
		$this->contenido();
		if(!empty($this->contenido)) {
			echo '<ul id="mycarousel2" class="noticias_css">';
			foreach($this->contenido as $clave=>$valor) {
				$res = $this->contenido[$clave];
				echo '<li>';
				foreach($res as $c=>$v) {
					if($c == "not_titulo") echo "<h4>".$v."</h4>";
					elseif($c == "not_fecha") echo "<p>".$v."</p>";
					else echo $v;
				}
				echo "</li>";
			}
			echo '</ul>';
		}
		else {
			echo '<img src="imagenes/logo_noticias.png" border="0" width="268" height="149" />';
		}
	}
}
?>