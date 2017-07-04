<?php

App::uses('AppModel', 'Model');
class Dcomment extends AppModel {
    
    public $actsAs = array('Tree');
    
    public $validate = array(
        'username' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Fill in your name'
        ),
        'annotation' => array(
            'rule' => 'notEmpty',
            'required' => true,
            'allowEmpty' => false,
            'message' => 'Fill in your name'
        )
    );
    
    public $belongsTo = array(
        'Cdata' => array(
            'className' => 'Cdata',
            'foreignKey' => 'id_cdata'
        )/*,
        'Parent' => array(
            'className' => 'Dcomment',
            'foreignKey' => 'parent_id'
        )*/
    );
    /*
    public $hasMany = array(
        'Children' => array(
            'className' => 'Dcomment',
            'foreignKey' => 'parent_id',
            'dependent' => true,
            'order' => 'Children.date_posted'
        )
    );*/
    
}
