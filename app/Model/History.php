<?php

App::uses('AppModel', 'Model');

class History extends AppModel {

    public $useTable = 'history';
    
    public $belongsTo = array(
        'Cdata' => array(
            'className' => 'Cdata',
            'foreignKey' => 'id_data'
        ),
        'ListOfSpecies' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_standardised_name'
        )
    );
    
}
