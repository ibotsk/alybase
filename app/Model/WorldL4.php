<?php

App::uses('AppModel', 'Model');

class WorldL4 extends AppModel {

    public $useTable = 'world_l4';
   
    public $hasMany = array(
        'Material' => array(
            'className' => 'Material',
            'foreignKey' => 'id_world_1',
            'dependent' => false
        ),
    );
    
    public $belongsTo = array(
        'WorldL3' => array(
            'className' => 'WorldL3',
            'foreignKey' => 'id_parent'
        )
    );
    
}
