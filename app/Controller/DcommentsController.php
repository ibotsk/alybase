<?php
//require_once 'D:\Apache Software Foundation\xampp\htdocs\AlyssumPortalCake\app\Lib\dBug.php';
App::uses('AppController', 'Controller');

class DcommentsController extends AppController {

    public $uses = array('Dcomment');

    public function add() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $data['Dcomment']['parent_id'] = ($data['Dcomment']['parent_id'] == 'null' ? null : $data['Dcomment']['parent_id']);
            $this->Dcomment->save($data);
            //$this->set(compact('data'));
            //$this->redirect($this->request->referer());
            return true;
        }
        return false;
    }

}
