<?php

//require_once 'D:\Apache Software Foundation\xampp\htdocs\AlyssumPortalCake\app\Lib\dBug.php';
App::uses('AppController', 'Controller');

class LiteraturesController extends AppController {

    public $uses = array('Literature');
    public $helpers = array('Html', 'Format');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('page', 'data');
    }

    public function index() {
        $literatures = $this->Literature->find('all', array('order' => array('Literature.paper_author', 'Literature.year', 'Literature.paper_title', 'Literature.id')));
        $this->set(compact('literatures'));
    }

    public function search() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $options = array('conditions' => array(),
                'order' => array('Literature.paper_author', 'Literature.year', 'Literature.paper_title', 'Literature.id'));
            if ($data['Filter']['author']) {
                $tokens = array_map('strtolower', array_map('trim', explode(',', $data['Filter']['author'])));
                foreach ($tokens as $ath) {
                    $options['conditions'][] = array('Literature.paper_author ILIKE' => '%' . $ath . '%');
                }
            }
            if ($data['Filter']['year']) {
                $options['conditions']['Literature.year'] = $data['Filter']['year'];
            }
            $literatures = $this->Literature->find('all', $options);
            $this->set(compact('literatures'));
        }
    }

    public function view() {
        $this->autoRender = false;
        if ($this->request->is('requested')) {
            $persons = $this->Literature->find('list', array(
                'fields' => array('Literature.paper_author'),
                'order' => 'Literature.paper_author'
            ));
            return $this->_atomiseAuthors($persons);
        }
    }

    private function _atomiseAuthors($authors) {
        $atomised = array();
        foreach ($authors as $value) {
            $tokens = array_map('trim', explode(',', trim($value)));
            foreach ($tokens as $t) {
                if (!key_exists($t, $atomised)) {
                    $atomised[$t] = $t;
                }
            }
        }
        ksort($atomised);
        return $atomised;
    }

}
