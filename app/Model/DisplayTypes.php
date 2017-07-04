<?php

App::uses('AppModel', 'Model');
class DisplayTypes extends AppModel {
    
    public $hasMany = array(
        'Literature' => array(
            'className' => 'Literature',
            'foreignKey' => 'display_type',
        )
    );
    
}