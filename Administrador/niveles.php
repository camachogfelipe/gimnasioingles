<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<script language="javascript" src="../scripts/niveles.js"></script>
	<script type="text/javascript" src="../scripts/tinymce/jscripts/tiny_mce/jquery.tinymce.js"></script>
	<script type="text/javascript" src="../scripts/editor_texto.js"></script>
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

	if(isset($_REQUEST['A'])) $activo = $_REQUEST['A'];
	else $activo = NULL;

	$operacion = new niveles($id);

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

class niveles{
	var $id;
	var $titulo;
	var $descripcion;
	var $equivalente;
	var $rango_edad;
	var $usuario;
	var $nom_usuario;
	var $tipo;
	var $activo;
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
		if(!empty($_REQUEST['descripcion'])) $this->descripcion = $_POST['descripcion'];
		if(!empty($_REQUEST['equivalente'])) $this->equivalente = $_POST['equivalente'];
		if(!empty($_REQUEST['rango1'])) $this->rango_edad = $_POST['rango1'];
		if(!empty($_REQUEST['rango2'])) $this->rango_edad .= " - ".$_POST['rango2'];
		else $this->activo = 0;
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
		$this->consulta->tabla("niveles");
	}
	
	function contenido_niveles() {
		$this->consulta->datos("niv_nombre, niv_descripcion, niv_equivalente, niv_rango_edad, niv_fecha_actualizado");
		$this->consulta->opciones("WHERE niv_id = '$this->id'");
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
		$this->contenido_niveles();
		$this->pintar_form(7);
	}
	
	function pintar_form($a) {
		echo '<form action="niveles.php?op='.$a.'&amp;id='.$this->id.'" id="niveles" class="niveles" name="niveles" method="post">';
		echo '<label for="nombre">Nombre: </label> <input name="nombre" id="nombre" type="text" size="60" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['niv_nombre'];
		echo '"><span id="error-nombre"></span>';
		echo '<br /><label for="rango1">Rango de edad (en años): </label> <input id="rango1" name="rango1" type="text" size="5" value="';
		if(!empty($this->resultados)) {
			$rango = explode(" - ", $this->resultados[0]['niv_rango_edad']);
			echo $rango[0];
		}
		echo '">';
		echo ' - <input id="rango2" name="rango2" type="text" size="5" value="';
		if(!empty($this->resultados)) {
			if(count($rango) > 1) echo $rango[1];
		}
		echo '"><span id="error-rango1"></span><span id="error-rango2"></span>';
		echo '<br /><label for="equivalente">Nivel equivalente en otros planteles: </label> <input id="equivalente" name="equivalente" type="text" size="20" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['niv_equivalente'];
		echo '"><span id="error-equivalente"></span>';
		echo '<p><label for="descripcion">Descripci&oacute;n</label><br />';
		echo '<textarea id="descripcion" class="tinymce" name="descripcion" cols="100" rows="20">';
		if(!empty($this->resultados)) echo $this->resultados[0]['niv_descripcion'];
		echo '</textarea><span id="error-descripcion"></span></p>';
		echo '<button id="button" class="submit" type="submit">Guardar</button> <button id="button" class="reset" type="reset">Limpiar</button>';
		echo '</form>';
	}
	
	function listar($tipo = NULL) {
		$this->consulta->opciones("WHERE niveles.usr_id=usuarios.usr_id");
		switch($tipo) {
			case 1 : $this->consulta->tabla('niveles, usuarios');
					 $datos = 'niveles.niv_id, LOWER(niveles.niv_nombre), niveles.niv_fecha_actualizado, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)';
					 break;
			case 2 : $this->consulta->tabla('niveles, usuarios');
					 $datos = '*';
					 break;
			default : $tipo = 1;
			 		  $this->consulta->tabla('niveles, usuarios');
					 $datos = 'niveles.niv_id, LOWER(niveles.niv_nombre), niveles.niv_fecha_actualizado, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)';
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
				echo "<th>Nivel</th>\n";
				echo "<th>Fecha actualización</th>\n";
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
				$res = $this->resultados[$i];
				foreach($res as $clave=>$valor) {
					echo '<td align="center">';
					if($clave == "art_activo") {
						echo '<a href="#id='.$this->resultados[$i]['art_id'].'" onclick="recargar(\'charlas\', \'op=4&id='.$this->resultados[$i]['art_id'].'&A='.$valor.'&pag='.$this->pag.'\', \'contenido\')">';
						echo '<img src="../imagenes/';
						if($valor == "S") echo 'checked.png';
						else echo 'nochecked.png';
						echo '" width="16" height="16" border="0" align="absmiddle"></a>';
					}
					else echo ucfirst($valor);
					echo "</td>";
				}
				echo '<td align="center">';
				echo '<a href="#niveles_editar" title="Editar" ';
				echo 'onclick="recargar(\'niveles\', \'t=1&amp;op=2&amp;id='.$this->resultados[$i]['niv_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="niveles.php?op=3&amp;s=1&amp;id='.$this->resultados[$i]['niv_id'].'amp;width=800" class="thickbox" title="Ver">';
				echo '<img src="../imagenes/lupa_peq.png" width="18" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'niveles\', \'op=4&amp;id='.$this->resultados[$i]['niv_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="17" height="17" border="0"></a>';
				echo "</td>";
				echo "<tr>";
			}
			echo "<tbody>";
		}
	}
	
	function crear_articulo() {
		if(!empty($this->nombre) and !empty($this->descripcion) and !empty($this->equivalente) and !empty($this->rango_edad)) {
			$this->consulta->conecta();
			$this->consulta->tabla("niveles");
			$this->consulta->columnas("niv_nombre, niv_descripcion, niv_equivalente, niv_rango_edad, niv_fecha_actualizado, usr_id");
			$this->consulta->datos("'".$this->nombre."','".$this->descripcion."','".$this->equivalente."','".$this->rango_edad."','".date("Y-m-d")."','".$this->usuario."'" );
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado el nivel", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado el nivel, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_articulo() {
		if(!empty($this->nombre) and !empty($this->descripcion) and !empty($this->equivalente) and !empty($this->rango_edad)) {
			$this->consulta->conecta();
			$this->consulta->tabla('niveles');
			$this->consulta->datos("niv_nombre='".$this->nombre."',niv_descripcion='".$this->descripcion."',niv_equivalente='".$this->equivalente."',niv_rango_edad='".$this->rango_edad."',niv_fecha_actualizado='".date("Y-m-d")."',usr_id='".$this->usuario."'" );
			$this->consulta->opciones("WHERE niv_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado el nivel", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado el nivel, los campos requeridos estan vacios", 2);
		$mensaje->info();
		$this->listar();
	}
	
	function ver() {
		echo '<div id="contenido2" style="padding:5px; font-size: medium">';
		$this->consulta->conecta();
		$this->consulta->tabla("niveles, usuarios");
		$this->consulta->datos("niveles.*, CONCAT_WS(' ', usuarios.usr_nombre, usuarios.usr_apellido)");
		$this->consulta->opciones("WHERE niveles.niv_id = '$this->id' and niveles.usr_id=usuarios.usr_id");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		echo "<br /><h1>".$this->resultados[0]['niv_nombre']."</h1>";
		echo $this->resultados[0]['niv_descripcion'];
		echo "<strong>Rango de edad del nivel:</strong> ".$this->resultados[0]['niv_rango_edad'];
		echo "<p><strong>Nivel equivalente en otros planteles:</strong> ".$this->resultados[0]['niv_equivalente']."</p>";
		echo "<strong>Fecha de la última modificación:</strong> ".$this->resultados[0]['niv_fecha_actualizado'];
		echo "<p><strong>Creación y/o modificación por:</strong> ".$this->resultados[0]['CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)']."</p>";
		echo '</div>';
	}
	
	function eliminar() {
		$this->consulta->conecta();
		$this->consulta->tabla("niveles");
		$this->consulta->opciones("WHERE niv_id = '$this->id'");
		$this->consulta->eliminar();
		$this->consulta->ejecutar_query();
		$this->listar();
	}
}
?>