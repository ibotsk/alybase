<?php

App::uses('AppModel', 'Model');

class WorldL1 extends AppModel {

    public $useTable = 'world_l1';
    
    public $hasMany = array(
        'WorldL2' => array(
            'className' => 'WorldL2',
            'foreignKey' => 'id_parent'
        )
    );
    
}
