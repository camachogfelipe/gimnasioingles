<?php
defined("_GI") or die();

class BDManejo {
	private $usuario;
	private $clave;
	private $servidor;
	private $base_datos;
	private $tabla;
	private $columnas;
	private $datos;
	private $opciones;
	private $sql;
	private $resultados;
	private $res;
	private $conecto;
	private $tipo;	
	private $pag;
	private $limite;
	
	function __construct($pag = 1) {
		if(empty($this->usuario)) {
			$this->usuario = "gimnasio_usrdb";
			$this->clave = "GS2013";
			$this->servidor = "localhost";
			$this->base_datos = "gimnasio_ingles";
		}
		unset($this->tabla);
		unset($this->columnas);
		unset($this->datos);
		unset($this->opciones);
		unset($this->sql);
		unset($this->resultados);
		unset($this->res);
		unset($this->conecto);
		$this->pag = $pag;
		$this->limite = (($this->pag - 1) * 10).",10";
	}
	
	function tabla($tabla) {
		return $this->tabla = $tabla;
	}
	
	function datos($datos) {
		return $this->datos = $datos;
	}
	
	function columnas($columnas) {
		return $this->columnas = $columnas;
	}
	
	function opciones($opciones) {
		return $this->opciones = $opciones;
	}
	
	function conecta(){
		$this->conecto = mysql_connect($this->servidor, $this->usuario, $this->clave);
	    mysql_select_db($this->base_datos) or die("La base de datos no existe");
		return $this->conecto;
	}
	
	function desconecta() {
		mysql_close($this->conecto);
	}
	
	function insert() {
		$this->sql = "INSERT INTO ".$this->tabla."(".$this->columnas.") VALUES (".$this->datos.")";
		if(!empty($this->opciones)) $this->sql .= " ".$this->opciones;
	}
	
	function actualiza() {
		$this->sql = "UPDATE ".$this->tabla." SET ".$this->datos;
		if(!empty($this->opciones)) $this->sql .= " ".$this->opciones;
	}
	
	function eliminar() {
		$this->sql = "DELETE FROM ".$this->tabla;
		if(!empty($this->opciones)) $this->sql .= " ".$this->opciones;
	}
	
	function leer_datos() {
		$this->sql = "SELECT ".$this->datos." FROM ".$this->tabla;
		if(!empty($this->opciones)) $this->sql .= " ".$this->opciones;
		$this->sql .= " LIMIT ".$this->limite;
	}
	
	function total_resultados() {
		if(!empty($this->resultados)) return mysql_num_rows($this->resultados);
		else return $this->resultados = true;
	}
	
	function ejecutar_query() {
		unset($this->resultados);
		unset($this->res);
		mysql_set_charset('utf8');
		return $this->resultados = mysql_query($this->sql) or die("".$this->sql.",".mysql_error());
	}
	
	function array_array($tipo = NULL) {
		$this->conecta();
		$this->ejecutar_query();
		if(!empty($this->resultados)) {
			switch($tipo) {
				case 1 : while($row = mysql_fetch_array($this->resultados, MYSQL_NUM)) $this->res[] = $row;
						 break;
				case 2 : while($row = mysql_fetch_array($this->resultados, MYSQL_ASSOC)) $this->res[] = $row;
						 break;
				case 3 : while($row = mysql_fetch_array($this->resultados, MYSQL_BOTH)) $this->res[] = $row;
						 break;
				default : while($row = mysql_fetch_array($this->resultados, MYSQL_BOTH)) $this->res[] = $row;
						  break;
			}
			return $this->res;
		}
	}
	
	function array_asociativo() {
		$this->conecta();
		$this->ejecutar_query();
		if(!empty($this->resultados)) {
			while($row = mysql_fetch_assoc($this->resultados)) $this->res[] = $row;
			return $this->res;
		}
		else return $this->res;
	}
	
	function array_objetos() {
		$this->conecta();
		$this->ejecutar_query();
		if(!empty($this->resultados)) {
			while($row = mysql_fetch_object($this->resultados)) $this->res[] = $row;
			return $this->res;
		}
	}
	
	function array_numerico() {
		$this->conecta();
		$this->ejecutar_query();
		if(!empty($this->resultados)) {
			while($row = mysql_fetch_row($this->resultados)) $this->res[] = $row;
			return $this->res;
		}
	}
	
	function libera() {
		unset($this->columnas);
		unset($this->datos);
		unset($this->opciones);
		unset($this->resultados);
	}
	
	function ultimo_id() {
		return mysql_insert_id();
	}
	
	function mostrar_sql() {
		echo $this->sql;
	}
}

class mensajes_globales {
	private $imagen;
	private $mensaje;
	private $i = 1;
	
	function __construct($mensaje, $i) {
		if(!empty($mensaje)) $this->mensaje = $mensaje;
		if(!empty($i)) $this->i = $i;
		else $this->i = 1;
	}
	
	function info(){
		$this->imagen = "info.png";
		$this->pintar_mensaje();
	}
	
	function alerta(){
		$this->imagen = "alerta.png";
		$this->pintar_mensaje();
	}
	
	function pintar_mensaje() {
		echo '<p id="info">';
		if($this->i == 1) echo '<img src="imagenes/'.$this->imagen.'" align="absmiddle"> ';
		elseif($this->i == 2) echo '<img src="../imagenes/'.$this->imagen.'" align="absmiddle"> ';
		echo $this->mensaje;
		echo '</p>';
	}
}
?>