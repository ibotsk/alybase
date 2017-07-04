<?php

App::uses('AppModel', 'Model');

class Reference extends AppModel {

    public $useTable = 'reference';
    
    public $hasOne = array(
        'Material' => array(
            'className' => 'Material',
            'foreignKey' => 'id_reference',
            'dependent' => true
        )
    );
    
    public $belongsTo = array(
        'Literature' => array(
            'className' => 'Literature',
            'foreignKey' => 'id_literature'
        ),
        'ListOfSpecies' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_standardised_name'
        )
    );
    
}
