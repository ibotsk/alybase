<?php

App::uses('AppModel', 'Model');

class LosComment extends AppModel {

    public $actsAs = array('Tree');
    
    public $validate = array(
        'username' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Fill in your name'
        ),
        'institution' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Fill in your institution'
        ),
        'annotation' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Fill in annotation'
        )
    );
    
    public $belongsTo = array(
        'ListOfSpecies' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_list_of_species'
        )
    );
    
}

