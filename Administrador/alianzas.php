<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<script language="javascript" src="../scripts/alianzas.js"></script>
<?php
	if($scripts == 0) {
?>
	    <script language="javascript" src="../scripts/thickbox.js"></script>
		<link href="../scripts/thickbox.css" rel="stylesheet" type="text/css" />
<?php
	}
	defined( '_GI' ) or define( '_GI', 1 );
	require("funciones_globales.php");

	if(is_numeric($_REQUEST['op'])) $opcion = $_REQUEST['op'];
	else $opcion = 5;

	if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
	else $id = NULL;

	$operacion = new alianzas($id);

	switch($opcion) {
		case 1 : $operacion->crear();
				 break;
		case 2 : $operacion->editar();
				 break;
		case 3 : $operacion->ver();
				 break;
		case 4 : $operacion->eliminar();
				 break;
		case 5 : $operacion->listar();
				 break;
		case 6 : $operacion->crear_articulo();
				 break;
		case 7 : $res = $operacion->guardar_articulo();
				 break;
	}
}

class alianzas {
	var $id;
	var $nombre;
	var $web;
	var $usuario;
	var $nom_usuario;
	var $tipo;
	var $consulta;
	var $resultados;
	var $tresultados;
	var $pag;
	var $limite;
	
	function __construct($id){
		unset($this->resultados);
		unset($this->tresultados);
		unset($this->consulta);
		if(!empty($id)) $this->id = $id;
		if(!empty($_REQUEST['nombre'])) $this->nombre = $_POST['nombre'];
		if(!empty($_REQUEST['web'])) $this->web = $_POST['web'];
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
		$this->consulta->tabla("alianzas");
	}
	
	function contenido_institucional() {
		$this->consulta->datos("ali_nombre, ali_web, ali_logo");
		$this->consulta->opciones("WHERE ali_id = '$this->id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		$this->consulta->desconecta();
	}
	
	function crear() {
		$this->pintar_form(6);
	}
	
	function editar() {
		$this->consulta->conecta();
		$this->contenido_institucional();
		$this->pintar_form(7);
	}
	
	function pintar_form($a) {
		echo '<form action="alianzas.php?op='.$a.'&amp;id='.$this->id.'" id="alianzas" class="alianzas" name="alianzas" method="post">';
		echo '<label for="nombre">Nombre de la alianza: </label> <input name="nombre" type="text" size="60" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['ali_nombre'];
		echo '">';
		echo '<p><label for="web">Direcci&oacute;n web (incluya http://)</label> <input name="web" type="text" size="60" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['ali_web'];
		echo '"></p>';
		echo '<button id="button" class="submit" type="submit">Guardar</button> <button id="button" class="reset" type="reset">Limpiar</button>';
		echo '</form>';
	}
	
	function listar($tipo = NULL) {
		$this->consulta->tabla('alianzas, usuarios');
		$this->consulta->opciones("WHERE alianzas.usr_id=usuarios.usr_id");
		switch($tipo) {
			case 1 : $datos = 'alianzas.ali_id, ali_logo, LOWER(alianzas.ali_nombre) as ali_nombre, alianzas.ali_web, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido) as user_nombre';
					 break;
			case 2 : $datos = '*';
					 break;
			default : $tipo = 1;
					  $datos = 'alianzas.ali_id, ali_logo, LOWER(alianzas.ali_nombre) as ali_nombre, alianzas.ali_web, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido) as user_nombre';
					  break;
		}
		$this->consulta->conecta();
		$this->consulta->datos($datos);
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->desconecta();
		if(empty($this->resultados)) {
			$mensaje = new mensajes_globales("No se encontraron resultados", 2); 
			$mensaje->info();
		}
		else {
			echo "<p>Se encontraron ".$this->tresultados=$this->consulta->total_resultados()." resultados</p>";
			echo '<table width="100%" cellpadding="2" cellspacing="0" align="center">';
			echo "<thead>\n";
			if($tipo == 1) {
				echo "<th>Id</th>\n";
				echo "<th>Logo</th>\n";
				echo "<th>Nombre</th>\n";
				echo "<th>Nombre usuario</th>\n";
				echo "<th>Acciones</th>\n";
			}
			else {
				foreach($this->resultados[0] as $clave=>$valor) {
					echo "<th>".$clave."</th>\n";
				}
			}
			echo "</thead>\n";
			echo "<tbody>";
			for($i=0; $i<$this->tresultados; $i++) {
				echo "<tr>";
				echo '<td align="center">';
				echo $this->resultados[$i]['ali_id'];
				echo "</td>";
				echo '<td align="center">';
				echo '<a href="'.$this->resultados[$i]['ali_web'].'" target="_blanck">';
				echo '<img src="../logos_alianzas/'.$this->resultados[$i]['ali_logo'].'" border="0" align="absmiddle"></a>';
				echo "</td>";
				echo '<td align="center">';
				echo '<a id="link" href="'.$this->resultados[$i]['ali_web'].'" target="_blanck">';
				echo $this->resultados[$i]['ali_nombre'].'</a>';
				echo "</td>";
				echo '<td align="center">';
				echo $this->resultados[$i]['user_nombre'];
				echo "</td>";
				echo '<td align="center">';
				echo '<a id="link" href="#alianzas_editar_alianza" title="Editar" ';
				echo 'onclick="recargar(\'alianzas\', \'t=1&amp;op=2&amp;id='.$this->resultados[$i]['ali_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="subir_archivo.php?t=2&amp;op=1&amp;id='.$this->resultados[$i]['ali_id'].'&amp;KeepThis=true&TB_iframe=true&amp;width=800" class="thickbox" title="Subir archivo de imagen">';
				echo '<img src="../imagenes/upload.png" width="17" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'alianzas\', \'op=4&amp;id='.$this->resultados[$i]['ali_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="17" height="17" border="0"></a>';
				echo "</td>";
				echo "<tr>";
			}
			echo "<tbody>";
		}
	}
	
	function crear_articulo() {
		if(!empty($this->nombre) and !empty($this->web)) {
			$this->consulta->conecta();
			$this->consulta->tabla("alianzas");
			$this->consulta->columnas("ali_nombre, ali_web, usr_id");
			$this->consulta->datos("'".$this->nombre."','".$this->web."','".$this->usuario."'" );
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado la alianza", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado la alianza, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_articulo() {
		if(!empty($this->nombre) and !empty($this->web)) {
			$this->consulta->conecta();
			$this->consulta->tabla('alianzas');
			$this->consulta->datos("ali_nombre = '".$this->nombre."', ali_web = '".$this->web."', usr_id = '".$this->usuario."'");
			$this->consulta->opciones("WHERE ali_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado la alianza", 2);
		}
		else $mensaje = new mensajes_globales("No se ha actualizado la alianza, los campos requeridos estan vacios", 2);
		$mensaje->info();
		$this->listar();
	}
	
	function eliminar() {
		$this->consulta->conecta();
		$this->consulta->tabla("alianzas");
		$this->consulta->opciones("WHERE ali_id = '$this->id'");
		$this->consulta->eliminar();
		$this->consulta->ejecutar_query();
		$this->listar();
	}
}
?>