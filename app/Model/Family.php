<?php

App::uses('AppModel', 'Model');

class Family extends AppModel {

    public $useTable = 'family';
    
    public $hasMany = array(
        'ListOfSpecies' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_family',
        )
    );
    
}
