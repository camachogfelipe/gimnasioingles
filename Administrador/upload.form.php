<!DOCTYPE HTML>
<html lang="es">
<head>
<link rel="stylesheet" href="<?php echo COGPATH_CSS2 ?>blitzer/jquery-ui-1.9.2.custom.min.css" type="text/css" media="screen" />
<link href="<?php echo COGPATH_CSS ?>usuarios.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="<?php echo COGPATH_CSS ?>gik.css" type="text/css" media="screen" />
</head>
<body>
    <form action="subir_galeria.php?op=2&amp;s=1&amp;id=<?php echo $this->id ?>" method="post" enctype="multipart/form-data" class="form_upload">
        <?php
			if(isset($_GET['mime'])) :
				switch($_GET['mime']) :
					case 'image'	:	$mime = "image/*";
										break;
					default			:	$mime = "*/*";
										break;
				endswitch;
			else :
				$mime = "*/*";
			endif;
        ?>
        <input type="file" name="myfile[]" multiple accept="<?php echo $mime; ?>"><br>
        <button id="button" class="submit" type="submit">Subir archivos</button>
    </form>
    <div class="progress">
        <div class="bar"></div >
        <div class="percent">0%</div >
    </div>
    <div id="status"></div>
	<script type="text/javascript" src="<?php echo COGPATH_JS ?>jquery-1.8.2.min.js"></script>
    <script type="text/javascript" src="<?php echo COGPATH_JS ?>jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo COGPATH_JS ?>jquery.validate.js"></script>
    <script type="text/javascript" src="<?php echo COGPATH_JS ?>jquery.validate.additional-methods.js"></script>
    <script type="text/javascript" src="<?php echo COGPATH_JS ?>bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo COGPATH_JS ?>jquery-ui/js/jquery-ui-1.9.2.custom.min.js"></script>
    <script type="text/javascript" src="<?php echo COGPATH_JS ?>gik.js"></script>
    <script type="text/javascript" src="<?php echo COGPATH_JS ?>upload.js"></script>
</body> 
</html>