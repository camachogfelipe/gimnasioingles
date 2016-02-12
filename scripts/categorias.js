// JavaScript Document
$(document).ready(function() {
	redondear();
	
	var v = $(".categorias").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			titulo: {
				required: true,
				minlength: 5,
				letterswithbasicpuncacent: true
			},
			descripcion: {
				required: true,
				minlength: 5
			}
		},
		messages: {
			titulo: {
				required: " <span id='msj_error_texto'>Ingrese el nombre</span>",
				minlength: jQuery.format(" <span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpuncacent: " <span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			descripcion: {
				required: " <span id='msj_error_texto'>Ingrese la descripción</span>",
				minlength: jQuery.format(" <span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>")
			}
		},
		errorPlacement: function(error, element) {
			error.insertAfter("#error-"+element.attr('id'));
		},
		submitHandler: function(){
			$('#load').show();
			$.ajax(
			{
				type: 'POST',
				url: $('.categorias').attr('action'),
				data: $('.categorias').serialize(),
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