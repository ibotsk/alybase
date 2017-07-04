<?php

App::uses('AppController', 'Controller');

class PersonsController extends AppController {

    public $uses = array('Person');
    
    public function view() {
        $this->autoRender = false;
        if ($this->request->is('requested')) {
            $persons = $this->Person->find('list', array(
                'fields' => array('id', 'pers_name'),
                'order' => 'pers_name'
            ));
            return $persons;
        }
    }

}
