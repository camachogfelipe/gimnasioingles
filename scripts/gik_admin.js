// JavaScript Document
var lyteboxTheme = 'orange';

$(document).ready(function() {
	
	mainmenu();
	
	$("#load").hide();
	
	settings = {
          tl: { radius: 20 },
          tr: { radius: 20 },
          bl: { radius: 20 },
          br: { radius: 20 },
          antiAlias: true,
          autoPad: true,
          validTags: ["div", "table", "thead", "tbody", "tr"]
      }

	redondear(settings);
});

$(function() {
    $('img[data-hover]').hover(function() {
        $(this).attr('tmp', $(this).attr('src')).attr('src', $(this).attr('data-hover')).attr('data-hover', $(this).attr('tmp')).removeAttr('tmp');
    }).each(function() {
        $('<img />').attr('src', $(this).attr('data-hover'));
    });;
});

function recargar(x,y,z){
	var pagina=x+".php?"+y;
	//alert(pagina);
	$("#load").show();
	$.post(pagina, function(data){
		$("#"+z).html(data);
		initLytebox();
	});
	$("#load").hide();
}

function mainmenu(){
$(" #menu_usuarios ul li ul ").css({display: "none"}); // Opera Fix
$(" #menu_usuarios ul li").hover(function(){
		$(this).find('ul:first').css({visibility: "visible",display: "none"}).show(400);
		},function(){
		$(this).find('ul:first').css({visibility: "hidden"});
		});
}

function setFocus() {
	if(document.acceso) {
		document.forms.acceso.usuario.select();
		document.forms.acceso.usuario.focus();
	}
}

function redondear(settings) {
	$("#calendario").corner("bottom", settings);
	$("#calendario caption").corner("top", settings);
	$("#calendario thead th").corner(settings);
	$('#BannerNoticias').corner();
	$('#div_index_izq_inf').corner();
	$('#div_index_der_inf').corner();
	$('#contenido_usuarios').corner(settings);
	$('#menu_usuarios').corner(settings);
	$('#pie_pagina_usuarios').corner("top 20");
	$('#pie_pagina').corner("top 20");
	$('.submit').corner(settings);
	$('.reset').corner(settings);
}

function eliminar(x,y,z) {
	var entrar = confirm("¿Está seguro de eliminar este árticulo?", true);
		if(entrar) {
			recargar(x,y,z)
		}
}