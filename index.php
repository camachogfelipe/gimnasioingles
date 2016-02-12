<?php
/*if(!isset($_REQUEST['NC'])) {
	require_once("index_construccion.php");
	exit();
}*/
error_reporting(E_ALL & ~E_NOTICE);
define( '_GI', 1 );
require_once("clases/alianzas.class.php");
require_once("clases/noticias.class.php");
require_once("clases/calendario.class.php");
?>
<!DOCTYPE html>
<html lang="es-CO">
<head>
<link rel="shortcut icon" type="image/x-icon" href="imagenes/favicon.ico">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name='Author' content='COgroup SAS Bogotá-Colombia' />
<meta name='language' content='es' />
<meta name='copyright' content='Copyright Grupo educativo formar sas 2013 - Todos los derechos reservados' />
<meta name="title" content="Jardín Gimnasio Inglés" />
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<meta name="Keywords" content="educación, jardín, niños, baby, education, childs, formation, formación"/>
<meta name="Description" content="Somos una institución educativa de calidad, que cuenta con el respaldo de 22 años de trayectoria, apasionada por el desarrollo del potencial cognitivo, afectivo, espiritual y comunicativo de los niños, al tiempo que forma a las familias y a sus colaboradores." />
<meta name="DC.title" content="Jardín Gimnasio Inglés" />
<meta http-equiv="DC.Description" content="Somos una institución educativa de calidad, que cuenta con el respaldo de 22 años de trayectoria, apasionada por el desarrollo del potencial cognitivo, afectivo, espiritual y comunicativo de los niños, al tiempo que forma a las familias y a sus colaboradores." />
<meta name="Revisit" content="5 days" />
<meta name="Distribution" content="global"/>
<meta name="Robots" content="all"/>
<title>Gimnasio Ingles</title>
<link rel="stylesheet" href="scripts/lytebox/lytebox.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="skins/tango/skin.css" />
<link rel="stylesheet" type="text/css" href="noticias.css" />
<link href="gik.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="scripts/flowplayer/flowplayer-3.2.8.min.js"></script>
<script language="javascript" src="scripts/jquery-1.7.min.js"></script>
<script language="javascript" src="scripts/jquery.corner.js"></script>
<script type="text/javascript" src="scripts/jquery.jcarousel.min.js"></script>
<script type="text/javascript" language="javascript" src="scripts/lytebox/lytebox.js"></script>
<script type="text/javascript" language="javascript" src="scripts/jquery.nicescroll.js"></script>
<script language="JavaScript" src="scripts/gik.js"></script>
</head>

<body>
<div id="cabezote"><img src="imagenes/cabezote.png" width="1024" height="200" /></div>
<div id="load"><img src="imagenes/preload.gif" alt="" width="130" height="144" class="load" /></div>
<hr id="hrmenu" />
<div id="contenido">
	<div id="menu">
    	<li>
        	<a href=""><img src="imagenes/inicio1.png" data-hover="imagenes/inicio2.png" width="107" height="67" border="0" /></a>
		</li>
        <li>
        	<a href="#institucional" onclick="recargar('institucional', '', 'contenido2')"><img src="imagenes/institucional1.png" data-hover="imagenes/institucional2.png" width="153" height="67" border="0" /></a>
		</li>
        <li>
        	<a href="#niveles" onclick="recargar('servicios', '', 'contenido2')"><img src="imagenes/servicios1.png" data-hover="imagenes/servicios2.png" width="105" height="67" border="0" /></a>
		</li>
        <li>
        	<a href="#calendario" onclick="recargar('calendario', '', 'contenido2')"><img src="imagenes/calendario1.png"data-hover="imagenes/calendario2.png" width="121" height="67" border="0" /></a>
		</li>
        <li>
        	<a href="#galeria" onclick="recargar('galerias', '', 'contenido2')"><img src="imagenes/galeria1.png" data-hover="imagenes/galeria2.png" width="111" height="67" border="0" /></a>
		</li>
        <li>
        	<a href="#contacto" onclick="recargar('contacto', '', 'contenido2')"><img src="imagenes/contacto1.png" data-hover="imagenes/contacto2.png" width="116" height="67" border="0" /></a>
		</li>
    </div>
    <div id="contenido2">
	    <div id="div_index_izq_sup">
    		<h1>
        		<img src="imagenes/cuadrado_azul.png" width="33" height="33" align="texttop" /> 
        		Bienvenido
			</h1>
    	    <div style="width: 100%; height: 180px;" id="div_text_bienvenida">Bienvenido a un Jardín Infantil de calidad, que cuenta con el respaldo de más de 20 años de trayectoria, apasionado por el desarrollo del potencial cognitivo, afectivo, espiritual y comunicativo de tu hijo.<p>Creemos que cada niño es único e irrepetible, competente, valioso y con capacidades infinitas. Buscamos construir relaciones auténticas con las familias, que derivan en historias de vida que perduran por siempre. Nuestro equipo profesional es amigo de la innovación y con alta capacidad de  amar y crecer con tu hijo.</p>Únete a una experiencia grupal maravillosa, una pasión compartida, un esfuerzo de muchos.</div>
	    </div>
    	<div id="div_index_der_sup">
    		<div id="BannerNoticias"><?php $noticias = new noticias(); $noticias->muestra_contenido(); ?></div>
        <a href="http://portal.cibercolegios.com/index.php/ingresar"><img src="imagenes/ciberlogo.png" alt="" width="276" height="61" border="0" /></a></div>
    	<div id="div_index_cen_sup">
    		<a href="videos/gik_org.mp4" style="display:block;width:100%;height:250px; margin-left:auto;margin-right:auto" id="player"></a>
			<!-- this will install flowplayer inside previous A- tag. -->
	        <script>
				flowplayer("player", "scripts/flowplayer-3.2.5.swf",
				{
					clip: {
						autoPlay: false,
					}
				});
			</script>
	    </div>
    	<div id="div_index_izq_inf">
    		<h1>
   	    		<img src="imagenes/rectangulo naranja.png" alt="" width="33" height="45" align="absmiddle" />
	            Próximos eventos
    	    </h1>
            <?php $calendario = new calendario(); $calendario->proximos_eventos(); $calendario->mostrar_proximos_eventos(); ?>
	    </div>
		<div id="div_index_der_inf">
			<h1>
    			<img src="imagenes/rectangulo azul.png" alt="" width="33" height="45" align="absmiddle" />
	            Alianzas
			</h1>
            <?php $alianzas = new alianzas(); $alianzas->muestra_contenido(); ?>
		</div>
	</div>
</div>
<div id="pie">
	<p id="texto_pie">Copyright &copy; Gimnasio Ingl&eacute;s 2013. Todos los derechos reservados. Programación Por <a href="http://www.cogroupsas.com">Felipe Camacho</a>, Dise&ntilde;o por Tonica Films, Adpataci&oacute;n para web VideoExpress.org</p>
</div>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37712847-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>