<?php

App::uses('AppModel', 'Model');

class Material extends AppModel {

    public $useTable = 'material';
    
    public $hasOne = array(
        'Cdata' => array(
            'className' => 'Cdata',
            'foreignKey' => 'id_material'
        )
    );
    
    public $belongsTo = array(
        'Reference' => array(
            'className' => 'Reference',
            'foreignKey' => 'id_reference'
        ),
        'WorldL1' => array(
            'className' => 'WorldL4',
            'foreignKey' => 'id_world_4'
        ),
        'PersonsCol' => array(
            'className' => 'Person',
            'foreignKey' => 'collected_by'
        ),
        'PersonsIdf' => array(
            'className' => 'Person',
            'foreignKey' => 'identified_by'
        ),
        'PersonsChk' => array(
            'className' => 'Person',
            'foreignKey' => 'checked_by'
        ),
        'PhytogeoDistrict' => array(
            'className' => 'PhytogeographicalDistrict',
            'foreignKey' => 'phytogeographical_district'
        )
    );
    
}
