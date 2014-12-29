<?php 

/**
 * Outil permettant de générer le contenu des fichiers qui composent un projet Zend
 * @author Dina Rajaonson
 */
require_once 'config/connect.php';
require_once 'zend.generator.inc.php';

session_start();

$generer = new Zend_Generator();

if (!$_SESSION['db']) {
	header("Location: index.php");
}

$db = $_SESSION['db'];
$generer->connectDb($db);

if (isset($_GET['table'])) {
	if ($_GET['table'] != "") {
    
        if (isset($_GET['naming'])) {
            $naming = $_GET['naming'];
            $generer->setNaming($naming);
        }
        
		$table = $_GET['table'];
		$namespace = ($_GET['namespace']!='')?$_GET['namespace']:null;
		
		$colonnes = $generer->getColonnesName($table);
		
		$output_model  = $generer->model($table, $namespace);
		$output_mapper = $generer->mapper($table, $namespace);
		$output_form   = $generer->form($table, $namespace);
		$output_controller = $generer->controller($table, $namespace, $_GET['controller']);
		$output_view_index = $generer->viewIndex($table, $namespace);
		$output_view_info = $generer->viewInfo($table, $namespace);
		$output_view_edit = $generer->viewEdit();
		$output_view_suppr = $generer->viewSuppr();
	}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Zend Class Generator <?php echo ((isset($_GET['table']))?'| ' . $_GET['table']:'')?></title>

	<link rel="stylesheet" href="public/css/style.css">
	<link rel="stylesheet" href="public/css/ui-lightness/jquery-ui-1.8.16.custom.css">
	<link rel="stylesheet" href="public/css/smoothness/jquery-ui-1.8.16.custom.css">

	<script src="public/js/jquery-1.6.2.min.js"></script>
	<script src="public/js/jquery-ui-1.8.16.custom.min.js"></script>
	<script>
	$(function() {
		$( "#tabs" ).tabs();
	});
	</script>
</head>
<body>
	
	<div id="header">
		<h1><a href="index.php">Zend Generator <?php echo ((isset($_GET['table']))?' > ' . $_GET['table']:'')?></a></h1>
	</div>
	
	<div style="margin:10px;">
		<div id="wrapper-left">
			<div id="content-left">
				<form action="" method="get">
				<table>
					<tr><td>Namespace  </td><td><input type="text" name="namespace" title="Par défaut : Application_" value="<?php echo ((isset($_GET['namespace']))?$_GET['namespace']:'Application_') ?>" /></td></tr>
					<tr><td>Table *</td><td>
						<select name="table">
							<option value="">Sélectionner</option>
						<?php foreach ($generer->getTables() as $row) { ?>
							<option value="<?php echo $row ?>" <?php if (isset($_GET['table']) && $_GET['table'] == $row) echo 'selected="selected"' ?>><?php echo $row ?></option>
						<?php } ?>
						</select>
					</td></tr>
					<tr><td>Controller</td><td><input type="text" name="controller" title="Si différent de la table" value="<?php echo ((isset($_GET['controller']))?$_GET['controller']:'') ?>" /></td></tr>
					<tr><td>Nommage</td><td>
                        <select name="naming">
                        <?php
                        $namings = array('underscore'=>'under_score', 'camelcase'=>'camelCase'); 
                        foreach ($namings as $row=>$label) { ?>
							<option value="<?php echo $row ?>" <?php if (isset($_GET['naming']) && $_GET['naming'] == $row) echo 'selected="selected"' ?>><?php echo $label ?></option>
						<?php 
                        } ?>
                        </select>
                    </td></tr>
					<tr><td></td><td><input type="submit" value="Générer" /></td></tr>
					<tr><td colspan="2" style="padding:10px 0;">
						<a class="button" style="text-align:center;width:100%;" href="files.generator.php">Générer tous les fichiers</a>
					</td></tr>
					<tr><td colspan="2" style="padding:10px 0;">
						<a class="button" style="text-align:center;width:100%;" href="zfprojectxml.php">Fichier XML du projet</a>
					</td></tr>
					<?php if (isset($colonnes)) {?>
						<tr><td colspan="2"><h4>Colonnes :</h4></td></tr>
						<?php 
						foreach ($colonnes as $i => $colonne) { ?>
							<tr><td style="text-align:right;"><?php echo $i + 1 ?>  :  </td><td><?php echo $colonne ?></td></tr>
						<?php 
						} 
					} ?>
				</table>
				</form>
			</div>
		</div>
		
		<div id="wrapper-content">
			<div id="tabs">
				<ul>
					<li><a href="#tabs-1">Controller</a></li>
					<li><a href="#tabs-2">Form</a></li>
					<li><a href="#tabs-3">Model</a></li>
					<li><a href="#tabs-4">Model Mapper</a></li>
					<li><a href="#tabs-5">View edit</a>
					<li><a href="#tabs-6">View index</a>
					<li><a href="#tabs-7">View info</a>
					<li><a href="#tabs-8">View suppr</a>
				</ul>
				
				<div id="tabs-1">
					<textarea class="output"><?php echo ((isset($output_controller))?($output_controller):'') ?></textarea>
				</div>
				<div id="tabs-2">
					<textarea class="output"><?php echo ((isset($output_form))?($output_form):'') ?></textarea>
				</div>
				<div id="tabs-3">
					<textarea class="output"><?php echo ((isset($output_model))?($output_model):'') ?></textarea>
				</div>
				<div id="tabs-4">
					<textarea class="output"><?php echo ((isset($output_mapper))?($output_mapper):'') ?></textarea>
				</div>
				<div id="tabs-5">
					<textarea class="output"><?php echo ((isset($output_view_edit))?($output_view_edit):'') ?></textarea>
				</div>
				<div id="tabs-6">
					<textarea class="output"><?php echo ((isset($output_view_index))?($output_view_index):'') ?></textarea>
				</div>
				<div id="tabs-7">
					<textarea class="output"><?php echo ((isset($output_view_info))?($output_view_info):'') ?></textarea>
				</div>
				<div id="tabs-8">
					<textarea class="output"><?php echo ((isset($output_view_suppr))?($output_view_suppr):'') ?></textarea>
				</div>
				
			</div>
		</div>
		<div class="clear"></div>
	</div>
	<div id="footer">
		&copy; Dina Rajaonson - 2011 - Zend Generator 1.2
	</div>
</body>
</html>