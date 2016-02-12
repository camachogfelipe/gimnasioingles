<?php
define( '_GI', 1 );
require_once("Administrador/funciones_globales.php");

class institucional {
	private $consulta;
	private $contenido = NULL;
	
	function __construct() {
		$this->consulta = new BDManejo();
		$this->consulta->tabla("institucional");
	}
	
	function contenido_institucional($opcion) {
		unset($this->contenido);
		$this->consulta->datos("inst_titulo, inst_descripcion, inst_fecha_modificado, inst_archivo_pdf");
		$this->consulta->opciones("WHERE inst_id = '$opcion'");
		$this->consulta->leer_datos();
		$this->contenido[] = NULL;
		$this->contenido = $this->consulta->array_asociativo();
		
		return $this->contenido;
	}
	
	function menu($op) {
		unset($this->contenido);
		$this->consulta->datos("inst_id, LOWER(inst_titulo)");
		$this->consulta->opciones("ORDER BY orden ASC");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
		$this->consulta->libera();
		$this->consulta->desconecta();
		if(!empty($this->contenido)) {
			$i = 1;
			foreach($this->contenido as $valor) {
				echo '<li ';
				if((isset($op) and $op == $valor['inst_id']) || (!isset($op) and $i == 1)) :
					echo 'class="activo" ';
					$op = $valor['inst_id'];
				endif;
				echo 'onclick="recargar(\'institucional\', \'i='.$valor['inst_id'].'\', \'contenido2\')">'.ucfirst($valor['LOWER(inst_titulo)']).'</li>'."\n";
				$i++;
			}
		}
		else { echo "No existen datos institucionales"; exit(); }
		unset($this->contenido);
		return $op;
	}
}
?>