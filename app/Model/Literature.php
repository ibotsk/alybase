<?php

App::uses('AppModel', 'Model');

class Literature extends AppModel {

    public $useTable = 'literature';
    
    public $belongsTo = array(
        'DisplayTypes' => array(
            'className' => 'DisplayTypes',
            'foreignKey' => 'display_type'
        )
    );
    
    public $hasMany = array(
        'Reference' => array(
            'className' => 'Reference',
            'foreignKey' => 'id_literature',
        )
    );
    
}

