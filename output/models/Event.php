<?php
/**
 * Model_Event.php
 * @author HUGOCORP DR <contact@hugocorp.com>
 */
 
class Model_Event
{
    protected $_mapper;
    protected $_id;
    protected $_nom;
    protected $_date_heure;
    protected $_description;
    protected $_url;
    protected $_date_insert;
    protected $_actif;
    protected $_prix;


    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value)
    {
        $method = 'set' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid event property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid event property');
        }
        return $this->$method();
    }
    
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }
    
    public function setMapper($mapper)
    {
        $this->_mapper = $mapper;
    }
    
    public function getMapper()
    {
        if (null === $this->_mapper) {
            $this->setMapper(new Model_EventMapper());
        }
        return $this->_mapper;
    }

    /**
     * GETTERS / SETTERS
     */
        public function getId()
    {
        return $this->_id;
    }

    public function setId($id)
    {
        $this->_id = $id;
        return $this;
    }

    public function getNom()
    {
        return $this->_nom;
    }

    public function setNom($nom)
    {
        $this->_nom = $nom;
        return $this;
    }

    public function getDate_heure()
    {
        return $this->_date_heure;
    }

    public function setDate_heure($date_heure)
    {
        $this->_date_heure = $date_heure;
        return $this;
    }

    public function getDescription()
    {
        return $this->_description;
    }

    public function setDescription($description)
    {
        $this->_description = $description;
        return $this;
    }

    public function getUrl()
    {
        return $this->_url;
    }

    public function setUrl($url)
    {
        $this->_url = $url;
        return $this;
    }

    public function getDate_insert()
    {
        return $this->_date_insert;
    }

    public function setDate_insert($date_insert)
    {
        $this->_date_insert = $date_insert;
        return $this;
    }

    public function getActif()
    {
        return $this->_actif;
    }

    public function setActif($actif)
    {
        $this->_actif = $actif;
        return $this;
    }

    public function getPrix()
    {
        return $this->_prix;
    }

    public function setPrix($prix)
    {
        $this->_prix = $prix;
        return $this;
    }


    
    
    /**
     * Retourne les informations sur un enregistrement grace à son id
     * @param int $id
     */
    public function info($id)
    {
        $this->getMapper()->find($id, $this);
        return $this;
    }
    
    /**
     * Retourne les enregistrement de la table correspondant aux clauses where, order et limit 
     * @param string $where Critère de sélection. ex : "type = 2" ou "date >= '2012-03-10'". Optionnel
     * @param string $order Order. ex : "date" ou "date ASC" ou array('date ASC', 'nom DESC') . Optionnel
     * @param int $limit Nombre d'enregistrements. Optionnel
     */
    public function liste($where=null, $order=null, $limit=null)
    {
        return $this->getMapper()->fetchAll($where, $order, $limit);
    }
    
    /**
     * Crée un enregistrement ou met à jour avec les informations de l'objet
     */
    public function save()
    {
        return $this->getMapper()->save($this);
    }
    
    /**
     * Supprime un enregistrement grace à son id
     * @param int $id
     */
    public function suppr()
    {
        if ($this->getId() == null) {
            throw new Exception("Aucun objet instancié à supprimer");
        }
        return $this->getMapper()->delete($this->getId());
    }
}