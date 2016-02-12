<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<script language="javascript" src="../scripts/institucional.js"></script>
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
	else $opcion = 6;

	if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
	else $id = NULL;

	if(isset($_REQUEST['A'])) $activo = $_REQUEST['A'];
	else $activo = NULL;

	$operacion = new institucional($id);

	switch($opcion) {
		case 1 : $operacion->crear();
				 break;
		case 2 : $operacion->editar();
				 break;
		case 3 : $operacion->ver();
				 break;
		case 4 : $operacion->activar($activo);
				 break;
		case 5 : $operacion->eliminar();
				 break;
		case 6 : $operacion->listar();
				 break;
		case 7 : $operacion->crear_articulo();
				 break;
		case 8 : $res = $operacion->guardar_articulo();
				 break;
	}
}

class institucional{
	var $id;
	var $titulo;
	var $descripcion;
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
		if(!empty($_REQUEST['titulo'])) $this->titulo = $_POST['titulo'];
		if(!empty($_REQUEST['descripcion'])) $this->descripcion = $_POST['descripcion'];
		else $this->activo = 0;
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
		$this->consulta->tabla("institucional");
	}
	
	function contenido_institucional() {
		$this->consulta->datos("inst_titulo, inst_descripcion, inst_fecha_modificado, inst_archivo_pdf");
		$this->consulta->opciones("WHERE inst_id = '$this->id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		$this->consulta->desconecta();
	}
	
	function crear() {
		$this->pintar_form(7);
	}
	
	function editar() {
		$this->consulta->conecta();
		$this->contenido_institucional();
		$this->pintar_form(8);
	}
	
	function pintar_form($a) {
		echo '<form action="institucional.php?op='.$a.'&amp;id='.$this->id.'" id="institucional" class="institucional" name="institucional" method="post">';
		echo '<label for="titulo">T&iacute;tulo: </label> <input name="titulo" type="text" size="60" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['inst_titulo'];
		echo '">';
		echo '<p><label for="descripcion">Descripci&oacute;n</label><br />';
		echo '<textarea class="tinymce" name="descripcion" cols="100" rows="20">';
		if(!empty($this->resultados)) echo $this->resultados[0]['inst_descripcion'];
		echo '</textarea></p>';
		echo '<button id="button" class="submit" type="submit">Guardar</button> <button id="button" class="reset" type="reset">Limpiar</button>';
		echo '</form>';
	}
	
	function listar($tipo = NULL) {
		$this->consulta->opciones("WHERE institucional.usr_id=usuarios.usr_id");
		switch($tipo) {
			case 1 : $this->consulta->tabla('institucional, usuarios');
					 $datos = 'institucional.inst_id, LOWER(institucional.inst_titulo), institucional.inst_fecha_creado, institucional.inst_fecha_modificado, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)';
					 break;
			case 2 : $this->consulta->tabla('institucional, usuarios');
					 $datos = '*';
					 break;
			default : $tipo = 1;
			 		  $this->consulta->tabla('institucional, usuarios');
					  $datos = 'institucional.inst_id, LOWER(institucional.inst_titulo), institucional.inst_fecha_creado, institucional.inst_fecha_modificado, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)';
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
				echo "<th>Título</th>\n";
				echo "<th>Fecha de creación</th>\n";
				echo "<th>última modificación</th>\n";
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
				echo '<a href="#institucional_editar_articulo" title="Editar" ';
				echo 'onclick="recargar(\'institucional\', \'t=1&amp;op=2&amp;id='.$this->resultados[$i]['inst_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="institucional.php?op=3&amp;s=1&amp;id='.$this->resultados[$i]['inst_id'].'amp;width=800" class="thickbox" title="Ver">';
				echo '<img src="../imagenes/lupa_peq.png" width="18" height="17" border="0"></a>';
				echo ' <a href="subir_archivo.php?t=1&amp;op=1&amp;id='.$this->resultados[$i]['inst_id'].'&amp;KeepThis=true&TB_iframe=true&amp;width=800" class="thickbox" title="Subir archivo pdf">';
				echo '<img src="../imagenes/upload.png" width="17" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'institucional\', \'op=5&amp;id='.$this->resultados[$i]['inst_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="17" height="17" border="0"></a>';
				echo "</td>";
				echo "<tr>";
			}
			echo "<tbody>";
		}
	}
	
	function crear_articulo() {
		if(!empty($this->titulo) and !empty($this->descripcion)) {
			$this->consulta->conecta();
			$this->consulta->tabla("institucional");
			$this->consulta->columnas("inst_titulo, inst_descripcion, inst_fecha_creado, usr_id");
			$this->consulta->datos("'".$this->titulo."','".$this->descripcion."','".date("Y-m-d H:i:s")."','".$this->usuario."'" );
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado el árticulo", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado el árticulo, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_articulo() {
		if(!empty($this->titulo) and !empty($this->descripcion)) {
			$this->consulta->conecta();
			$this->consulta->tabla('institucional');
			$this->consulta->datos("inst_titulo = '".$this->titulo."', inst_descripcion = '".$this->descripcion."', inst_fecha_modificado = '".date("Y-m-d H:i:s")."', usr_id = '".$this->usuario."'");
			$this->consulta->opciones("WHERE inst_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado el árticulo", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado el árticulo, los campos requeridos estan vacios", 2);
		$mensaje->info();
		$this->listar();
	}
	
	function ver() {
		echo '<div id="contenido2" style="padding:5px; font-size: medium">';
		$this->consulta->conecta();
		$this->consulta->tabla("institucional, usuarios");
		$this->consulta->datos("institucional.*, CONCAT_WS(' ', usuarios.usr_nombre, usuarios.usr_apellido)");
		$this->consulta->opciones("WHERE institucional.inst_id = '$this->id' and institucional.usr_id=usuarios.usr_id");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		echo "<br /><h1>".$this->resultados[0]['inst_titulo']."</h1>";
		echo $this->resultados[0]['inst_descripcion'];
		echo "<strong>Fecha de creación:</strong> ".$this->resultados[0]['inst_fecha_creado'];
		if($this->resultados[0]['inst_fecha_modificado'] == "0000-00-00") echo "<p><strong>Este árticulo nunca ha sido modificado</strong></p>";
		else echo "<p><strong>Fecha de la última modificación:</strong> ".$this->resultados[0]['inst_fecha_modificado']."</p>";
		if($this->resultados[0]['inst_archivo_pdf'] == "0000-00-00") echo "<strong>Este árticulo no tiene un archivo pdf asociado</strong>";
		else echo "<strong>Archivo pdf asociado:</strong> ".$this->resultados[0]['inst_archivo_pdf'];
		echo "<p><strong>Creación y/o modificación por:</strong> ".$this->resultados[0]['CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)']."</p>";
		echo '</div>';
	}
	
	function eliminar() {
		$this->consulta->conecta();
		$this->consulta->tabla("institucional");
		$this->consulta->opciones("WHERE inst_id = '$this->id'");
		$this->consulta->eliminar();
		$this->consulta->ejecutar_query();
		$this->listar();
	}
}
?>