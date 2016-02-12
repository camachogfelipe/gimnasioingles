<?php
define( '_GI', 1 );
require_once("Administrador/funciones_globales.php");

class institucional {
	private $consulta;
	private $contenido;
	
	function __construct() {
		$this->consulta = new BDManejo();
		$this->consulta->tabla("institucional");
	}
	
	function contenido($opcion) {
		$this->consulta->datos("inst_titulo, inst_descripcion, inst_fecha_modificado, inst_archivo");
		$this->consulta->opciones("inst_id = '$opcion'");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
		
		return $this->contenido;
	}
	
	function menu($op) {
		$this->consulta->datos("inst_id, inst_titulo");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
		foreach($this->contenido as $valor) {
			echo '<li ';
			if($op == $valor['inst_id']) echo 'class="activo" ';
			echo 'onclick="recargar(\'institucional.php\', \'?i='.$valor['inst_id'].'\', \'#contenido2\')">'.$valor['inst_titulo'].'</li>';
		}
	}
}
?>