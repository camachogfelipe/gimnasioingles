<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<script language="javascript" src="../scripts/galerias.js"></script>
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

	$operacion = new galerias($id);

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
		case 8 : $operacion->ver_galeria();
				 break;
	}
}

class galerias {
	var $id;
	var $titulo;
	var $descripcion;
	var $usuario;
	var $nom_usuario;
	var $tipo;
	var $consulta;
	var $resultados;
	var $tresultados;
	var $pag;
	var $limite;
	var $directorio;
	var $archivos;
	
	function __construct($id){
		unset($this->resultados);
		unset($this->tresultados);
		unset($this->consulta);
		if(!empty($id)) $this->id = $id;
		if(!empty($_REQUEST['titulo'])) $this->titulo = $_POST['titulo'];
		if(!empty($_REQUEST['descripcion'])) $this->descripcion = $_POST['descripcion'];
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
		$this->consulta->tabla("galerias");
	}
	
	function definir_directorio() {
		$this->consulta->datos("gal_titulo, gal_archivos");
		$this->consulta->opciones("WHERE gal_id = '$this->id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->directorio = dirname(__FILE__);
		define('DS', DIRECTORY_SEPARATOR);
		$this->directorio = explode(DS, $this->directorio);
		unset($this->directorio[count($this->directorio)-1]);
		$this->directorio = implode(DS, $this->directorio);
		$this->directorio .= DS.'galerias';
		$find    = array( "á", "é", "í", "ó", "ú"," ", "ñ" );
		$replace = array( "a", "e", "i", "o", "u","_", "n" );
		$this->directorio .= DS.str_ireplace($find, $replace, strtolower($this->resultados[0]['gal_titulo']));	
		$this->archivos = explode(";",$this->resultados[0]['gal_archivos']);
	}
	
	function contenido_galerias() {
		$this->consulta->datos("gal_titulo, gal_descripcion, gal_archivos");
		$this->consulta->opciones("WHERE gal_id = '$this->id'");
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
		$this->contenido_galerias();
		$this->pintar_form(7);
	}
	
	function pintar_form($a) {
		echo '<form action="galerias.php?op='.$a.'&amp;id='.$this->id.'" id="galerias" class="galerias" name="galerias" method="post">';
		echo '<label for="titulo">Título de la galería </label> <input name="titulo" id="titulo" type="text" size="60" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['gal_titulo'];
		echo '" tabindex="1" /><span id="error-titulo"></span>';
		echo '<p><label for="descripcion">Descripci&oacute;n</label><br />';
		echo '<textarea id="descripcion" class="tinymce" name="descripcion" cols="100" rows="20" tabindex="2" >';
		if(!empty($this->resultados)) echo $this->resultados[0]['gal_descripcion'];
		echo '</textarea><span id="error-descripcion"></span></p>';
		if($a == 7 and !empty($this->resultados[0]['gal_archivos'])) {
			$this->archivos = explode(";",$this->resultados[0]['gal_archivos']);
			foreach($this->archivos as $archivo) {
				echo '<input name="archivos[]" type="checkbox" value="'.$archivo.'" /> '.$archivo."<br />";
			}
		}
		echo '<button id="button" class="submit" type="submit">Guardar</button> <button id="button" class="reset" type="reset">Limpiar</button>';
		echo '</form>';
	}
	
	function listar($tipo = NULL) {
		$this->consulta->tabla('galerias, usuarios');
		$this->consulta->opciones("WHERE galerias.usr_id_ultimo_modifico=usuarios.usr_id");
		switch($tipo) {
			case 1 : $datos = 'galerias.gal_id, LOWER(galerias.gal_titulo) as gal_titulo, galerias.gal_fecha_creada, galerias.gal_fecha_modificada, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido) as user_nombre';
					 break;
			case 2 : $datos = '*';
					 break;
			default : $tipo = 1;
					  $datos = 'galerias.gal_id, LOWER(galerias.gal_titulo) as gal_titulo, galerias.gal_fecha_creada, galerias.gal_fecha_modificada, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido) as user_nombre';
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
				echo "<th>T&iacute;tulo</th>\n";
				echo "<th>Fecha creaci&oacute;n</th>\n";
				echo "<th>Fecha modificaci&oacute;n</th>\n";
				echo "<th>Usuario modifico</th>\n";
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
				echo '<td align="center">'.$this->resultados[$i]['gal_id']."</td>";
				echo '<td align="center">'.$this->resultados[$i]['gal_titulo']."</td>";
				echo '<td align="center">'.$this->resultados[$i]['gal_fecha_creada']."</td>";
				echo '<td align="center">'.$this->resultados[$i]['gal_fecha_modificada']."</td>";
				echo '<td align="center">'.$this->resultados[$i]['user_nombre']."</td>";
				echo '<td align="center">';
				echo '<a id="link" href="#galerias_ver" title="Ver" ';
				echo 'onclick="recargar(\'galerias\', \'op=8&amp;id='.$this->resultados[$i]['gal_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/lupa_peq.png" width="18" height="17" border="0"></a> ';
				echo '<a id="link" href="#galerias_editar" title="Editar" ';
				echo 'onclick="recargar(\'galerias\', \'op=2&amp;id='.$this->resultados[$i]['gal_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="subir_galeria.php?t=2&amp;op=1&amp;noscript=1&amp;id='.$this->resultados[$i]['gal_id'].'&amp;KeepThis=true&TB_iframe=true&amp;width=800" class="thickbox" title="Subir archivo de imagen">';
				echo '<img src="../imagenes/upload.png" width="17" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'galerias\', \'op=4&amp;id='.$this->resultados[$i]['gal_id'].'\', \'contenido2\')">';
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
			$this->consulta->tabla("galerias");
			$this->consulta->columnas("gal_titulo, gal_descripcion, gal_fecha_creada, gal_fecha_modificada, usr_id_creador, usr_id_ultimo_modifico");
			$this->consulta->datos("'".$this->titulo."','".$this->descripcion."','".date("Y-m-d")."','".date("Y-m-d h:m:s")."','".$this->usuario."','".$this->usuario."'" );
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado la galeria", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado la galeria, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_articulo() {
		if(!empty($_REQUEST['archivos'])) { foreach($_REQUEST['archivos'] as $valor) $this->archivos[] = $valor; }
		if(!empty($this->titulo) and !empty($this->descripcion)) {
			$this->consulta->conecta();
			$this->consulta->tabla('galerias');
			$this->consulta->datos("gal_titulo = '".$this->titulo."', gal_fecha_modificada = '".date("Y-m-d h:m:s")."', usr_id_ultimo_modifico = '".$this->usuario."'");
			$this->consulta->opciones("WHERE gal_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado la galeria", 2);
		}
		else $mensaje = new mensajes_globales("No se ha actualizado la galeria, los campos requeridos estan vacios", 2);
		$mensaje->info();
		$this->listar();
	}
	
	function eliminar() {
		$this->definir_directorio();
		foreach($this->archivos as $archivo) unlink($this->directorio."\\".$archivo);
		rmdir($this->directorio);
		$this->consulta->conecta();
		$this->consulta->tabla("galerias");
		$this->consulta->opciones("WHERE gal_id = '$this->id'");
		$this->consulta->eliminar();
		$this->consulta->ejecutar_query();
		$this->listar();
	}
	
	function ver_galeria() {
		$this->contenido_galerias();
		echo '<h4>'.$this->resultados[0]['gal_titulo'].'</h4>';
        echo $this->resultados[0]['gal_descripcion'];
        $fotos = explode(";",$this->resultados[0]['gal_archivos']);
		$find    = array( "á", "é", "í", "ó", "ú"," ", "ñ" );
		$replace = array( "a", "e", "i", "o", "u","_", "n" );
		$this->directorio .= str_ireplace($find, $replace, strtolower($this->resultados[0]['gal_titulo']));
		foreach($fotos as $valor) {
			echo '<a href="../galerias/'.$this->directorio.'/'.$valor.'" class="lytebox" data-lyte-options="group:'.$this->resultados[0]['gal_titulo'].'" data-title="'.$this->resultados[0]['gal_titulo'].'"><img src="../galerias/'.$this->directorio.'/'.$valor.'" width="94" height="64" align="absmiddle" /></a>'."\n";
		}
		echo '<br /><a title="Regresar" href="#Galerias_ver" onclick="recargar(\'galerias\', \'op=5&amp;pag='.$this->pag.'\', \'contenido2\')">';
		echo '<img src="../imagenes/cometa_izq.png" alt="Regresar" />';
		echo '</a>';
	}
}
?>