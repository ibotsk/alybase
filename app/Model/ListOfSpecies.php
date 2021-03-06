<?php

App::uses('AppModel', 'Model');

class ListOfSpecies extends AppModel {

    public $useTable = 'list_of_species';
    public $hasMany = array(
        'History' => array(
            'className' => 'History',
            'foreignKey' => 'id_standardised_name',
        ),
        'LatestRevision' => array(
            'className' => 'LatestRevision',
            'foreignKey' => 'id_standardised_name'
        ),
        'Reference' => array(
            'className' => 'Reference',
            'foreignKey' => 'id_standardised_name'
        ),
        'Synonyms' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_accepted_name',
            'conditions' => array(
                /*'OR' => array(*/'Synonyms.syn_type' => 2, /*array('Synonyms.syn_type' => 3, 'Synonyms.id_basionym' => null)),*/
                'Synonyms.is_basionym' => false,
                'Synonyms.id_superior_name' => null,
                //'Synonyms.ntype !=' => 0
                //array('OR' => array(
                        /*array(
                            array('OR' => array('Synonyms.subsp != Synonyms.species', 'Synonyms.subsp' => null)),
                            array('OR' => array('Synonyms.var != Synonyms.species', 'Synonyms.var' => null)),
                            array('OR' => array('Synonyms.forma != Synonyms.species', 'Synonyms.forma' => null))
                        ),*/
                //        'Synonyms.id_superior_name' => null
                //)
            ),
            'order' => array('Synonyms.genus', 'Synonyms.species',
                'Synonyms.subsp', 'Synonyms.var', 'Synonyms.subvar',
                'Synonyms.forma', 'Synonyms.authors')
        ),
        'SynonymsInvalid' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_accepted_name',
            'conditions' => array('SynonymsInvalid.syn_type' => 1),
            'order' => array('SynonymsInvalid.genus', 'SynonymsInvalid.species',
                'SynonymsInvalid.subsp', 'SynonymsInvalid.var', 'SynonymsInvalid.subvar',
                'SynonymsInvalid.forma', 'SynonymsInvalid.authors')
        ),
        'BasionymFor' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_basionym',
        //'conditions' => array('BasionymFor.ntype !=' => '')
        ),
        'NomenNovumFor' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_nomen_novum'
        ),
        'ReplacedFor' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_replaced'
        ),
        'LosComment' => array(
            'className' => 'LosComment',
            'foreignKey' => 'id_list_of_species',
            'dependent' => true,
            'order' => 'LosComment.date_posted'
        )
    );
    public $belongsTo = array(
        /* 'Family' => array(
          'className' => 'Family',
          'foreignKey' => 'id_family'
          ), */
        'Accepted' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_accepted_name'
        ),
        'Basionym' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_basionym'
        ),
        'Replaced' => array(
            'className' => 'ListOfSpecies',
            'foreignKey' => 'id_replaced'
        )
    );

}
