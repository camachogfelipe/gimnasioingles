// JavaScript Document

$(document).ready(function() {
	redondear();
	
	var v = $(".noticias").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			titulo: {
				required: true,
				minlength: 5,
				maxlength: 36,
				letterswithbasicpuncacent: true
			},
			A: { required: true },
			P: { required: true },
			texto: {
				required: true,
				minlength: 5,
				maxlength: 232,
				letterswithbasicpuncacent: true
			}
		},
		messages: {
			titulo: {
				required: " <br /><span id='msj_error_texto'>Ingrese el título de la noticia</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				maxlength: jQuery.format(" <br /><span id='msj_error_texto'>Máximo {0} caracteres!</span>"),
				letterswithbasicpuncacent: " <br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			A: { required: " <br /><span id='msj_error_texto'>Seleccione si la noticia se activa inmediatamente</span>" },
			P: { required: " <br /><span id='msj_error_texto'>Seleccione si la noticia es permanente</span>" },
			texto: {
				required: " <br /><span id='msj_error_texto'>Ingrese el texto de la noticia</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				maxlength: jQuery.format(" <br /><span id='msj_error_texto'>Máximo {0} caracteres!</span>"),
				letterswithbasicpuncacent: " <br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			}
		},
		submitHandler: function() {
			$('#load').show();
			$.ajax({
				type: 'POST',
				url: $(".noticias").attr('action'),
				data: $(".noticias").serialize(),
				success: function(data)
				{
					var result = $('#contenido2').html(data);
					$('#load').hide();
					$(result).fadeIn('slow');
				}
			})
			return false;
		}
	});
	jQuery(".reset").click(function() {
			v.resetForm();
	});
});

function contar(input) {
	var t = document.getElementById(input).value;
	t = t.length;
	//document.getElementById(input+"-contar").innerHTML("Total caracteres: ".t);
	$("#"+input+"-contar").html("caracteres: "+t);
}