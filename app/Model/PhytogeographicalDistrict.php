<?php

App::uses('AppModel', 'Model');

class PhytogeographicalDistrict extends AppModel {

    public $useTable = 'phytogeographical_district';
    
    public $hasMany = array(
        'PhytogeoDistrict' => array(
            'className' => 'PhytogeographicalDistrict',
            'foreignKey' => 'phytogeographical_district',
        )
    );
    
}
