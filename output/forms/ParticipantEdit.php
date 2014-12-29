<?php

class Form_ParticipantEdit extends Zend_Form
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
        
        // date_insert
        $date_insert = new Zend_Form_Element_Text('date_insert');
        $date_insert
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Date insert');
        $this->addElement($date_insert);
        
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
        
        // mail
        $mail = new Zend_Form_Element_Text('mail');
        $mail
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Mail');
        $this->addElement($mail);
        
        // tel_mobile
        $tel_mobile = new Zend_Form_Element_Text('tel_mobile');
        $tel_mobile
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Tel mobile');
        $this->addElement($tel_mobile);
        
        // nb_invites
        $nb_invites = new Zend_Form_Element_Text('nb_invites');
        $nb_invites
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Nb invites');
        $this->addElement($nb_invites);
        
        // montant
        $montant = new Zend_Form_Element_Text('montant');
        $montant
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Montant');
        $this->addElement($montant);
        
        // mode_reglement
        $mode_reglement = new Zend_Form_Element_Text('mode_reglement');
        $mode_reglement
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Mode reglement');
        $this->addElement($mode_reglement);
        
        // encaisse
        $encaisse = new Zend_Form_Element_Text('encaisse');
        $encaisse
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Encaisse');
        $this->addElement($encaisse);
        
        // users_id
        $users_id = new Zend_Form_Element_Text('users_id');
        $users_id
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Users id');
        $this->addElement($users_id);
        
        // event_id
        $event_id = new Zend_Form_Element_Text('event_id');
        $event_id
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Event id');
        $this->addElement($event_id);
        
        
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
