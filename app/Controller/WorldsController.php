<?php

App::uses('AppController', 'Controller');

class WorldsController extends AppController {

    public $uses = array('WorldL1', 'WorldL2', 'WorldL3', 'WorldL4');
    
    public function view($type) {
        $this->autoRender = false;
        if ($this->request->is('requested')) {
            $result = array();
            $options = array('fields' => array('id', 'description'), 'order' => 'description');
            switch ($type) {
                case 1:
                    $result = $this->WorldL1->find('list', $options);
                    break;
                case 2:
                    $result = $this->WorldL2->find('list', $options);
                    break;
                case 3:
                    $result = $this->WorldL3->find('list', $options);
                    break;
                case 4:
                    $result = $this->WorldL4->find('list', $options);
                    break;
                default:
                    break;
            }
            return $result;
        }
    }

}
