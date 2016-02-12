// JavaScript Document
$(document).ready(function() {
	var v = $("#contacto").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			nombre_completo: {
				required: true,
				minlength: 5,
				letterswithbasicpunc: true
			},
			motivo: {
				required: true,
				minlength: 5,
				letterswithbasicpunc: true
			},
			mail: {
				required: true,
				email: true
			},
			asunto: {
				required: true,
				minlength: 5,
				letterswithbasicpunc: true
			}
		},
		messages: {
			nombre_completo: {
				required: " <br /><span id='msj_error_texto'>Ingrese su nombre por favor</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpunc: " <br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			motivo: {
				required: " <br /><span id='msj_error_texto'>Ingrese el motivo por el que nos contacta</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpunc: " <br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			mail: {
				required: " <br /><span id='msj_error_texto'>Ingrese el mail para poderlo contactar</span>",
				email: " <br /><span id='msj_error_texto'>Ingrese un mail valido</span>"
			},
			asunto: {
				required: " <br /><span id='msj_error_texto'>Ingrese el texto de contacto</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpunc: " <br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			}
		},
		submitHandler: function(){
			$('#load').show();
			$.ajax({
				type: 'POST',
				url: $('#contacto').attr('action'),
				data: $('#contacto').serialize(),
				success: function(data)
				{
					$('#contacto').hide();
					var result = $('#resultado').html(data);
					//$('#load').hide();
					$(result).fadeIn('slow');
					$('#load').hide();
				}
			})
			return false;
		}
	});
	jQuery(".reset").click(function() {
			v.resetForm();
	});
});