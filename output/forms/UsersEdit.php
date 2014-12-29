<?php

class Form_UsersEdit extends Zend_Form
{

    public function init()
    {
        $this->setMethod('post');
        
        $this->addElementPrefixPath('App_Decorator',
                            'App/Decorator/',
                            'decorator');  
                            
        // id
        $id = new Zend_Form_Element_Hidden('id');
        $this->addElement($id);
        
        // nom
        $nom = new Zend_Form_Element_Text('nom');
        $nom
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Nom');
        $this->addElement($nom);
        
        // prenom
        $prenom = new Zend_Form_Element_Text('prenom');
        $prenom
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Prenom');
        $this->addElement($prenom);
        
        // login
        $login = new Zend_Form_Element_Text('login');
        $login
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Login');
        $this->addElement($login);
        
        // pass
        $pass = new Zend_Form_Element_Text('pass');
        $pass
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Pass');
        $this->addElement($pass);
        
        // email
        $email = new Zend_Form_Element_Text('email');
        $email
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Email');
        $this->addElement($email);
        
        // actif
        $actif = new Zend_Form_Element_Text('actif');
        $actif
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Actif');
        $this->addElement($actif);
        
        // last_connect
        $last_connect = new Zend_Form_Element_Text('last_connect');
        $last_connect
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Last connect');
        $this->addElement($last_connect);
        
        
        // Bouton OK
        $valid = new Zend_Form_Element_Submit('valid');
        $valid->setValue('Valider')
              ->setLabel('');
        $this->addElement($valid);
        
        // DECORATOR
        $except = array();
        foreach ($this->getElements() as $element) {
            if (!in_array($element->getName(), $except)) {
                $element->setDecorators(array('Inline'));
            }
        }
    }
    
}
