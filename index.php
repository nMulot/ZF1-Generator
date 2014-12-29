<?php
session_start();

require_once 'config/connect.php';
require_once 'zend.generator.inc.php';

if (isset($_POST['db'])) {
	$_SESSION['db'] = $_POST['db'];
	header('Location:zend.generator.php');
}

$model = new Zend_Generator();
$databases = $model->getDatabases();

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Strict//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
<title>Zend Class Generator <?php echo ((isset($_POST['table']))?'| ' . $_POST['table']:'')?></title>
<style>
	* {padding:0;margin:0;}
	body {font-family:Helvetica,Arial;font-size:14px;background-color:#333333;color:white;}
	#footer {background-color: #333333;font-size:12px;color: #F7F7F7;margin-top:25px;padding:10px;text-align:right;}
	
</style>
</head>
<body>
	<div style="margin:50px;padding:20px;background-color:grey;border-radius:10px;">
		<form action="" method="post">
			<span style="padding-right:50px;">Base de données</span> 
			<select name="db" style="width:150px;padding:5px;">	
				<option></option>
			<?php 
			foreach ($databases as $db) { ?>
				<?php var_dump($db);?>
				<option value="<?php echo $db ?>"><?php echo $db ?></option>
			<?php 
			} ?>
			</select>
			
			<input type="submit" value="Connecter" style="padding:5px 10px;" />
		</form>
	</div>
	<div id="footer">
		&copy; Dina Rajaonson - 2011
	</div>
</body>
</html>