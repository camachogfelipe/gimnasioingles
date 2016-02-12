<?php
session_start();
if(isset($_SESSION['usuario']) and isset($_SESSION['nombre'])) {
	if(isset($_REQUEST['s'])) $scripts = $_REQUEST['s'];
	else $scripts = 0;
?>
	<script language="javascript" src="../scripts/usuarios.js"></script>
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
	else $opcion = 3;

	if(isset($_REQUEST['id'])) $id = $_REQUEST['id'];
	else $id = NULL;

	$operacion = new usuarios($id);

	switch($opcion) {
		case 1 : $operacion->crear();
				 break;
		case 2 : $operacion->editar();
				 break;
		case 3 : $operacion->ver();
				 break;
		case 4 : $operacion->activar();
				 break;
		case 5 : $operacion->eliminar();
				 break;
		case 6 : $operacion->listar();
				 break;
		case 7 : $operacion->crear_usuario();
				 break;
		case 8 : $res = $operacion->guardar_usuario();
				 break;
		case 9 : $operacion->cambio_clave();
				 break;
		case 10 :$operacion->guarda_cambio_clave();
				 break;
	}
}

class usuarios{
	//variables de nuevo usuario o edición de usuario
	var $id;
	var $nombre;
	var $apellidos;
	var $correo;
	var $login;
	var $tipo_usuario;
	var $activar;
	var $institucional;
	var $calendario;
	var $galeria;
	var $niveles;
	var $noticias;
	//passwords
	var $clave1;
	var $clave2;
	//variables de usuario que esta trabajando
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
		if(!empty($_REQUEST['apellidos'])) $this->apellidos = $_POST['apellidos'];
		if(!empty($_REQUEST['correo'])) $this->correo = $_POST['correo'];
		if(!empty($_REQUEST['login'])) $this->login = $_POST['login'];
		if(!empty($_REQUEST['tipo_usuario'])) $this->tipo_usuario= $_POST['tipo_usuario'];
		if(!empty($_REQUEST['activar'])) $this->activar = $_POST['activar'];
		if(!empty($_REQUEST['institucional'])) $this->institucional = $_POST['institucional'];
		if(!empty($_REQUEST['calendario'])) $this->calendario = $_POST['calendario'];
		if(!empty($_REQUEST['galeria'])) $this->galeria = $_POST['galeria'];
		if(!empty($_REQUEST['niveles'])) $this->niveles = $_POST['niveles'];
		if(!empty($_REQUEST['noticias'])) $this->noticias = $_POST['noticias'];
		if(!empty($_REQUEST['A'])) $this->activo = $_REQUEST['A'];
		else $this->activo = NULL;
		$this->usuario = $_SESSION['usr_id'];
		$this->nom_usuario = $_SESSION['nombre'];
		if(!empty($_REQUEST['pag'])) $this->pag = $_REQUEST['pag'];
		else $this->pag = 1;
		$this->consulta = new BDManejo($this->pag);
		$this->consulta->tabla("usuarios");
	}
	
	function contenido_usuarios() {
		$this->consulta->datos("*");
		$this->consulta->opciones("WHERE usr_id = '$this->id'");
		$this->consulta->leer_datos();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->libera();
		$this->consulta->desconecta();
	}
	
	function crear() {
		$this->pintar_form(7);
	}
	
	function editar() {
		echo "cargo editar";
		$this->consulta->conecta();
		$this->contenido_usuarios();
		echo "cargo la información del usuario";
		$this->pintar_form(8);
	}
	
	function pintar_form($a) {
		echo '<form action="usuarios.php?op='.$a.'&id='.$this->id.'" class="usuarios" name="usuarios" method="post">';
		echo '<table width="720px" border="0" cellspacing="3" cellpadding="0" align="center">';
		echo '<tr>';
		echo '<td width="38%">Nombre</td><td width="62%">';
		echo '<input name="nombre" type="text" id="nombre" tabindex="1" size="50" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['usr_nombre'];
		echo '">';
		echo '<span id="error-nombre"></span></td>';
		echo '</tr><tr>';
		echo '<td>apellidos</td>';
		echo '<td><input name="apellidos" type="text" id="apellidos" tabindex="2" size="50" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['usr_apellido'];
		echo '">';
		echo '<span id="error-apellidos"></span></td>';
		echo '</tr><tr>';
		echo '<td>Correo electrónico</td><td><input name="correo" type="text" id="correo" tabindex="3" size="50" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['usr_email'];
		echo '">';
		echo '<span id="error-correo"></span></td>';
		echo '</tr><tr>';
		echo '<td>Login</td><td><input name="login" type="text" id="login" tabindex="4" size="20" value="';
		if(!empty($this->resultados)) echo $this->resultados[0]['usr_login'];
		echo '">';
		echo '<span id="error-login"></span></td>';
		echo '</tr><tr>';
		echo '<td>Tipo de usuario</td><td>';
		echo '<label><input id="tipo_usuario" type="radio" name="tipo_usuario" value="A" tabindex="5" onclick="selecciona(\'A\')"';
		if(empty($this->resultados) || $this->resultados[0]['usr_tipo_usuario'] == "A") echo ' checked="checked"';
		echo '>Administrador</label> ';
		echo '<label><input id="tipo_usuario" type="radio" name="tipo_usuario" tabindex="6" value="NA" onclick="selecciona(\'NA\')"';
		if(!empty($this->resultados) and $this->resultados[0]['usr_tipo_usuario'] == "NA") echo ' checked="checked"';
		echo '>No Administrador</label>';
		echo '<span id="error-tipo_usuario"></span></td>';
		echo '</tr><tr>';
		echo '<td>Activar inmediatamente</td><td>';
		echo '<label><input id="activar" class="1" type="radio" name="activar" value="S" tabindex="7"';
		if(empty($this->resultados) || $this->resultados[0]['usr_activo'] == "S") echo ' checked="checked"';
		echo '>Si</label> ';
		echo '<label><input id="activar" class="2" type="radio" name="activar" value="N" tabindex="8"';
		if(!empty($this->resultados) and $this->resultados[0]['usr_activo'] == "N") echo ' checked="checked"';
		echo '>No</label>';
		echo ' <span id="error-activar"></span></td>';
		echo '</tr><tr>';
		echo '<td>Acceso a sección institucional</td><td>';
		echo '<label><input id="institucional" class="1" type="radio" name="institucional" value="S" tabindex="9"';
		if(empty($this->resultados) || $this->resultados[0]['usr_institucional'] == "S") echo ' checked="checked"';
		echo '>Si</label> ';
		echo '<label><input id="institucional" class="2" type="radio" name="institucional" value="N" tabindex="10"';
		if(!empty($this->resultados) and $this->resultados[0]['usr_institucional'] == "N") echo ' checked="checked"';
		echo '>No</label>';
		echo ' <span id="error-institucional"></span></td>';
		echo '</tr><tr>';
		echo '<td>Acceso a sección calendario</td><td>';
		echo '<label><input id="calendario" class="1" type="radio" name="calendario" value="S" tabindex="11"';
		if(empty($this->resultados) || $this->resultados[0]['usr_calendario'] == "S") echo ' checked="checked"';
		echo '>Si</label> ';
		echo '<label><input id="calendario" class="2" type="radio" name="calendario" value="N" tabindex="12"';
		if(!empty($this->resultados) and $this->resultados[0]['usr_calendario'] == "N") echo ' checked="checked"';
		echo '>No</label>';
		echo ' <span id="error-calendario"></span></td>';
		echo '</tr><tr>';
		echo '<td>Acceso a sección galeria</td><td>';
		echo '<label><input id="galeria" class="g1" type="radio" name="galeria" value="S" tabindex="13"';
		if(empty($this->resultados) || $this->resultados[0]['usr_galeria'] == "S") echo ' checked="checked"';
		echo '>Si</label> ';
		echo '<label><input id="galeria" class="g2" type="radio" name="galeria" value="N" tabindex="14"';
		if(!empty($this->resultados) and $this->resultados[0]['usr_galeria'] == "N") echo ' checked="checked"';
		echo '>No</label>';
		echo ' <span id="error-galeria"></span></td>';
		echo '</tr><tr>';
		echo '<td>Acceso a sección niveles</td><td>';
		echo '<label><input id="niveles" class="1" type="radio" name="niveles" value="S" tabindex="15"';
		if(empty($this->resultados) || $this->resultados[0]['usr_niveles'] == "S") echo ' checked="checked"';
		echo '>Si</label> ';
		echo '<label><input id="niveles" class="2" type="radio" name="niveles" value="N" tabindex="16"';
		if(!empty($this->resultados) and $this->resultados[0]['usr_niveles'] == "N") echo ' checked="checked"';
		echo '>No</label>';
		echo ' <span id="error-niveles"></span></td>';
		echo '</tr><tr>';
		echo '<td>Acceso a sección noticias</td><td>';
		echo '<label><input id="noticias" class="1" type="radio" name="noticias" value="S" tabindex="17"';
		if(empty($this->resultados) || $this->resultados[0]['usr_noticias'] == "S") echo ' checked="checked"';
		echo '>Si</label> ';
		echo '<label><input id="noticias" class="2" type="radio" name="noticias" value="N" tabindex="18"';
		if(!empty($this->resultados) and $this->resultados[0]['usr_noticias'] == "N") echo ' checked="checked"';
		echo '>No</label>';
		echo ' <span id="error-noticias"></span></td>';
		echo '</tr><tr>';
		echo '<td align="right"><button id="button" class="submit" type="submit" tabindex="19">Guardar</button> </td>';
		echo '<td align="left"> <button id="button" class="reset" type="reset" tabindex="20">Limpiar</button></td>';
		echo '</tr>';
		echo '</table>';
		echo '</form>';
	}
	
	function listar($tipo = NULL) {
		switch($tipo) {
			case 1 : $datos = 'usr_id, CONCAT_WS(\' \', usr_nombre, usr_apellido) AS usr_nombre, usr_email, usr_fecha_creacion, usr_fecha_ultimo_acceso, usr_tipo_usuario, usr_activo, CONCAT_WS(\',\', usr_institucional, usr_calendario, usr_galeria, usr_niveles, usr_noticias) AS usr_permisos';
					 break;
			case 2 : $datos = '*';
					 break;
			default : $tipo = 1;
					  $datos = 'usr_id, CONCAT_WS(\' \', usr_nombre, usr_apellido) AS usr_nombre, usr_email, usr_fecha_creacion, usr_fecha_ultimo_acceso, usr_tipo_usuario, usr_activo, CONCAT_WS(\',\', usr_institucional, usr_calendario, usr_galeria, usr_niveles, usr_noticias) AS usr_permisos';
					  break;
		}
		$this->consulta->conecta();
		$this->consulta->datos($datos);
		$this->consulta->opciones("");
		$this->consulta->leer_datos();
		//$this->consulta->mostrar_sql();
		$this->resultados = $this->consulta->array_asociativo();
		$this->consulta->desconecta();
		if(empty($this->resultados)) {
			$mensaje = new mensajes_globales("No se encontraron resultados", 2); 
			$mensaje->info();
		}
		else {
			echo "<p>Se encontraron ".$this->tresultados=$this->consulta->total_resultados()." resultados</p>";
			echo '<table width="100%" cellpadding="2" cellspacing="0" align="center" style="font-size: 90%">';
			echo "<thead>\n";
			if($tipo == 1) {
				echo "<th>Id</th>\n";
				echo "<th>Nombre completo</th>\n";
				echo "<th>Correo electrónico</th>\n";
				echo "<th>Fecha creado</th>\n";
				echo "<th>Fecha último acceso</th>\n";
				echo "<th>Tipo de usuario</th>\n";
				echo "<th>Activo</th>\n";
				echo "<th>Permisos</th>\n";
				echo "<th>Acciones</th>\n";
			}
			else {
				foreach($this->resultados[0] as $clave=>$valor) {
					echo "<th>".$clave."</th>\n";
				}
			}
			echo "</thead>\n";
			echo '<tbody>';
			for($i=0; $i<$this->tresultados; $i++) {
				echo "<tr>";
				$res = $this->resultados[$i];
				foreach($res as $clave=>$valor) {
					echo '<td align="center">';
					if($clave == "usr_activo") {
						echo '<a href="#activar_usuario" onclick="recargar(\'usuarios\', \'op=4&id='.$this->resultados[$i]['usr_id'].'&A='.$valor.'&pag='.$this->pag.'\', \'contenido2\')">';
						echo '<img src="../imagenes/';
						if($valor == "S") echo 'ok.png';
						else echo 'delete.png';
						echo '" width="16" height="16" border="0" align="absmiddle"></a>';
					}
					else echo ucfirst($valor);
					echo "</td>";
				}
				echo '<td align="center">';
				echo '<a href="#usuarios_editar_articulo" title="Editar" ';
				echo 'onclick="recargar(\'usuarios\', \'op=2&amp;id='.$this->resultados[$i]['usr_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/checked.png" width="15" height="17" border="0"></a>';
				echo ' <a href="#Eliminar" title="Eliminar"';
				echo 'onclick="eliminar(\'usuarios\', \'op=5&amp;id='.$this->resultados[$i]['usr_id'].'\', \'contenido2\')">';
				echo '<img src="../imagenes/delete.png" width="17" height="17" border="0"></a>';
				echo "</td>";
				echo "<tr>";
			}
			echo "<tbody>";
		}
	}
	
	function crear_usuario() {
		if(!empty($this->nombre) and !empty($this->apellidos) and !empty($this->correo) and !empty($this->login) and !empty($this->tipo_usuario) and !empty($this->activar) and !empty($this->institucional) and !empty($this->calendario) and !empty($this->galeria) and !empty($this->niveles) and !empty($this->noticias)) {
			$this->consulta->conecta();
			$this->consulta->tabla("usuarios");
			$this->consulta->columnas("usr_nombre, usr_apellido, usr_email, usr_login, usr_tipo_usuario, usr_activo, usr_institucional, usr_calendario, usr_galeria, usr_niveles, usr_noticias, usr_fecha_creacion, usr_clave");
			$this->consulta->datos("'".$this->nombre."', '".$this->apellidos."', '".$this->correo."', '".$this->login."', '".$this->tipo_usuario."', '".$this->activar."', '".$this->institucional."', '".$this->calendario."', '".$this->galeria."', '".$this->niveles."', '".$this->noticias."', '".date("Y-m-d")."', '".md5('GIK')."'");
			$this->consulta->insert();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha creado el usuario", 2);
		}
		else $mensaje = new mensajes_globales("No se ha creado el usuario, los campos requeridos estan vacios", 2); 
		$mensaje->info();
		$this->listar();
	}
	
	function guardar_usuario() {
		if(!empty($this->nombre) and !empty($this->apellidos) and !empty($this->correo) and !empty($this->login) and !empty($this->tipo_usuario) and !empty($this->activar) and !empty($this->institucional) and !empty($this->calendario) and !empty($this->galeria) and !empty($this->niveles) and !empty($this->noticias)) {
			$this->consulta->conecta();
			$this->consulta->tabla('usuarios');
			$this->consulta->datos("usr_nombre='".$this->nombre."', usr_apellido='".$this->apellidos."', usr_email='".$this->correo."', usr_login='".$this->login."', usr_tipo_usuario='".$this->tipo_usuario."', usr_activo='".$this->activar."', usr_institucional='".$this->institucional."', usr_calendario='".$this->calendario."', usr_galeria='".$this->galeria."', usr_niveles='".$this->niveles."', usr_noticias='".$this->noticias."'");
			$this->consulta->opciones("WHERE usr_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
			$mensaje = new mensajes_globales("Se ha actualizado el usuario<br />Las modificaciones tendran efecto en el inicio de la siguiente sesión", 2);
		}
		else $mensaje = new mensajes_globales("No se ha actualizado el usuario, los campos requeridos estan vacios", 2);
		$this->consulta->opciones(NULL);
		$mensaje->info();
		$this->listar();
	}
	
	function ver() {
		echo '<div id="contenido2" style="padding:5px; font-size: medium">';
		$this->consulta->conecta();
		$this->consulta->tabla("usuarios");
		$this->consulta->datos("*");
		$this->consulta->opciones("WHERE usr_id = '$this->id'");
		$this->consulta->leer_datos();
		$this->consulta->mostrar_sql();
		$this->resultados = $this->consulta->array_asociativo();
		echo "<pre>".print_r($this->resultados);echo "<pre>";exit();
		echo "<br /><h1>".$this->resultados[0]['inst_titulo']."</h1>";
		echo $this->resultados[0]['inst_descripcion'];
		echo "<strong>Fecha de creación:</strong> ".$this->resultados[0]['inst_fecha_creado'];
		if($this->resultados[0]['inst_fecha_modificado'] == "0000-00-00") echo "<p><strong>Este árticulo nunca ha sido modificado</strong></p>";
		else echo "<p><strong>Fecha de la última modificación:</strong> ".$this->resultados[0]['inst_fecha_modificado']."</p>";
		if($this->resultados[0]['inst_archivo_pdf'] == "0000-00-00") echo "<strong>Este árticulo no tiene un archivo pdf asociado</strong>";
		else echo "<strong>Archivo pdf asociado:</strong> ".$this->resultados[0]['inst_archivo_pdf'];
		echo "<p><strong>Creación y/o modificación por:</strong> ".$this->resultados[0]['CONCAT_WS(\' \', usuarios.usr_nombre, usuarios.usr_apellidos)']."</p>";
		echo '</div>';
	}
	
	function activar() {
		if(!empty($this->activo) and !empty($this->id)) {
			if($this->activo == "S") $this->activo = "N";
			else $this->activo = "S";
			$this->consulta->conecta();
			$this->consulta->tabla('usuarios');
			$this->consulta->datos("usr_activo='".$this->activo."'");
			$this->consulta->opciones("WHERE usr_id = '".$this->id."'");
			$this->consulta->actualiza();
			$this->consulta->ejecutar_query();
			$this->consulta->desconecta();
		}
		else {
			$mensaje = new mensajes_globales("Los campos requeridos estan vacios", 2);
			$mensaje->info();
		}
		$this->consulta->opciones(NULL);
		$this->listar();
	}
	
	function eliminar() {
		if($this->id != 1) {
			$this->consulta->conecta();
			$this->consulta->tabla("usuarios");
			$this->consulta->opciones("WHERE usr_id = '$this->id'");
			$this->consulta->eliminar();
			$this->consulta->ejecutar_query();
			$mensaje = new mensajes_globales("El usuario se elimino con éxito",2);
		}		
		else $mensaje = new mensajes_globales("El usuario no puede ser borrado", 2);
		$mensaje->info();
		$this->listar();
	}
}
?>