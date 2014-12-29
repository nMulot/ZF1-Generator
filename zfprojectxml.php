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
$generer->connectDb($conn, $db);

$tables = $generer->getTables();

$controllers = array();
foreach ($tables as $table) {
	$controllers[] = str_replace(' ', '', (ucwords(str_replace('_', ' ', $table))));
}
        
$actions = array('index', 'edit', 'info', 'suppr');

?>

Voir le code source de cette page

<?php 
echo '<?xml version="1.0"?>';

?>	
<projectProfile type="default" version="1.11.6">
  <projectDirectory>
    <projectProfileFile filesystemName=".zfproject.xml"/>
    <applicationDirectory classNamePrefix="Application_">
      <apisDirectory enabled="false"/>
      <configsDirectory>
        <applicationConfigFile type="ini"/>
      </configsDirectory>
      <controllersDirectory>
	    <controllerFile controllerName="Index">
          <actionMethod actionName="index"/>
        </controllerFile>
		<controllerFile controllerName="Error"/>
<?php foreach ($controllers as $table) { ?>
        <controllerFile controllerName="<?php echo ucfirst($table) ?>">
		<?php foreach ($actions as $action) {?>
          <actionMethod actionName="<?php echo $action ?>"/>
        <?php } ?>
        </controllerFile>
<?php
} ?>
      </controllersDirectory>
      <formsDirectory>
<?php foreach ($controllers as $table) { ?>
		<formFile formName="<?php echo ucfirst($table) ?>Edit"/>
<?php
} ?>
      </formsDirectory>
      <layoutsDirectory enabled="false"/>
      <modelsDirectory>
        <dbTableDirectory>
<?php foreach ($controllers as $table) { ?>
		<dbTableFile dbTableName="<?php echo ucfirst($table) ?>"/>
<?php
} ?>      
       </dbTableDirectory>
<?php foreach ($controllers as $table) { ?>
		<modelFile modelName="<?php echo ucfirst($table) ?>"/>
        <modelFile modelName="<?php echo ucfirst($table) ?>Mapper"/>
<?php
} ?>             
	  </modelsDirectory>
      <modulesDirectory enabled="false"/>
      <viewsDirectory>
        <viewScriptsDirectory>
		  <viewControllerScriptsDirectory forControllerName="Index">
            <viewScriptFile forActionName="index"/>
          </viewControllerScriptsDirectory>
          <viewControllerScriptsDirectory forControllerName="Error">
            <viewScriptFile forActionName="error"/>
          </viewControllerScriptsDirectory>
<?php foreach ($controllers as $table) { 
		foreach($actions as $action) {?>
		  <viewControllerScriptsDirectory forControllerName="<?php echo ucfirst($table) ?>">
            <viewScriptFile forActionName="<?php echo $action ?>"/>
          </viewControllerScriptsDirectory>
	<?php }
} ?>      
       </viewScriptsDirectory>
        <viewHelpersDirectory/>
        <viewFiltersDirectory enabled="false"/>
      </viewsDirectory>
      <bootstrapFile filesystemName="Bootstrap.php"/>
    </applicationDirectory>
    <dataDirectory enabled="false">
      <cacheDirectory enabled="false"/>
      <searchIndexesDirectory enabled="false"/>
      <localesDirectory enabled="false"/>
      <logsDirectory enabled="false"/>
      <sessionsDirectory enabled="false"/>
      <uploadsDirectory enabled="false"/>
    </dataDirectory>
    <docsDirectory>
      <file filesystemName="README.txt"/>
    </docsDirectory>
    <libraryDirectory>
      <zfStandardLibraryDirectory enabled="false"/>
    </libraryDirectory>
    <publicDirectory>
      <publicStylesheetsDirectory enabled="false"/>
      <publicScriptsDirectory enabled="false"/>
      <publicImagesDirectory enabled="false"/>
      <publicIndexFile filesystemName="index.php"/>
      <htaccessFile filesystemName=".htaccess"/>
    </publicDirectory>
    <projectProvidersDirectory enabled="false"/>
    <temporaryDirectory enabled="false"/>
    <testsDirectory>
      <testPHPUnitConfigFile filesystemName="phpunit.xml"/>
      <testPHPUnitBootstrapFile filesystemName="bootstrap.php"/>
      <testApplicationDirectory>
        <testApplicationControllerDirectory>
          <testApplicationControllerFile forControllerName="Index"/>
        </testApplicationControllerDirectory>
      </testApplicationDirectory>
      <testLibraryDirectory/>
    </testsDirectory>
  </projectDirectory>
</projectProfile>
     