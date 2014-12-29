<?php 

/**
 * Générateur de code source pour Zend Framework
 * @author Dina Rajaonson 10-2011
 *
 */
class Zend_Generator
{	
	protected $_naming = null;
    protected $conn = null;
    
    public function __construct($naming='underscore')
    {
        $this->setNaming($naming);
        $this->initDb();
    }
    
    public function setNaming($naming)
    {
        $this->_naming = $naming;
    }
    
    public function initDb() 
    {
        
        try {
            $conn = new PDO('mysql:host=127.0.0.1', 'root', '');
            $this->conn = $conn;
        } catch (PDOException $e) {
            print "Erreur !: " . $e->getMessage() . "<br/>";
            die();
        }
    }
    
    public function connectDb($db)
	{
		try {
			$conn = new PDO('mysql:host=127.0.0.1;dbname='.$db, 'root', '');
			$this->conn = $conn;
		} catch (PDOException $e) {
			echo "Erreur : ". $e->getMessage();
			die();
		}
	}
	
	
	public static function deleteDir($dirPath) 
	{
		if (!is_dir($dirPath)) {
			throw new InvalidArgumentException('$dirPath must be a directory');
		}
		if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
			$dirPath .= '/';
		}
		$files = glob($dirPath . '*', GLOB_MARK);
		foreach ($files as $file) {
			if (is_dir($file)) {
				self::deleteDir($file);
				rmdir($file);
			} else {
				unlink($file);
			}
		}
		// rmdir($dirPath);
	}

	public function getDatabases()
	{
		$req = "SHOW DATABASES";
		$res = $this->conn->prepare($req);
        $res->execute();
        
		$tab = array();
		$exclus = array(
			'information_schema',
			'performance_schema',
			'mysql'
		);
        
        $rows = $res->fetchAll();
        
        foreach($rows as $row) {
            if (!in_array($row['Database'], $exclus)) {
				$tab[] = $row[0];
			}
		}
		return $tab;
	}
	
	/** 
	 * Retourne la liste des tables de la base de données sélectionné
	 */
	public function getTables()
	{
		$req = "SHOW TABLES";
		$res = $this->conn->prepare($req);
        $res->execute();
        
		$tab = array();
        
        $rows = $res->fetchAll();
        
		foreach ($rows as $row) {
			$tab[] = $row[0];
		}
		return $tab;
	}
	
	/**
	 * Récupère les colonnes d'une table
	 * @param string $table
	 */
	public function getColonnesName($table)
	{
		$req = "SHOW COLUMNS FROM $table";
		$res = $this->conn->prepare($req);
        $res->execute();
        
		$tab = array();
		$rows = $res->fetchAll();
        
		foreach ($rows as $row) {
			$tab[] = $row['Field'];
		}

		return $tab;
	}
	
	/**
	 * Retourne l'url du controller pour une table
	 * Ex : pour la table commande_ligne, l'url sera commande-ligne
	 * @param unknown_type $table
	 */
	public function controllerUrl($table) 
	{
		return strtolower(str_replace('_', '-', $table));
	}
	
	/**
	 * Retourne le nom de la classe à partir du nom de la table
	 * Ex : pour la table commande_ligne, le nom sera CommandeLigne
	 * @param unknown_type $table
	 */
	public function className($table) {
		return str_replace(' ', '', ucwords(str_replace('_', ' ', $table)));
	}
	
    /**
     * Convertit une chaine avec underscore en camelCase
     * Ex : commande_ligne_id => commandeLigneId
     */ 
    public function camelCase($string) 
    {
        return lcfirst(str_replace(' ', '', ucwords(str_replace('_', ' ', $string))));
    }
    
    /**
     * Retourne la chaine propriété qui correspond à la colonne
     * Vérifie si on est en mode camelCase ou under_score
     */ 
    public function getProperty($string)
    {
        if ($this->_naming == 'camelcase') {           
            return $this->camelCase($string);
        }
        return strtolower($string);
    }
    
	/**
	 * Contenu du model
	 * @param string $table
	 * @param array $fields
	 * @param string $namespace
	 */
	public function model($table, $namespace="Application_")
	{
		$canevas = file_get_contents('canevas/model.txt');
		$getsetCanevas = file_get_contents('canevas/getset.txt');
		$class = strtolower($table);
		$class_uc = $this->className($table);
		$classname = $namespace . 'Model_' . $class_uc;

		// Propriétés
		$proprietes = '';
		$getset = '';
		
		foreach ($this->getColonnesName($table) as $field) {
			if ($field != '') {
				// $field = strtolower($field);
				$field = $this->getProperty($field);
				
				$proprietes.= '    protected $_' . $field . ';' . "\n";

				$getsetfield = $getsetCanevas;
				$getsetfield = str_replace('<<PROPRIETE>>', ucfirst($field), $getsetfield);
				$getsetfield = str_replace('<<FIELD>>', $field, $getsetfield);
				
				$getset.= $getsetfield;
			}
		}
		
		$canevas = str_replace('<<CLASSNAME>>', $classname, $canevas);
		$canevas = str_replace('<<PROPRIETES>>', $proprietes, $canevas);
		$canevas = str_replace('<<CLASS>>', $class, $canevas);
		$canevas = str_replace('<<GETSET>>', $getset, $canevas);
		
		return $canevas;
	}
	
	/**
	 * Contenu du Mappper
	 * @param string $table
	 * @param array $fields
	 * @param string $namespace
	 */
	public function mapper($table, $namespace="Application_")
	{
		$canevas = file_get_contents('canevas/mapper.txt');
		$class = strtolower($table);
		$class_uc = $this->className($table);
		$classname = $namespace . 'Model_' . $class_uc;
		$dbtable = $namespace . 'Model_DbTable_' . $class_uc;
		
		// Get et Set
		$getObj = '';
		$setObj = '';
		foreach ($this->getColonnesName($table) as $i => $field) {
			if ($field != '') {
				// $field = strtolower($field);
				$dbField = $field;
				$field = $this->getProperty($field);
				
				if ($i == 0) {
					$setObj.= '$obj';
					$getObj.= '';
					
				} else {
					$setObj.= '                ';
					$getObj.= '            ';
				}
				$setObj.= '->set' . ucfirst($field) . '($row->' . $dbField . ')' . "\n";
				
				$getObj.= "'".$field."'" . ' => $obj->get' . ucfirst($field). '(),' . "\n";
				// Ne pas oublier d'enlever la derniÃ¨re virgule
				
			}
		}
		
		$canevas = str_replace('<<CLASSNAME>>', $classname, $canevas);
		$canevas = str_replace('<<DBTABLE>>', $dbtable, $canevas);
		$canevas = str_replace('<<SETOBJ>>', substr($setObj, 0, -1), $canevas);
		$canevas = str_replace('<<GETOBJ>>', substr($getObj, 0, -2), $canevas);
				
		return $canevas;
	}
	
	public function form ($table, $namespace="Application_")
	{
		$canevas = file_get_contents('canevas/form.txt');
		$formElementCanevas = file_get_contents('canevas/form_element.txt');
		$class = strtolower($table);
		$class_uc = $this->className($table);
		$classname = $namespace . 'Form_' . $class_uc . 'Edit';
		
		// Get
		$elementsContent = '';
		foreach ($this->getColonnesName($table) as $field) {
			if ($field != '' && $field != 'id') {
				$field = $this->getProperty($field);
				
				$formElement = $formElementCanevas;
				$formElement = str_replace('<<FIELD>>', $field, $formElement);
				$formElement = str_replace('<<LABEL>>', str_replace("_", " ",ucfirst($field)), $formElement);
				
				$elementsContent.= $formElement;
			}
		}
		
		$canevas = str_replace('<<CLASSNAME>>', $classname, $canevas);
		$canevas = str_replace('<<FORMELEMENT>>', $elementsContent, $canevas);
				
		return $canevas;
	}
	
	public function controller($table, $namespace="Application_", $controller='')
	{
		$canevas = file_get_contents('canevas/controller.txt');
		$class = strtolower($table);
		$class_uc = $this->className($table);
		$title = ucfirst(str_replace("_", " ", $table));
		$classname = $namespace . 'Model_' . $class_uc;
		$form = $namespace . 'Form_' . $class_uc .'Edit';
		if ($controller == '') $controller = $this->controllerUrl($table);
		
		// Get
		$getObj = '';
		$setObj = '';
		foreach ($this->getColonnesName($table) as $i=>$field) {
			if ($field != '') {
                $dbField = $field;
				$field = $this->getProperty($field);
				
				if ($i == 0) {
					$setObj.= '$model';
					$getObj.= '';
					
				} else {
					$setObj.= '                  ';
					$getObj.= '                ';
				}
				
				if ($field != 'id') {
					$setObj.= '->set' . ucfirst($field) . '($values[\'' . $dbField . '\'])' . "\n";
				}
				
				$getObj.= "'".$field."'" . ' => $obj->get' . ucfirst($field). '(),' . "\n";
				// Ne pas oublier d'enlever la derniÃ¨re virgule
				
			}
		}
		
		$canevas = str_replace('<<CLASSNAME>>', $classname, $canevas);
		$canevas = str_replace('<<CLASS>>', $class, $canevas);
		$canevas = str_replace('<<CLASSUC>>', $class_uc, $canevas);
		$canevas = str_replace('<<TITLE>>', $title, $canevas);
		$canevas = str_replace('<<CONTROLLER>>', $controller, $canevas);
		$canevas = str_replace('<<FORM>>', $form, $canevas);
		$canevas = str_replace('<<SETMODEL>>', substr($setObj, 0, -1), $canevas);
		$canevas = str_replace('<<GETOBJ>>', substr($getObj, 0, -2), $canevas);
				
		return $canevas;
	}
	
	/**
	 * Contenu de la vue index
	 * @param string $table
	 * @param array $fields
	 * @param string $namespace
	 */
	public function viewIndex($table, $namespace="Application_")
	{
		$canevas = file_get_contents('canevas/view_index.txt');
		$class = strtolower(str_replace("_", " ", $table));
		$classname = $namespace . 'Model_' . ucfirst($class);

		// Headers et cols
		$headers = '';
		$cols = '';
		foreach ($this->getColonnesName($table) as $field) {
			if ($field != '') {
				$dbField = $field;
				$field = $this->getProperty($field);
				$headers .= "    array('lib' => '" . ucfirst(str_replace("_", " ", $field)) . "')," . "\n";
				$cols    .= '            $row->get' . ucfirst($field) .'(),' . "\n";
			}
		}
		
		$canevas = str_replace('<<HEADERS>>', $headers, $canevas);
		$canevas = str_replace('<<COLS>>', $cols, $canevas);
		$canevas = str_replace('<<CLASS>>', $class, $canevas);
		
		return $canevas;
	}
	
	/**
	 * Contenu de la vue info
	 * @param string $table
	 * @param array $fields
	 * @param string $namespace
	 */
	public function viewInfo($table, $namespace="Application_")
	{
		$canevas = file_get_contents('canevas/view_info.txt');
		$class = strtolower($table);
		$class_uc = $this->className($table);
		$classname = $namespace . 'Model_' . $class_uc;

		// Rows
		$rows = '';
		foreach ($this->getColonnesName($table) as $field) {
			if ($field != '') {
				$dbField = $field;
				$field = $this->getProperty($field);
				$rows    .= '$rows[] = array(\'' . ucfirst(str_replace('_', ' ', $dbField)) . '\' => $model->get' . ucfirst($field) .'());' . "\n";
			}
		}
		
		$canevas = str_replace('<<CLASSNAME>>', $classname, $canevas);
		$canevas = str_replace('<<ROWS>>', $rows, $canevas);
		$canevas = str_replace('<<CLASSUC>>', $class_uc, $canevas);
		
		return $canevas;
	}
	
	/**
	 * Contenu de la vue edit
	 */
	public function viewEdit($table=null, $namespace='')
	{
		$canevas = file_get_contents('canevas/view_edit.txt');
		return $canevas;
	}

	/**
	 * Contenu de la vue suppr
	 */
	public function viewSuppr($table=null, $namespace='')
	{
		$canevas = file_get_contents('canevas/view_suppr.txt');
		return $canevas;
	}
}
?>