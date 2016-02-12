<?php
defined( '_GI' ) or define( '_GI', 1 );
require_once("Administrador/funciones_globales.php");

class alianzas {
	private $consulta;
	private $contenido;
	
	function __construct() {
		$this->consulta = new BDManejo();
		$this->consulta->tabla("alianzas");
	}
	
	function contenido() {
		$this->consulta->datos("ali_web, ali_nombre, ali_logo");
		$this->consulta->leer_datos();
		$this->contenido = $this->consulta->array_asociativo();
	}
	
	function muestra_contenido() {
		$this->contenido();
		if(!empty($this->contenido)) {
			echo '<div id="alianzas">';
			echo '<ul id="mycarousel" class="jcarousel-skin-tango">';
			foreach($this->contenido as $valor) {
				echo '<li>';
				echo '<a href="'.$valor['ali_web'].'" target="_blank">';
				if(!empty($valor['ali_logo'])) {
					$tam = 75;
					$tmp = getimagesize('logos_alianzas/'.$valor['ali_logo']);
					if($tmp[1] > $tam) {
						if($tmp[0] > $tam || $tmp[1] > $tam) {
							if($tmp[0] > $tmp[1]) {
								$width = ($tam * $tmp[0])/$tmp[1];
								$height = $tam;
								if($width > 150) :
									$tam = 150;
									$width = $tam;
									$height = ($tam * $tmp[1])/$tmp[0];
								endif;
							}
							elseif($tmp[0] < $tmp[1]) {
								$width = $tam;
								$height = ($tam * $tmp[0])/$tmp[1];
							}
							elseif($tmp[0] == $tmp[1]) {
								$width = $tam;
								$height = $tam;
							}
						}
						else {
							$width = $tmp[0];
							$height = $tmp[1];
						}
					}
					else {
						$width = $tmp[0];
						$height = $tmp[1];
					}
					echo '<img src="logos_alianzas/'.$valor['ali_logo'].'" width="'.$width.'" height="'.$height.'" border="0" />';
				}
				else echo $valor['ali_nombre'];
				echo "</a></li>";
			}
			echo '</ul></div>';
		}
		else {
			$mensaje = new mensajes_globales("No existen alianzas", 1);
			$mensaje->info();
		}
	}
}
?>