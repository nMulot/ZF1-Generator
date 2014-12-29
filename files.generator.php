<?php 

/**
 * Outil permettant de générer les fichiers qui composent un projet Zend
 * @author Dina Rajaonson
 */

set_include_path(implode(PATH_SEPARATOR, array(
    realpath(dirname(__FILE__) . './../library'),
    'G:\ZENDFRAMEWORK\ZendFramework-1.12.9-minimal\library',
    get_include_path(),
)));


require_once 'config/connect.php';
require_once 'files.generator.inc.php';

session_start();

$model = new Files_Generator();

if (!$_SESSION['db']) {
	header("Location: index.php");
}

$db = $_SESSION['db'];
$model->connectDb($db);

if (isset($_POST['submit'])) {
	$namespace = $_POST['namespace'];
	
	if (isset($_POST['table'])) {
		if ($model->genererSource($_POST['table'], $namespace)) {
			$confirm = 'Fichiers sources générés avec succés';
		}
	} else {	
		if ($model->genererSource(null, $namespace)) {
			$confirm = 'Fichiers sources générés avec succés';
		}
	}
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<title>Zend Class Generator <?php echo ((isset($_POST['table']))?'| ' . $_POST['table']:'')?></title>

	<link rel="stylesheet" href="public/css/style.css">
	
	<style>
		.confirm {
			padding:50px;
			font-weight:bold;
			color:green;
			font-size:1.2em;
		}
		
		#header {
			background-color:#575757;
		}
		
		#header h1 a {
			color:#fff;
			text-shadow:1px 0 #000;
		}
	</style>
</head>
<body>
	<div id="header">
		<h1><a href="index.php">Zend Generator <?php echo ((isset($_POST['table']))?' > ' . $_POST['table']:'')?></a></h1>
	</div>
	
	<?php if (isset($confirm)) { ?>
	<div class="confirm">
		<?php echo $confirm ?><br />
		<a href="./output.zip">Télécharger le dossier</a>
	</div>
	<?php } ?>
	
	<div style="margin:10px;">
		<h4>
			Les fichiers seront placés dans le dossier /output.<br />
			Avant de commencer, assurez-vous que ce dossier soit vide.
		</h4>
		<div style="margin:20px;">
		<form action="" method="post">
			Namespace : <input type="text" name="namespace" /><br />
			Exemple : 'Application_', 'App_' (sans les quotes) ou vide 
			<br />
			<br />
			
			<input type="submit" name="submit" value="Générer les fichiers sources" />
		</form>
		</div>
	</div>
</body>
<?php 

