// JavaScript Document
$(document).ready(function() {
	redondear();
	$( "#fecha" ).datepicker({ 
		minDate: -20,
		maxDate: "+1M +10D",
		regional: "es"
	});
	$('#hora_inicio').timepicker({
		ampm: true
	});
	$('#hora_fin').timepicker({
		ampm: true
	});
	
	var v = $(".calendario").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			titulo: {
				required: true,
				minlength: 5,
				letterswithbasicpuncacent: true
			},
			fecha: {
				required: true,
				date: true
			},
			hora_inicio: {
				time: true
			},
			hora_fin: {
				time: true				
			},
			categoria: {
				required: true
			},
			descripcion: {
				required: true,
				minlength: 5
			}
		},
		messages: {
			titulo: {
				required: " <span id='msj_error_texto'>Ingrese el título</span>",
				minlength: jQuery.format(" <span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpuncacent: " <span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			fecha: {
				required: " <span id='msj_error_texto'>Ingrese una fecha para el evento</span>",
				date: " <span id='msj_error_texto'>Ingrese una fecha valida por favor</span>"
			},
			hora_inicio: {
				time: " <span id='msj_error_texto'>Ingrese una hora valida por favor</span>"
			},
			hora_fin: {
				time: " <span id='msj_error_texto'>Ingrese una hora valida por favor</span>"
			},
			categoria: {
				required: " <span id='msj_error_texto'>Seleccione la categoría por favor</span>"
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
			$.ajax({
				type: 'POST',
				url: $('.calendario').attr('action'),
				data: $('.calendario').serialize(),
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