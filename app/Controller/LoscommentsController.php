<?php
//require_once 'D:\Apache Software Foundation\xampp\htdocs\AlyssumPortalCake\app\Lib\dBug.php';
App::uses('AppController', 'Controller');

class LoscommentsController extends AppController {

    public $uses = array('LosComment');

    public function add() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['LosComment']['parent_id'] = ($data['LosComment']['parent_id'] == 'null' ? null : $data['LosComment']['parent_id']);
            $this->LosComment->save($data);
            //$this->set(compact('data'));
            return true;
        }
        return false;
    }

}

