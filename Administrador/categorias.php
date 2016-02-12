<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<script language="javascript" src="../scripts/categorias.js"></script>
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
	else $opcion = 4;

	if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
	else $id = NULL;

	$operacion = new categorias($id);

	switch($opcion) {
		case 1 : $operacion->crear();
				 break;
		case 2 : $operacion->editar();
				 break;
		case 3 : $operacion->eliminar();
				 break;
		case 4 : $operacion->listar();
				 break;
		case 5 : $operacion->crear_categoria();
				 break;
		case 6 : $res = $operacion->guardar_categoria();
				 break;
	}
}

class categorias{
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
		$this->consulta->tabla("categorias");
	}
	
	function contenido_categoria() {
		$this->consulta->datos("cat_titulo, cat_descripcion");
		$this->consulta->opciones("WHERE cat_id = '$this->id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		$this->consulta->desconecta();
	}
	
	function crear() {
		$this->pintar_form(5);
	}
	
	function editar() {
		$this->consulta->conecta();
		$this->contenido_categoria();
		$this->pintar_form(6);
	}
	
	function pintar_form($a) {
		echo '<form action="categorias.php?op='.$a.'&amp;id='.$this->id.'" id="categorias" class="categorias" name="categorias" method="post">';
		echo '<label for="titulo">Título de la categoría </label> <input name="titulo" id="titulo" type="text" size="60" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['cat_titulo'];
		echo '" tabindex="1" /><span id="error-titulo"></span>';
		echo '<p><label for="descripcion">Descripci&oacute;n</label><br />';
		echo '<textarea id="descripcion" class="tinymce" name="descripcion" cols="100" rows="20" tabindex="5" >';
		if(!empty($this->resultados)) echo $this->resultados[0]['cat_descripcion'];
		echo '</textarea><span id="error-descripcion"></span></p>';
		echo '<button id="button" class="submit" type="submit">Guardar</button> <button id="button" class="reset" type="reset">Limpiar</button>';
		echo '</form>';
	}
	
	function listar($tipo = NULL) {
		$this->consulta->tabla('categorias');
		$datos = 'cat_id, LOWER(cat_titulo), cat_descripcion';
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
				echo "<th>Id</th>\n";
				echo "<th>Título</th>\n";
				echo "<th>Descripción</th>\n";
				echo "<th>Acciones</th>\n";
			echo "</thead>\n";
			echo "<tbody>";
			for($i=0; $i<$this->tresultados; $i++) {
				echo "<tr>";
				$res = $this->resultados[$i];
				foreach($res as $clave=>$valor) {
					echo '<td align="center">';
					echo ucfirst($valor);
					echo "</td>";
				}
				echo '<td align="center">';
				echo '<a href="#niveles_editar" title="Editar" ';
				echo 'onclick="recargar(\'categorias\', \'&amp;op=2&amp;id='.$this->resultados[$i]['cat_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'categorias\', \'op=3&amp;id='.$this->resultados[$i]['cat_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="17" height="17" border="0"></a>';
				echo "</td>";
				echo "<tr>";
			}
			echo "<tbody>";
		}
	}
	
	function crear_categoria() {
		if(!empty($this->titulo) and !empty($this->descripcion)) {
			$this->consulta->conecta();
			$this->consulta->tabla("categorias");
			$this->consulta->columnas("cat_titulo, cat_descripcion");
			$this->consulta->datos("'".$this->titulo."','".$this->descripcion."'" );
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado la categoría", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado la categoría, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_categoria() {
		if(!empty($this->nombre) and !empty($this->descripcion) and !empty($this->equivalente) and !empty($this->rango_edad)) {
			$this->consulta->conecta();
			$this->consulta->tabla('categorias');
			$this->consulta->datos("cat_titulo='".$this->titulo."',cat_descripcion='".$this->descripcion."'" );
			$this->consulta->opciones("WHERE cat_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado la categoría", 2);
		}
		else $mensaje = new mensajes_globales("No se ha guardado la categoría, los campos requeridos estan vacios", 2);
		$mensaje->info();
		$this->listar();
	}
	
	function eliminar() {
		$this->consulta->conecta();
		$this->consulta->tabla("categorias");
		$this->consulta->opciones("WHERE cat_id = '$this->id'");
		$this->consulta->eliminar();
		$this->consulta->ejecutar_query();
		$this->listar();
	}
}
?>