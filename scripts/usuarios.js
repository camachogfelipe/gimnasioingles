// JavaScript Document
$(document).ready(function() {
	redondear();
	
	var v = $(".usuarios").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			nombre: {
				required: true,
				minlength: 5,
				letterswithbasicpuncacent: true
			},
			apellidos: {
				required: true,
				minlength: 5,
				letterswithbasicpuncacent: true
			},
			correo: {
				required: true,
				email2: true
			},
			login: {
				required: true,
				minlength: 5,
				letterswithbasicpunc: true
			},
			tipo_usuario: {
				required: true
			},
			activar: {
				required: true
			},
			institucional: {
				required: true
			},
			calendario: {
				required: true
			},
			galeria: {
				required: true
			},
			niveles: {
				required: true
			},
			noticias: {
				required: true
			}
		},
		messages: {
			nombre: {
				required: "<br /><span id='msj_error_texto'>Ingrese el nombre</span>",
				minlength: jQuery.format("<span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpuncacent: "<span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			apellidos: {
				required: "<br /><span id='msj_error_texto'>Ingrese los apellidos</span>",
				minlength: jQuery.format("<br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpuncacent: "<br /><span id='msj_error_texto'>Ingrese solo letras por favor</span>"
			},
			correo: {
				required: "<br /><span id='msj_error_texto'>Ingrese el correo electrónico</span>",
				email2: "<br /><span id='msj_error_texto'>Ingrese un correo valido</span>"
			},
			login: {
				required: "<br /><span id='msj_error_texto'>Ingrese el usuario de acceso</span>",
				minlength: jQuery.format("<br /><span id='msj_error_texto'>Mínimo {0} caracteres necesarios!</span>"),
				letterswithbasicpunc: "<br /><span id='msj_error_texto'>Ingrese solo letras sin acentos ni caracteres latinos</span>"
			},
			tipo_usuario: {
				required: "<span id='msj_error_texto'>Seleccione un valor por favor</span>"
			},
			activar: {
				required: "<span id='msj_error_texto'>Seleccione un valor por favor</span>"
			},
			institucional: {
				required: "<span id='msj_error_texto'>Seleccione un valor por favor</span>"
			},
			calendario: {
				required: "<span id='msj_error_texto'>Seleccione un valor por favor</span>"
			},
			galeria: {
				required: "<span id='msj_error_texto'>Seleccione un valor por favor</span>"
			},
			niveles: {
				required: "<span id='msj_error_texto'>Seleccione un valor por favor</span>"
			},
			noticias: {
				required: "<span id='msj_error_texto'>Seleccione un valor por favor</span>"
			}
		},
		errorPlacement: function(error, element) {
			error.insertAfter("#error-"+element.attr('id'));
		},
		submitHandler: function() {
			var cant = $('.2:radio:checked').size();
			var g2 = $('.g2:radio:checked').size();
			cant += g2;
			if(cant < 6) {
				$('#load').show();
				$.ajax({
					type: 'POST',
					url: $(".usuarios").attr('action'),
					data: $(".usuarios").serialize(),
					success: function(data)
					{
						var result = $('#contenido2').html(data);
						$('#load').hide();
						$(result).fadeIn('slow');
					}
				})
			}
			else {
				alert("Debe darle al menos un permiso al usuario");
				return false;
			}
			return false;
		}
	});
	jQuery(".reset").click(function() {
			v.resetForm();
	});
});

function selecciona(input) {
	if(input == "A") {
		$(".1").attr("checked", true);
	}
	else if(input = "NA") {
		$(".2").attr("checked", true);
	}
}