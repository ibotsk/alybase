<?php

App::uses('AppModel', 'Model');

class WorldL2 extends AppModel {

    public $useTable = 'world_l2';
    
    public $belongsTo = array(
        'WorldL1' => array(
            'className' => 'WorldL1',
            'foreignKey' => 'id_parent'
        )
    );
    
    public $hasMany = array(
        'WorldL3' => array(
            'className' => 'WorldL3',
            'foreignKey' => 'id_parent',
        )
    );
    
}