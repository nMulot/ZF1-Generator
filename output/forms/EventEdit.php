<?php

class Form_EventEdit extends Zend_Form
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
        
        // date_heure
        $date_heure = new Zend_Form_Element_Text('date_heure');
        $date_heure
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Date heure');
        $this->addElement($date_heure);
        
        // description
        $description = new Zend_Form_Element_Text('description');
        $description
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Description');
        $this->addElement($description);
        
        // url
        $url = new Zend_Form_Element_Text('url');
        $url
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Url');
        $this->addElement($url);
        
        // date_insert
        $date_insert = new Zend_Form_Element_Text('date_insert');
        $date_insert
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Date insert');
        $this->addElement($date_insert);
        
        // actif
        $actif = new Zend_Form_Element_Text('actif');
        $actif
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Actif');
        $this->addElement($actif);
        
        // prix
        $prix = new Zend_Form_Element_Text('prix');
        $prix
            ->setRequired(true)
            ->setAttrib('class','med')
            ->setLabel('Prix');
        $this->addElement($prix);
        
        
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
