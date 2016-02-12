// JavaScript Document
$(document).ready(function() {
	redondear();
	
	var v = $(".niveles").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			nombre: {
				required: true,
				minlength: 5,
				letterswithbasicpuncacent: true
			},
			rango1: {
				required: true,
				number: true
			},
			rango2: {
				number: true
			},
			equivalente: {
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
			nombre: {
				required: " <span id='msj_error_texto'>Ingrese el título</span>",
				minlength: jQuery.format(" <span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpuncacent: " <span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			rango1: {
				required: " <span id='msj_error_texto'>Ingrese el rango mínimo de edad por favor</span>",
				number: " <span id='msj_error_texto'>Ingrese solo numeros enteros por favor</span>"
			},
			rango2: {
				number: " <span id='msj_error_texto'>Ingrese solo numeros enteros por favor</span>"
			},
			equivalente: {
				required: " <span id='msj_error_texto'>Ingrese el equivalente en otros planteles</span>",
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
		submitHandler: function() {
			$('#load').show();
			$.ajax(
			{
				type: 'POST',
				url: $(".niveles").attr('action'),
				data: $(".niveles").serialize(),
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