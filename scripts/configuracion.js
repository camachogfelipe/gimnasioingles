// JavaScript Document
$(document).ready(function() {
	redondear();
	
	var v = $(".configuracion").validate({
		success: function(label) { label.addClass("valid").text(" ") },
		rules: {
			correo: {
				required: true,
				email2: true
			}
		},
		messages: {
			correo: {
				required: " <br /><span id='msj_error_texto'>Ingrese el correo de contacto</span>",
				email2: " <br /><span id='msj_error_texto'>Ingrese un correo valido</span>"
			}
		},
		submitHandler: function(){
			$('#load').show();
			$.ajax({
				type: 'POST',
				url: $('.configuracion').attr('action'),
				data: $('.configuracion').serialize(),
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