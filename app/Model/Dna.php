<?php

App::uses('AppModel', 'Model');

class Dna extends AppModel {

    public $useTable = 'dna';
    
    public $hasOne = array(
        'Cdata' => array(
            'className' => 'Cdata',
            'foreignKey' => 'id_dna'
        )
    );
    
}

