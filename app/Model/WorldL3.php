<?php

App::uses('AppModel', 'Model');

class WorldL3 extends AppModel {

    public $useTable = 'world_l3';
    
    public $belongsTo = array(
        'WorldL2' => array(
            'className' => 'WorldL2',
            'foreignKey' => 'id_parent'
        )
    );
    
    public $hasMany = array(
        'WorldL4' => array(
            'className' => 'WorldL4',
            'foreignKey' => 'id_parent',
        )
    );
    
}
