<?php defined( '_GIA' ) or die("Accesos restringido solo para usuarios"); ?>
	<ul id="menu_usuarios">
    		<li><a href="#institucional">Institucional</a>
            	<ul class="submenu">
                	<li><a href="#institucional_crear" onclick="recargar('institucional', 'op=1', 'contenido2')">Crear nuevo articulo de institucional</a></li>
                    <li><a href="#institucional_ver" onclick="recargar('institucional', 'op=6', 'contenido2')">Ver items institucionales</a></li>
                </ul>
            </li>
	        <li><a href="#Calendario">Calendario</a>
            	<ul class="submenu">
                	<li><a href="#calendario_nueva_categoria" onclick="recargar('categorias', 'op=1', 'contenido2')">Crear categoria</a></li>
                    <li><a href="#calendario_ver_categoria" onclick="recargar('categorias', 'op=4', 'contenido2')">Ver categorias</a></li>
                	<li><a href="#Calendario_nuevo" onclick="recargar('calendario', 'op=1', 'contenido2')">Crear un nuevo evento</a></li>
                    <li><a href="#Calendario_ver" onclick="recargar('calendario', 'op=5', 'contenido2')">Ver eventos del calendario</a></li>
                </ul>
            </li>
	        <li><a href="#Galerias">Galerias</a>
            	<ul class="submenu">
                	<li><a href="#Galerias_nueva" onclick="recargar('galerias', 'op=1', 'contenido2')">Crear una nueva galeria</a></li>
                    <li><a href="#Galerias_ver" onclick="recargar('galerias', 'op=5', 'contenido2')">Ver galerias</a></li>
                </ul>
            </li>
	        <li><a href="#Niveles">Niveles</a>
            	<ul class="submenu">
                	<li><a href="#Niveles_nuevo" onclick="recargar('niveles', 'op=1', 'contenido2')">Crear un nuevo nivel</a></li>
                    <li><a href="#Niveles_ver" onclick="recargar('niveles', 'op=5', 'contenido2')">Ver niveles</a></li>
                </ul>
            </li>
	        <li><a href="#Alianzas">Alianzas</a>
            	<ul class="submenu">
                	<li><a href="#Alianzas_nueva" onclick="recargar('alianzas', 'op=1', 'contenido2')">Crear una nueva alianza</a></li>
                    <li><a href="#Alianzas_ver" onclick="recargar('alianzas', 'op=5', 'contenido2')">Ver alianzas</a></li>
                </ul>
            </li>
    	    <li><a href="#Noticias">Noticias</a>
            	<ul class="submenu">
                	<li><a href="#Noticias_nueva" onclick="recargar('noticias', 'op=1', 'contenido2')">Crear una noticia</a></li>
                    <li><a href="#Noticias_ver" onclick="recargar('noticias', 'op=5', 'contenido2')">Ver noticias</a></li>
                </ul>
            </li>
	        <li><a href="#Usuarios">Usuarios</a>
            	<ul class="submenu">
                	<li><a href="#Usuarios_nuevo" onclick="recargar('usuarios', 'op=1', 'contenido2')">Crear un nuevo usuario</a></li>
                    <li><a href="#Usuarios_ver" onclick="recargar('usuarios', 'op=6', 'contenido2')">Ver usuarios</a></li>
                    <li><a href="#Usuarios_ver" onclick="recargar('usuarios', 'op=9', 'contenido2')">Cambiar su contrasena</a></li>
                </ul>
			</li>
    	    <li><a href="#Configuracion" onclick="recargar('configuracion', 'op=1', 'contenido2')">Configuracion</a></li>
        	<li class="salir"><a href="?salir"><img src="../imagenes/logout24.png" width="24" height="24" border="0" align="absmiddle" /> Salir</a></li>
    </ul>
	<div id="contenido2">
	</div>