<?php

/**
 * G�n�rateur de code source pour Zend Framework
 * @author Dina Rajaonson 10-2011
 *
 */
require_once 'zend.generator.inc.php';

require_once('Zend/Filter/Compress.php');
require_once('Zend/Filter/Compress/Zip.php');

class Files_Generator extends Zend_Generator
{	
	protected $namespace;
	
	public function genererSource($table=null, $namespace)
	{
		$this->namespace = $namespace;
		
		// vider dossier output
		if (file_exists(dirname(__FILE__).'/output')) {
			$this->deleteDir(realpath(dirname(__FILE__)).'/output');
		}
		if (!file_exists(dirname(__FILE__).'/output')) {
			mkdir(realpath(dirname(__FILE__)).'/output');
		}
		
		$outputzip = realpath(dirname(__FILE__)).'/output.zip';
		if (file_exists($outputzip)) {
			unlink($outputzip);
		}
		
		$this->controllerFiles();
		$this->formFiles();
		$this->modelFiles();
		$this->viewFiles();
		
		// Zip files
		$filter = new Zend_Filter_Compress(array(
			'adapter' => 'Zip',
			'options' => array(
				'archive' => 'output.zip',
			),
		));
		$compressed = $filter->filter($this->getPath());
		
		return true; 
	}
	
	public function getPath()
	{
		return realpath(dirname(__FILE__).'/output');
	}
	
	public function controllerFiles()
	{
		// Creer dossier controllers
		mkdir($this->getPath().'/controllers');
		foreach ($this->getTables() as $table) {
			// Cr�er fichier et ajouter le contenu
			$filename = $this->getPath().'/controllers/'.$this->className($table) . 'Controller.php';
			$fp = fopen($filename, 'w');
			fwrite($fp, $this->controller($table, $this->namespace));
			fclose($fp);
		}
	}
	
	public function formFiles() 
	{
		// Creer dossier forms
		mkdir($this->getPath().'/forms');
		
		foreach ($this->getTables() as $table) {
			// Cr�er fichier et ajouter le contenu
			$filename = $this->getPath().'/forms/'.$this->className($table) . 'Edit.php';
			$fp = fopen($filename, 'w');
			fwrite($fp, $this->form($table, $this->namespace));
			fclose($fp);
		}
	}
	
	public function modelFiles() 
	{
		// Creer dossier models
		mkdir($this->getPath().'/models');
		
		foreach ($this->getTables() as $table) {
			// Cr�er fichier et ajouter le contenu model
			$filename = $this->getPath().'/models/'.$this->className($table) . '.php';
			$fp = fopen($filename, 'w');
			fwrite($fp, $this->model($table, $this->namespace));
			fclose($fp);
			
			// Cr�er fichier et ajouter le contenu mapper
			$filename = $this->getPath().'/models/'.$this->className($table) . 'Mapper.php';
			$fp = fopen($filename, 'w');
			fwrite($fp, $this->mapper($table, $this->namespace));
			fclose($fp);
		}
	}
	
	public function viewFiles() 
	{
		$actions = array('index', 'edit', 'info', 'suppr');
		// Creer dossier views
		mkdir($this->getPath().'/views');
		
		foreach ($this->getTables() as $table) {
			// Cr�er dossier controller
			$controllerUrl = $this->controllerUrl($table);
			mkdir($this->getPath().'/views/'.$controllerUrl);
		
			$filename = $this->getPath().'/views/'.$controllerUrl.'/suppr.phtml';
			foreach ($actions as $action) {
				// Cr�er fichier et ajouter le contenu
				$filename = $this->getPath().'/views/'.$controllerUrl.'/'.$action . '.phtml';
				$fp = fopen($filename, 'w');
				$method = 'view'.ucfirst($action);
				fwrite($fp, $this->$method($table, $this->namespace));
				fclose($fp);
				
			}
		}
	}
}