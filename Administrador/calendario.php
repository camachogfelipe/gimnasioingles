<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<link rel="stylesheet" type="text/css" media="all" href="../scripts/jquery-ui-1.8.16.custom/css/ui-lightness/jquery-ui-1.8.16.custom.css" />
	<script language="javascript" src="../scripts/jquery-ui-1.8.16.custom/js/jquery-ui-1.8.16.custom.min.js" type="text/javascript"></script>
	<script language="javascript" src="../scripts/jquery-ui-1.8.16.custom/js/jquery.ui.datepicker-es.js" type="text/javascript"></script>
	<script language="javascript" src="../scripts/jquery-ui-1.8.16.custom/js/timepicker.js" type="text/javascript"></script>
	<script language="javascript" src="../scripts/calendario.js"></script>
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

	$operacion = new calendario($id);

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
		case 6 : $operacion->crear_evento();
				 break;
		case 7 : $res = $operacion->guardar_evento();
				 break;
	}
}

class calendario{
	var $id;
	var $titulo;
	var $descripcion;
	var $fecha;
	var $hora_inicio;
	var $hora_fin;
	var $categoria;
	var $categorias;
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
		unset($this->categoria);
		unset($this->categorias);
		unset($this->consulta);
		if(!empty($id)) $this->id = $id;
		if(!empty($_REQUEST['titulo'])) $this->titulo = $_POST['titulo'];
		if(!empty($_REQUEST['descripcion'])) $this->descripcion = $_POST['descripcion'];
		if(!empty($_REQUEST['fecha'])) {
			$this->fecha = $_POST['fecha'];
			$this->fecha = explode("/", $this->fecha);
		}
		if(!empty($_REQUEST['hora_inicio'])) $this->hora_inicio = $_POST['hora_inicio'];
		if(!empty($_REQUEST['hora_fin'])) $this->hora_fin = $_POST['hora_fin'];
		if(!empty($_REQUEST['categoria'])) $this->categoria = $_POST['categoria'];
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
	}
	
	function contenido_evento() {
		$this->consulta->conecta();
		$this->consulta->tabla("calendario");
		$this->consulta->datos("cal_titulo, CONCAT_WS('/', cal_dia, cal_mes, cal_year) as cal_fecha, cal_hora_inicio, cal_hora_fin, cal_descripcion, cat_id");
		$this->consulta->opciones("WHERE cal_id  = '$this->id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		$this->consulta->desconecta();
	}
	
	function carga_categorias() {
		$this->consulta->tabla("categorias");
		$this->consulta->datos("cat_id, cat_titulo");
		$this->consulta->opciones(NULL);
		$this->consulta->leer_datos();
		$this->categorias = $this->consulta->array_asociativo();
		$this->consulta->libera();
	}
	
	function crear() {		
		$this->pintar_form(6);
	}
	
	function editar() {
		$this->consulta->conecta();
		$this->contenido_evento();
		$this->pintar_form(7);
	}
	
	function pintar_form($a) {
		$this->carga_categorias();
		if(!empty($this->categorias)) {
			echo '<form action="calendario.php?op='.$a.'&amp;id='.$this->id.'" id="calendario" class="calendario" name="calendaro" method="post">';
			echo '<label for="titulo">Título del evento: </label> <input name="titulo" id="titulo" type="text" size="60" value="';
			if(!empty($this->resultados)) echo $this->resultados[0]['cal_titulo'];
			echo '" tabindex="1" /><span id="error-titulo"></span>';
			echo '<p><label for="fecha">Fecha del evento: </label> <input id="fecha" name="fecha" type="text" size="10" value="';
			if(!empty($this->resultados)) echo $this->resultados[0]['cal_fecha'];
			echo '" tabindex="2" /></span>';
			echo ' <label for="hora_inicio">Hora de inicio del evento: </label> <input id="hora_inicio" name="hora_inicio" type="text" size="10" value="';
			if(!empty($this->resultados)) echo $this->resultados[0]['cal_hora_inicio'];
			echo '" tabindex="3" />';
			echo ' <label for="hora_fin">Hora final del evento: </label> <input id="hora_fin" name="hora_fin" type="text" size="10" value="';
			if(!empty($this->resultados)) echo $this->resultados[0]['cal_hora_fin'];
			echo '" tabindex="4" /><br /><span id="error-fecha"></span> <span id="error-hora_inicio"></span> <span id="error-hora_fin"></span><p>';
			echo '<select id="categoria" name="categoria">';
			echo '<option value="">Seleccione la categoría</option>';
			foreach($this->categorias as $v) {
				echo '<option value="'.$v['cat_id'].'"';
				if($v['cat_id'] == $this->resultados[0]['cat_id']) echo ' selected="selected"';
				echo '>'.$v['cat_titulo'].'</option>';
			}
			echo '</select><span id="error-categoria"></span>';
			echo '<p><label for="descripcion">Descripci&oacute;n</label><br />';
			echo '<textarea id="descripcion" class="tinymce" name="descripcion" cols="100" rows="20" tabindex="6" >';
			if(!empty($this->resultados)) echo $this->resultados[0]['cal_descripcion'];
			echo '</textarea><span id="error-descripcion"></span></p>';
			echo '<button id="button" class="submit" type="submit">Guardar</button> <button id="button" class="reset" type="reset">Limpiar</button>';
			echo '</form>';
		}
		else {
			$mensaje = new mensajes_globales("No existen categorias", 2);
			$mensaje->alerta();
		}
	}
	
	function listar($tipo = NULL) {
		$this->consulta->opciones("WHERE calendario.usr_id=usuarios.usr_id");
		switch($tipo) {
			case 1 : $this->consulta->tabla('calendario, usuarios');
					 $datos = 'calendario.cal_id, LOWER(calendario.cal_titulo), CONCAT_WS(\'/\', calendario.cal_dia, calendario.cal_mes, calendario.cal_year), CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)';
					 break;
			case 2 : $this->consulta->tabla('calendario, usuarios');
					 $datos = '*';
					 break;
			default : $tipo = 1;
			 		  $this->consulta->tabla('calendario, usuarios');
					  $datos = 'calendario.cal_id, LOWER(calendario.cal_titulo), CONCAT_WS(\'/\', calendario.cal_dia, calendario.cal_mes, calendario.cal_year), CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido)';
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
				echo "<th>Fecha</th>\n";				
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
					echo ucfirst($valor);
					echo "</td>";
				}
				echo '<td align="center">';
				echo '<a href="#calendario_editar" title="Editar" ';
				echo 'onclick="recargar(\'calendario\', \'t=1&amp;op=2&amp;id='.$this->resultados[$i]['cal_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="calendario.php?op=3&amp;s=1&amp;id='.$this->resultados[$i]['cal_id'].'&amp;width=800" class="thickbox" title="Ver">';
				echo '<img src="../imagenes/lupa_peq.png" width="18" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'calendario\', \'op=4&amp;id='.$this->resultados[$i]['cal_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="17" height="17" border="0"></a>';
				echo "</td>";
				echo "<tr>";
			}
			echo "<tbody>";
		}
		$this->consulta->libera();
	}
	
	function crear_evento() {
		if(!empty($this->titulo) and !empty($this->descripcion) and !empty($this->fecha) and !empty($this->categoria)) {
			$this->consulta->conecta();
			$this->consulta->tabla("calendario");
			$this->consulta->columnas("cal_titulo, cal_descripcion, cal_dia, cal_mes, cal_year, cal_hora_inicio, cal_hora_fin, cat_id, usr_id");
			$this->consulta->datos("'".$this->titulo."','".$this->descripcion."','".$this->fecha[0]."','".$this->fecha[1]."','".$this->fecha[2]."','".$this->hora_inicio."','".$this->hora_fin."','".$this->categoria."','".$this->usuario."'" );
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado el evento", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado el evento, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_evento() {
		if(!empty($this->titulo) and !empty($this->descripcion) and !empty($this->fecha) and !empty($this->categoria)) {
			$this->consulta->conecta();
			$this->consulta->tabla('calendario');
			$this->consulta->datos("cal_titulo='".$this->titulo."',cal_descripcion='".$this->descripcion."',cal_dia='".$this->fecha[0]."',cal_mes='".$this->fecha[1]."',cal_year='".$this->fecha[2]."',cal_hora_inicio='".$this->hora_inicio."',cal_hora_fin='".$this->hora_fin."',cat_id='".$this->categoria."',usr_id='".$this->usuario."'" );
			$this->consulta->opciones("WHERE cal_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado el evento", 2);
		}
		else $mensaje = new mensajes_globales("No se ha actualizado el evento, los campos requeridos estan vacios", 2);
		$mensaje->info();
		$this->listar();
	}
	
	function ver() {
		echo '<div id="contenido2" style="padding:5px; font-size: medium">';
		$this->consulta->conecta();
		$this->consulta->tabla("calendario, usuarios, categorias");
		$this->consulta->datos("calendario.cal_titulo, calendario.cal_descripcion, calendario.cal_hora_inicio, calendario.cal_hora_fin, categorias.cat_titulo, CONCAT_WS('/', calendario.cal_dia, calendario.cal_mes, calendario.cal_year) as fecha, CONCAT_WS(' ', usuarios.usr_nombre, usuarios.usr_apellido) as usuario");
		$this->consulta->opciones("WHERE calendario.cal_id = '$this->id' and calendario.usr_id=usuarios.usr_id and  calendario.cat_id=categorias.cat_id");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		echo "<br /><h1>".ucfirst($this->resultados[0]['cal_titulo'])."</h1>";
		echo $this->resultados[0]['cal_descripcion'];
		echo "<strong>Categoría del evento:</strong> ".$this->resultados[0]['cat_titulo'];
		echo "<p><strong>Fecha del evento:</strong> ".$this->resultados[0]['fecha']."</p>";
		echo "<strong>Hora de inicio:</strong> ".$this->resultados[0]['cal_hora_inicio'];
		echo "<p><strong>Hora de terminación:</strong> ".$this->resultados[0]['cal_hora_fin']."</p>";
		echo "<strong>Creación y/o modificación por:</strong> ".$this->resultados[0]['usuario'];
		echo '</div>';
		unset($this->resultados);
	}
	
	function eliminar() {
		$this->consulta->conecta();
		$this->consulta->tabla("calendario");
		$this->consulta->opciones("WHERE cal_id = '$this->id'");
		$this->consulta->eliminar();
		$this->consulta->ejecutar_query();
		$this->listar();
	}
}
?>