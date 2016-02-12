// JavaScript Document
$(document).ready(function() {
	redondear();
	
	var v = $(".alianzas").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			nombre: {
				required: true,
				minlength: 5,
				letterswithbasicpuncacent: true
			},
			web: {
				required: true,
				url: true
			}
		},
		messages: {
			nombre: {
				required: " <br /><span id='msj_error_texto'>Ingrese el nombre de la alianza</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpuncacent: " <br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			web: {
				required: " <br /><span id='msj_error_texto'>Ingrese la dirección web</span>",
				url: jQuery.format(" <br /><span id='msj_error_texto'>Ingrese una dirección web valida</span>")
			}
		},
		submitHandler: function(){
			$('#load').show();
			$.ajax({
				type: 'POST',
				url: $('.alianzas').attr('action'),
				data: $('.alianzas').serialize(),
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