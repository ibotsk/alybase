<?php

App::uses('AppController', 'Controller');

class FamiliesController extends AppController {

    public $uses = array('Family');
    
    public function view() {
        $this->autoRender = false;
        if ($this->request->is('requested')) {
            $families = $this->Family->find('list', array(
                'fields' => array('id', 'name'),
                'order' => 'name'
            ));
            return $families;
        }
    }

}
