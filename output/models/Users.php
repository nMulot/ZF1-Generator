<?php
/**
 * Model_Users.php
 * @author HUGOCORP DR <contact@hugocorp.com>
 */
 
class Model_Users
{
    protected $_mapper;
    protected $_id;
    protected $_nom;
    protected $_prenom;
    protected $_login;
    protected $_pass;
    protected $_email;
    protected $_actif;
    protected $_last_connect;


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
            throw new Exception('Invalid users property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid users property');
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
            $this->setMapper(new Model_UsersMapper());
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

    public function getPrenom()
    {
        return $this->_prenom;
    }

    public function setPrenom($prenom)
    {
        $this->_prenom = $prenom;
        return $this;
    }

    public function getLogin()
    {
        return $this->_login;
    }

    public function setLogin($login)
    {
        $this->_login = $login;
        return $this;
    }

    public function getPass()
    {
        return $this->_pass;
    }

    public function setPass($pass)
    {
        $this->_pass = $pass;
        return $this;
    }

    public function getEmail()
    {
        return $this->_email;
    }

    public function setEmail($email)
    {
        $this->_email = $email;
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

    public function getLast_connect()
    {
        return $this->_last_connect;
    }

    public function setLast_connect($last_connect)
    {
        $this->_last_connect = $last_connect;
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