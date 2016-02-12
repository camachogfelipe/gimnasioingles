<?php
define( '_GI', 1 );
require_once("Administrador/funciones_globales.php");

class niveles {
	private $consulta;
	private $contenido;
	
	function __construct() {
		$this->consulta = new BDManejo();
		$this->consulta->tabla("niveles");
	}
	
	function contenido($opcion) {
		$this->consulta->datos("niv_nombre, niv_descripcion, niv_equivalente, niv_rango_edad, niv_fecha_actualizado");
		$this->consulta->opciones("WHERE niv_id = '$opcion'");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
		
		return $this->contenido;
	}
	
	function menu($op) {
		$this->consulta->datos("niv_id, LOWER(niv_nombre)");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
		if(!empty($this->contenido)) {
			foreach($this->contenido as $valor) {
				echo '<li ';
				if($op == $valor['niv_id']) echo 'class="activo" ';
				echo 'onclick="recargar(\'niveles\', \'i='.$valor['niv_id'].'\', \'contenido2\')">'.ucfirst($valor['LOWER(niv_nombre)']).'</li>';
			}
		}
		else { echo "No existen niveles educativos registrados"; exit(); }
	}
}
?>