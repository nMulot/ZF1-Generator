<?php

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->title = "Userss";
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        
        if ($this->view->isAjaxRequest()) {
            $this->_helper->layout()->disableLayout();
        }
    }

    public function indexAction()
    {
        $model = new Model_Users();
        $this->view->rows = $model->liste();
    }

    public function editAction()
    {
        $form = new Form_UsersEdit();
        $model = new Model_Users();
        $params = $this->getRequest()->getParams();
        $isUpdate = isset($params['uid']);
        
        $defaults = array();
        if ($isUpdate) {
            $id = $params['uid'];
            $obj = $model->info($id);
            $defaults = array(
                'id' => $obj->getId(),
                'nom' => $obj->getNom(),
                'prenom' => $obj->getPrenom(),
                'login' => $obj->getLogin(),
                'pass' => $obj->getPass(),
                'email' => $obj->getEmail(),
                'actif' => $obj->getActif(),
                'last_connect' => $obj->getLast_connect()
            );
            $this->view->id = $id;
            $this->view->title = "Modification Users";
        } else {
            $this->view->title = "Création Users";
        }
        
        // Validation du form
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();

            if (isset($values['id']) && $values['id'] != null) {
                $model->info($values['id']);
            }
            $model                  ->setNom($values['nom'])
                  ->setPrenom($values['prenom'])
                  ->setLogin($values['login'])
                  ->setPass($values['pass'])
                  ->setEmail($values['email'])
                  ->setActif($values['actif'])
                  ->setLast_connect($values['last_connect']);
                                
            // Sauvegarde des modifications
            $model->save();

            $this->_helper->redirector('index', 'users');
        }

        $form->setDefaults($defaults);
        $form->setAction('');
        $this->view->form = $form;
    }
    
    public function supprAction()
    {
        $model = new Model_Users();
        $params = $this->getRequest()->getParams();
        
        if ($this->getRequest()->isPost()) {
            if (isset($params['confirm_delete'])) { // Suppression confirmée
                $model->info($params['id']);
                if ($model->suppr()) { // id provenant du form
                    $this->_helper->flashMessenger->addMessage("Suppression effectuée avec succès");
                    $this->_helper->redirector('index', 'users');
                } else {
                    $this->_helper->flashMessenger->addMessage("Une erreur est survenue lors de la suppression");
                }
            } elseif (isset($params['cancel'])) {
                $this->_helper->flashMessenger->addMessage("Suppression annulée");
                $this->_helper->redirector('index', 'users');
            }
        } elseif (!isset($params['uid'])) {
            $this->_helper->flashMessenger->addMessage("Aucun enregistrement à supprimer");
            $this->_helper->redirector('index', 'users');
        }
        
        // Affichage du form de confirmation
        $this->view->id = $params['uid'];
    }

    public function infoAction()
    {
        $params = $this->getRequest()->getParams();
        
        if (!isset($params['uid'])) {
            $this->_helper->redirector('index', 'users');
        } 
        
        $this->view->uid = $params['uid'];            
        $this->view->title = "Détails Users";
    }

}
