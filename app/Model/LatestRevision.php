<?php

App::uses('AppModel', 'Model');

class LatestRevision extends AppModel {

    public $useTable = 'v_latest_revision';
    
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
