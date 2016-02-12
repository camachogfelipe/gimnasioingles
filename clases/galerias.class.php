<?php
define( '_GI', 1 );
require_once("Administrador/funciones_globales.php");

class galerias {
	private $consulta;
	private $contenido;
	
	function __construct() {
		$this->consulta = new BDManejo();
		$this->consulta->tabla("galerias");
	}
	
	function contenido($opcion = NULL) {
		$this->consulta->datos("gal_titulo, gal_descripcion, gal_archivos, gal_fecha_creada, gal_fecha_modificada");
		if(!is_null($opcion)) $this->consulta->opciones("WHERE gal_id = '$opcion'");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
		return $this->contenido;
	}
	
	function menu($op) {
		$this->consulta->datos("gal_id, LOWER(gal_titulo)");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
		if(!empty($this->contenido)) {
			foreach($this->contenido as $valor) {
				echo '<li ';
				if($op == $valor['gal_id']) echo 'class="activo" ';
				echo 'onclick="recargar(\'galerias\', \'?i='.$valor['gal_id'].'\', \'contenido2\')">'.ucfirst($valor['LOWER(gal_titulo)']).'</li>';
			}
			return true;
		}
		else return false;
	}
}
?>