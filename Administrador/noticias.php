<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<script language="javascript" src="../scripts/noticias.js"></script>
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

	if(isset($_REQUEST['A'])) $activa = $_REQUEST['A'];
	else $activa = NULL;
	
	if(isset($_REQUEST['P'])) $permanente = $_REQUEST['P'];
	else $permanente = NULL;

	$operacion = new noticias($id, $activa, $permanente);

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
		case 6 : $operacion->crear_noticias();
				 break;
		case 7 : $operacion->guardar_noticias();
				 break;
		case 8 : $operacion->activar();
				 break;
		case 9 : $operacion->permanente();
				 break;
	}
}

class noticias{
	var $id;
	var $titulo;
	var $texto;
	var $activa;
	var $permanente;
	var $usuario;
	var $nom_usuario;
	var $tipo;
	var $consulta;
	var $resultados;
	var $tresultados;
	var $pag;
	var $limite;
	
	function __construct($id, $activa, $permanente){
		unset($this->resultados);
		unset($this->tresultados);
		unset($this->categoria);
		unset($this->categorias);
		unset($this->consulta);
		if(!empty($id)) $this->id = $id;
		if(!empty($_REQUEST['titulo'])) $this->titulo = $_POST['titulo'];
		if(!empty($_REQUEST['texto'])) $this->texto = $_POST['texto'];
		if(!empty($_REQUEST['A'])) $this->activa = $activa;
		if(!empty($_REQUEST['P'])) $this->permanente = $permanente;
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
	}
	
	function contenido_noticia() {
		$this->consulta->conecta();
		$this->consulta->tabla("noticias");
		$this->consulta->datos("not_titulo, not_texto, not_fecha, not_activa, not_permanente");
		$this->consulta->opciones("WHERE not_id  = '$this->id'");
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
		$this->contenido_noticia();
		$this->pintar_form(7);
	}
	
	function pintar_form($a) {
			echo '<form action="noticias.php?op='.$a.'&amp;id='.$this->id.'" id="noticias" class="noticias" name="noticias" method="post">';
			echo '<label for="titulo">Título de la noticia (max. 36 caracteres): </label> <input name="titulo" id="titulo" type="text" size="60" value="';
			if(!empty($this->resultados)) echo $this->resultados[0]['not_titulo'];
			echo '" tabindex="1" onkeyup="contar(\'titulo\')" /> <span id="titulo-contar"></span><span id="error-titulo"></span><span id="titulo-contar"></span>';
			//ACTIVA
			echo '<p><label for="A">La noticia debe activarse de una vez: </label> Si <input id="A" name="A" type="radio" value="S"';
			if(empty($this->resultados) || $this->resultados[0]['not_activa'] == "S") echo ' checked';
			echo ' tabindex="2" />';
			echo ' No <input id="A" name="A" type="radio" value="N"';
			if(!empty($this->resultados) and $this->resultados[0]['not_activa'] == "N") echo ' checked';
			echo ' tabindex="3" /></p><span id="error-A"></span>';
			//PERMANENTE
			echo '<label for="P">La noticia es permanente: </label> Si <input id="P" name="P" type="radio" value="S"';
			if(!empty($this->resultados) and $this->resultados[0]['not_permanente'] == "N") echo ' checked';
			echo ' tabindex="4" />';
			echo ' No <input id="P" name="P" type="radio" value="N"';
			if(empty($this->resultados) || $this->resultados[0]['not_permanente'] == "N") echo ' checked';
			echo ' tabindex="5" /><span id="error-P"></span>';
			//TEXTO
			echo '<p><label for="texto">Texto de la noticia (Max. 232 caracteres):</label><br /><textarea id="texto" name="texto" cols="80" rows="3" tabindex="6" onkeyup="contar(\'texto\')" >';
			if(!empty($this->resultados)) echo $this->resultados[0]['not_texto'];
			echo '</textarea> <span id="texto-contar"></span><span id="error-texto"></span></p>';
			echo '<button id="button" class="submit" type="submit">Guardar</button> <button id="button" class="reset" type="reset">Limpiar</button>';
			echo '</form>';
	}
	
	function listar($tipo = NULL) {
		$this->consulta->opciones("WHERE noticias.usr_id=usuarios.usr_id");
		switch($tipo) {
			case 1 : $this->consulta->tabla('noticias, usuarios');
					 $datos = 'noticias.not_id, LOWER(noticias.not_titulo), not_fecha, not_activa, not_permanente, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido) as usuario';
					 break;
			case 2 : $this->consulta->tabla('noticias, usuarios');
					 $datos = '*';
					 break;
			default : $tipo = 1;
			 		  $this->consulta->tabla('noticias, usuarios');
					  $datos = 'noticias.not_id, LOWER(noticias.not_titulo), not_fecha, not_activa, not_permanente, CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellido) as usuario';
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
				echo "<th>Activa</th>\n";
				echo "<th>Permanente</th>\n";
				echo "<th>Usuario</th>\n";
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
					if($clave == "not_activa") {
						if($valor == "S") {
							echo '<a href="#noticias_activar" title="Activar/Desactivar" ';
				echo 'onclick="recargar(\'noticias\', \'t=1&amp;op=8&amp;id='.$this->resultados[$i]['not_id'].'&amp;A='.$valor.'&amp;pag='.$this->pag.'\', \'contenido2\')">';
				echo '<img src="../imagenes/ok.png" width="22" height="22" border="0"></a>';
						}
						else {
							echo '<a href="#noticias_activar" title="Activar/Desactivar" ';
				echo 'onclick="recargar(\'noticias\', \'t=1&amp;op=8&amp;id='.$this->resultados[$i]['not_id'].'&amp;A='.$valor.'&amp;pag='.$this->pag.'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="22" height="22" border="0"></a>';
						}
					}
					elseif($clave == "not_permanente") {
						if($valor == "S") {
							echo '<a href="#noticias_activar" title="Activar/Desactivar" ';
				echo 'onclick="recargar(\'noticias\', \'t=1&amp;op=9&amp;id='.$this->resultados[$i]['not_id'].'&amp;P='.$valor.'&amp;pag='.$this->pag.'\', \'contenido2\')">';
				echo '<img src="../imagenes/ok.png" width="22" height="22" border="0"></a>';
						}
						else {
							echo '<a href="#noticias_activar" title="Activar/Desactivar" ';
				echo 'onclick="recargar(\'noticias\', \'t=1&amp;op=9&amp;id='.$this->resultados[$i]['not_id'].'&amp;P='.$valor.'&amp;pag='.$this->pag.'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="22" height="22" border="0"></a>';
						}
					}
					else echo ucfirst($valor);
					echo "</td>";
				}
				echo '<td align="center">';
				echo '<a href="#noticias_editar" title="Editar" ';
				echo 'onclick="recargar(\'noticias\', \'t=1&amp;op=2&amp;id='.$this->resultados[$i]['not_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="noticias.php?op=3&amp;s=1&amp;id='.$this->resultados[$i]['not_id'].'&amp;width=800" class="thickbox" title="Ver">';
				echo '<img src="../imagenes/lupa_peq.png" width="18" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'noticias\', \'op=4&amp;id='.$this->resultados[$i]['not_id'].'&amp;pag='.$this->pag.'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="17" height="17" border="0"></a>';
				echo "</td>";
				echo "<tr>";
			}
			echo "<tbody>";
		}
		$this->consulta->libera();
	}
	
	function crear_noticias() {
		if(!empty($this->titulo) and !empty($this->texto) and !empty($this->activa) and !empty($this->permanente)) {
			$this->consulta->conecta();
			$this->consulta->tabla("noticias");
			$this->consulta->columnas("not_titulo, not_texto, not_fecha, not_activa, not_permanente, usr_id");
			$this->consulta->datos("'".$this->titulo."','".$this->texto."','".date("Y-m-d")."','".$this->activa."','".$this->permanente."','".$this->usuario."'" );
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado la noticia", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado la noticia, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_noticias() {
		if(!empty($this->titulo) and !empty($this->texto) and !empty($this->activa) and !empty($this->permanente)) {
			$this->consulta->conecta();
			$this->consulta->tabla('noticias');
			$this->consulta->datos("not_titulo='".$this->titulo."',not_texto='".$this->texto."',not_fecha='".date("Y-m-d")."',not_activa='".$this->activa."',not_permanente='".$this->permanente."',usr_id='".$this->usuario."'" );
			$this->consulta->opciones("WHERE not_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado la noticia", 2);
		}
		else $mensaje = new mensajes_globales("No se ha actualizado la noticia, los campos requeridos estan vacios", 2);
		$mensaje->info();
		$this->listar();
	}
	
	function ver() {
		echo '<div id="contenido2" style="padding:5px; font-size: medium">';
		$this->consulta->conecta();
		$this->consulta->tabla("noticias, usuarios");
		$this->consulta->datos("noticias.not_titulo, noticias.not_texto, noticias.not_activa, noticias.not_permanente, noticias.not_fecha, CONCAT_WS(' ', usuarios.usr_nombre, usuarios.usr_apellido) as usuario");
		$this->consulta->opciones("WHERE noticias.not_id = '$this->id' and noticias.usr_id=usuarios.usr_id");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		echo "<br /><h1>".ucfirst($this->resultados[0]['not_titulo'])."</h1>";
		echo $this->resultados[0]['not_texto'];
		echo "<p><strong>Fecha de la noticia:</strong> ".$this->resultados[0]['not_fecha']."</p>";
		echo "<strong>Activa:</strong> ";
		if($this->resultados[0]['not_activa'] == "S") echo '<img src="../imagenes/ok.png" width="22" height="22" border="0">';
		else echo '<img src="../imagenes/delete.png" width="22" height="22" border="0">';
		echo " <strong>Permanente:</strong> ";
		if($this->resultados[0]['not_permanente'] == "S") echo '<img src="../imagenes/ok.png" width="22" height="22" border="0">';
		else echo '<img src="../imagenes/delete.png" width="22" height="22" border="0">';
		echo "<p><strong>Creación y/o modificación por:</strong> ".$this->resultados[0]['usuario']."</p>";
		echo '</div>';
		unset($this->resultados);
	}
	
	function eliminar() {
		$this->consulta->conecta();
		$this->consulta->tabla("noticias");
		$this->consulta->opciones("WHERE not_id = '$this->id'");
		$this->consulta->eliminar();
		$this->consulta->ejecutar_query();
		$this->listar();
	}
	
	function activar() {
		if(!empty($this->activa)) {
			if($this->activa == "S") $this->activa = "N";
			else $this->activa = "S";
			$this->consulta->conecta();
			$this->consulta->tabla('noticias');
			$this->consulta->datos("not_activa='".$this->activa."' ,usr_id='".$this->usuario."'" );
			$this->consulta->opciones("WHERE not_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
		}
		else {
			$mensaje = new mensajes_globales("Los campos requeridos estan vacios", 2);
			$mensaje->info();
		}
		$this->listar();
	}
	
	function permanente() {
		if(!empty($this->permanente)) {
			if($this->permanente == "S") $this->permanente = "N";
			else $this->permanente = "S";
			$this->consulta->conecta();
			$this->consulta->tabla('noticias');
			$this->consulta->datos("not_permanente='".$this->permanente."' ,usr_id='".$this->usuario."'" );
			$this->consulta->opciones("WHERE not_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
		}
		else {
			$mensaje = new mensajes_globales("Los campos requeridos estan vacios", 2);
			$mensaje->info();
		}
		$this->listar();
	}
}
?>