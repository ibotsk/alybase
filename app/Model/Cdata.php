<?php

App::uses('AppModel', 'Model');
class Cdata extends AppModel {
    
    public $useTable = 'cdata';
    
    public $belongsTo = array(
        'Material' => array(
            'className' => 'Material',
            'foreignKey' => 'id_material'
        ),
        'Dna' => array(
            'className' => 'Dna',
            'foreignKey' => 'id_dna'
        ),
        'CountedBy' => array(
            'className' => 'Person',
            'foreignKey' => 'counted_by'
        )
    );
    
    public $hasMany = array(
        'Dcomment' => array(
            'className' => 'Dcomment',
            'foreignKey' => 'id_cdata',
            'dependent' => true,
            'order' => 'Dcomment.date_posted'
        ),
        'History' => array(
            'className' => 'History',
            'foreignKey' => 'id_data',
            'dependent' => true,
            'order' => array('History.h_date', 'History.id DESC')
        )
    );
    
    public $hasOne = array(
        'LatestRevision' => array(
            'className' => 'LatestRevision',
            'foreignKey' => 'id_data',
        )
    );
    
}

