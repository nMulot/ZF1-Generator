<?php

class ParticipantController extends Zend_Controller_Action
{

    public function init()
    {
        $this->view->title = "Participants";
        $this->view->messages = $this->_helper->flashMessenger->getMessages();
        
        if ($this->view->isAjaxRequest()) {
            $this->_helper->layout()->disableLayout();
        }
    }

    public function indexAction()
    {
        $model = new Model_Participant();
        $this->view->rows = $model->liste();
    }

    public function editAction()
    {
        $form = new Form_ParticipantEdit();
        $model = new Model_Participant();
        $params = $this->getRequest()->getParams();
        $isUpdate = isset($params['uid']);
        
        $defaults = array();
        if ($isUpdate) {
            $id = $params['uid'];
            $obj = $model->info($id);
            $defaults = array(
                'id' => $obj->getId(),
                'date_insert' => $obj->getDate_insert(),
                'nom' => $obj->getNom(),
                'prenom' => $obj->getPrenom(),
                'mail' => $obj->getMail(),
                'tel_mobile' => $obj->getTel_mobile(),
                'nb_invites' => $obj->getNb_invites(),
                'montant' => $obj->getMontant(),
                'mode_reglement' => $obj->getMode_reglement(),
                'encaisse' => $obj->getEncaisse(),
                'users_id' => $obj->getUsers_id(),
                'event_id' => $obj->getEvent_id()
            );
            $this->view->id = $id;
            $this->view->title = "Modification Participant";
        } else {
            $this->view->title = "Création Participant";
        }
        
        // Validation du form
        if ($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getPost())) {
            $values = $form->getValues();

            if (isset($values['id']) && $values['id'] != null) {
                $model->info($values['id']);
            }
            $model                  ->setDate_insert($values['date_insert'])
                  ->setNom($values['nom'])
                  ->setPrenom($values['prenom'])
                  ->setMail($values['mail'])
                  ->setTel_mobile($values['tel_mobile'])
                  ->setNb_invites($values['nb_invites'])
                  ->setMontant($values['montant'])
                  ->setMode_reglement($values['mode_reglement'])
                  ->setEncaisse($values['encaisse'])
                  ->setUsers_id($values['users_id'])
                  ->setEvent_id($values['event_id']);
                                
            // Sauvegarde des modifications
            $model->save();

            $this->_helper->redirector('index', 'participant');
        }

        $form->setDefaults($defaults);
        $form->setAction('');
        $this->view->form = $form;
    }
    
    public function supprAction()
    {
        $model = new Model_Participant();
        $params = $this->getRequest()->getParams();
        
        if ($this->getRequest()->isPost()) {
            if (isset($params['confirm_delete'])) { // Suppression confirmée
                $model->info($params['id']);
                if ($model->suppr()) { // id provenant du form
                    $this->_helper->flashMessenger->addMessage("Suppression effectuée avec succès");
                    $this->_helper->redirector('index', 'participant');
                } else {
                    $this->_helper->flashMessenger->addMessage("Une erreur est survenue lors de la suppression");
                }
            } elseif (isset($params['cancel'])) {
                $this->_helper->flashMessenger->addMessage("Suppression annulée");
                $this->_helper->redirector('index', 'participant');
            }
        } elseif (!isset($params['uid'])) {
            $this->_helper->flashMessenger->addMessage("Aucun enregistrement à supprimer");
            $this->_helper->redirector('index', 'participant');
        }
        
        // Affichage du form de confirmation
        $this->view->id = $params['uid'];
    }

    public function infoAction()
    {
        $params = $this->getRequest()->getParams();
        
        if (!isset($params['uid'])) {
            $this->_helper->redirector('index', 'participant');
        } 
        
        $this->view->uid = $params['uid'];            
        $this->view->title = "Détails Participant";
    }

}
