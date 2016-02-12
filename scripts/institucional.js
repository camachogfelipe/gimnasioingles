// JavaScript Document
$(document).ready(function() {
	redondear();
	
	var v = $(".institucional").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			titulo: {
				required: true,
				minlength: 5,
				letterswithbasicpunc: true
			},
			descripcion: {
				required: true,
				minlength: 5
			}
		},
		messages: {
			titulo: {
				required: " <br /><span id='msj_error_texto'>Ingrese el título</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpunc: " <br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			descripcion: {
				required: " <br /><span id='msj_error_texto'>Ingrese la descripción</span>",
				minlength: jQuery.format(" <br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>")
			}
		},
		submitHandler: function() {
			$('#load').show();
			$.ajax({
				type: 'POST',
				url: $('.institucional').attr('action'),
				data: $('.institucional').serialize(),
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