<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
?>
	<script language="javascript" src="../scripts/configuracion.js"></script>
<?php
	defined( '_GI' ) or define( '_GI', 1 );
	require("funciones_globales.php");

	if(is_numeric($_REQUEST['op'])) $opcion = $_REQUEST['op'];
	else $opcion = 5;

	$operacion = new configuracion();

	switch($opcion) {
		case 1 : $operacion->editar();
				 break;
		case 2 : $res = $operacion->guardar_articulo();
				 break;
	}
}

class configuracion {
	var $correo_contacto;
	var $consulta;
	var $resultados;
	
	function __construct(){
		unset($this->resultados);
		unset($this->consulta);
		if(!empty($_REQUEST['correo'])) $this->correo_contacto = $_POST['correo'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
		$this->consulta->tabla("configuracion");
	}
	
	function contenido_configuracion() {
		$this->consulta->datos("configuracion_nombre, configuracion_valor");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		$this->consulta->desconecta();
	}
	
	function editar() {
		$this->consulta->conecta();
		$this->contenido_configuracion();
		$this->pintar_form(2);
	}
	
	function pintar_form($a) {
		echo '<form action="configuracion.php?op='.$a.'" id="configuracion" class="configuracion" name="configuracion" method="post">';
		echo '<label for="correo">Correo para contácto desde la página web: </label> <input id="correo" name="correo" type="text" size="40" value="';
		if(!empty($this->resultados)) echo $this->resultados['0']['configuracion_valor'];
		echo '">';
		echo '<br /><button id="button" class="submit" type="submit">Guardar</button>';
		echo '</form>';
	}
	
	function guardar_articulo() {
		if(!empty($this->correo_contacto)) {
			$this->consulta->conecta();
			$this->consulta->tabla('configuracion');
			$this->consulta->datos("configuracion_valor = '".$this->correo_contacto."'");
			$this->consulta->opciones("WHERE configuracion_nombre = 'correo_contacto'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado la configuración", 2);
		}
		else $mensaje = new mensajes_globales("No se ha actualizado la configuracion, los campos requeridos estan vacios", 2);
		$mensaje->info();
	}
}
?>