<?php

App::uses('AppModel', 'Model');

class Person extends AppModel {

    public $useTable = 'persons';
    
    public $hasMany = array(
        'PersonsCtb' => array(
            'className' => 'Cdata',
            'foreignKey' => 'counted_by'
        ),
        'PersonsCol' => array(
            'className' => 'Material',
            'foreignKey' => 'collected_by'
        ),
        'PersonsIdf' => array(
            'className' => 'Material',
            'foreign_key' => 'identified_by'
        ),
        'PersonsChk' => array(
            'className' => 'Material',
            'foreign_key' => 'checked_by'
        )
    );
    
}