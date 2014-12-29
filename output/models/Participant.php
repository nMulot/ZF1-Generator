<?php
/**
 * Model_Participant.php
 * @author HUGOCORP DR <contact@hugocorp.com>
 */
 
class Model_Participant
{
    protected $_mapper;
    protected $_id;
    protected $_date_insert;
    protected $_nom;
    protected $_prenom;
    protected $_mail;
    protected $_tel_mobile;
    protected $_nb_invites;
    protected $_montant;
    protected $_mode_reglement;
    protected $_encaisse;
    protected $_users_id;
    protected $_event_id;


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
            throw new Exception('Invalid participant property');
        }
        $this->$method($value);
    }
 
    public function __get($name)
    {
        $method = 'get' . $name;
        if (('mapper' == $name) || !method_exists($this, $method)) {
            throw new Exception('Invalid participant property');
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
            $this->setMapper(new Model_ParticipantMapper());
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

    public function getDate_insert()
    {
        return $this->_date_insert;
    }

    public function setDate_insert($date_insert)
    {
        $this->_date_insert = $date_insert;
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

    public function getMail()
    {
        return $this->_mail;
    }

    public function setMail($mail)
    {
        $this->_mail = $mail;
        return $this;
    }

    public function getTel_mobile()
    {
        return $this->_tel_mobile;
    }

    public function setTel_mobile($tel_mobile)
    {
        $this->_tel_mobile = $tel_mobile;
        return $this;
    }

    public function getNb_invites()
    {
        return $this->_nb_invites;
    }

    public function setNb_invites($nb_invites)
    {
        $this->_nb_invites = $nb_invites;
        return $this;
    }

    public function getMontant()
    {
        return $this->_montant;
    }

    public function setMontant($montant)
    {
        $this->_montant = $montant;
        return $this;
    }

    public function getMode_reglement()
    {
        return $this->_mode_reglement;
    }

    public function setMode_reglement($mode_reglement)
    {
        $this->_mode_reglement = $mode_reglement;
        return $this;
    }

    public function getEncaisse()
    {
        return $this->_encaisse;
    }

    public function setEncaisse($encaisse)
    {
        $this->_encaisse = $encaisse;
        return $this;
    }

    public function getUsers_id()
    {
        return $this->_users_id;
    }

    public function setUsers_id($users_id)
    {
        $this->_users_id = $users_id;
        return $this;
    }

    public function getEvent_id()
    {
        return $this->_event_id;
    }

    public function setEvent_id($event_id)
    {
        $this->_event_id = $event_id;
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