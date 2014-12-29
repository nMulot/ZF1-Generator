<?php

class EventController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->title = "Events";
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        
        if ($this->view->isAjaxRequest()) {
            $this->_helper->layout()->disableLayout();
        }
    }

    public function indexAction()
    {
        $model = new Model_Event();
        $this->view->rows = $model->liste();
    }

    public function editAction()
    {
        $form = new Form_EventEdit();
        $model = new Model_Event();
        $params = $this->getRequest()->getParams();
        $isUpdate = isset($params['uid']);
        
        $defaults = array();
        if ($isUpdate) {
            $id = $params['uid'];
            $obj = $model->info($id);
            $defaults = array(
                'id' => $obj->getId(),
                'nom' => $obj->getNom(),
                'date_heure' => $obj->getDate_heure(),
                'description' => $obj->getDescription(),
                'url' => $obj->getUrl(),
                'date_insert' => $obj->getDate_insert(),
                'actif' => $obj->getActif(),
                'prix' => $obj->getPrix()
            );
            $this->view->id = $id;
            $this->view->title = "Modification Event";
        } else {
            $this->view->title = "Création Event";
        }
        
        // Validation du form
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();

            if (isset($values['id']) && $values['id'] != null) {
                $model->info($values['id']);
            }
            $model                  ->setNom($values['nom'])
                  ->setDate_heure($values['date_heure'])
                  ->setDescription($values['description'])
                  ->setUrl($values['url'])
                  ->setDate_insert($values['date_insert'])
                  ->setActif($values['actif'])
                  ->setPrix($values['prix']);
                                
            // Sauvegarde des modifications
            $model->save();

            $this->_helper->redirector('index', 'event');
        }

        $form->setDefaults($defaults);
        $form->setAction('');
        $this->view->form = $form;
    }
    
    public function supprAction()
    {
        $model = new Model_Event();
        $params = $this->getRequest()->getParams();
        
        if ($this->getRequest()->isPost()) {
            if (isset($params['confirm_delete'])) { // Suppression confirmée
                $model->info($params['id']);
                if ($model->suppr()) { // id provenant du form
                    $this->_helper->flashMessenger->addMessage("Suppression effectuée avec succès");
                    $this->_helper->redirector('index', 'event');
                } else {
                    $this->_helper->flashMessenger->addMessage("Une erreur est survenue lors de la suppression");
                }
            } elseif (isset($params['cancel'])) {
                $this->_helper->flashMessenger->addMessage("Suppression annulée");
                $this->_helper->redirector('index', 'event');
            }
        } elseif (!isset($params['uid'])) {
            $this->_helper->flashMessenger->addMessage("Aucun enregistrement à supprimer");
            $this->_helper->redirector('index', 'event');
        }
        
        // Affichage du form de confirmation
        $this->view->id = $params['uid'];
    }

    public function infoAction()
    {
        $params = $this->getRequest()->getParams();
        
        if (!isset($params['uid'])) {
            $this->_helper->redirector('index', 'event');
        } 
        
        $this->view->uid = $params['uid'];            
        $this->view->title = "Détails Event";
    }

}
