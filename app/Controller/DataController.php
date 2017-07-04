<?php

//require_once 'D:\Apache Software Foundation\xampp\htdocs\AlyssumPortalCake\app\Lib\dBug.php';
App::uses('AppController', 'Controller');
App::uses('dBug', 'dBug');

class DataController extends AppController {

    public $helpers = array('Html', 'Form', 'Format');
    public $uses = array('Cdata', 'Dcomment', 'History', 'LatestRevision', 'ListOfSpecies', 'Literature', 'Material', 'Person', 'Reference', 'WorldL4', 'WorldL3', 'WorldL2', 'WorldL1');
    public $components = array('RequestHandler');

    public function beforeFilter() {
        parent::beforeFilter();
        $this->set('page', 'data');
    }

    public function index() {
        
    }

    public function search() {
        if ($this->request->is('post')) {
            $data = $this->request->data;
            $this->_searchBasic($data['Filter']);
        }
    }

    public function literature($id) {
        $data = array('outtype' => 2, 'literatureId' => $id);
        $this->_searchBasic($data);
        $this->render('search');
    }

    private function _searchBasic($data) {
        $type = $data['outtype'];
        $conditions = $this->_prepareConditions($data, $data['outtype']);
        $cdata = array();
        switch ($type) {
            case 1:
                $cdata = $this->_lastRevision($conditions);
                break;
            case 2:
                $cdata = $this->_allNames($conditions);
                break;
            case 3:
                $cdata = $this->_origNamesAll($conditions);
                break;
            default:
                break;
        }
        $results = $this->_searchOutput($cdata, $type);
//$results = $cdata;
        $this->set(compact('results', 'type'));
    }

    public function chromajax() {
        if ($this->request->is('ajax')) {
            $results = $this->_chromajax($this->request->data);
            $this->set(compact('results'));
            $this->render('chromajax', 'ajax');
        }
    }

    public function chromajaxmap() {
        if ($this->request->is('ajax')) {
            //$results = $this->request->data;
            $results = $this->_chromajax($this->request->data);
            /* foreach ($results as &$r) {
              $this->ListOfSpecies->unbindModel($params)
              } */
            $this->set(compact('results'));
            $this->render('chromajaxmap', 'ajax');
        }
    }

    public function ploidyautosuggest() {
        if ($this->request->is('ajax')) {
            $data = $this->request->data;
            $this->Cdata->unbindModel(array('hasMany' => array('Dcomment', 'History'),
                'belongsTo' => array('Material', 'Dna', 'CountedBy'),
                'hasOne' => array('LatestRevision')));
            if ($data['type'] == 'dn') {
                $options = array(
                    'fields' => array('Cdata.dn'),
                    'conditions' => array('Cdata.dn LIKE' => '%' . $data['term'] . '%'),
                    'recursive' => 0,
                    'order' => 'Cdata.dn');
            } else {
                $options = array(
                    'fields' => array('Cdata.n'),
                    'conditions' => array('Cdata.n LIKE' => '%' . $data['term'] . '%'),
                    'recursive' => 0,
                    'order' => 'Cdata.n');
            }
            $dns = $this->Cdata->find('list', $options);
            $results = array_unique($dns, SORT_REGULAR);
            $this->set(compact('results'));
            $this->render('ploidyautosuggest', 'ajax');
        }
    }

    public function detail($id) {
        if (!$id) {
            throw new NotFoundException(__('Record not found'));
        }
        $cdata = $this->Cdata->findById($id);
        if (!$cdata) {
            throw new NotFoundException(__('Record not found'));
        }
        $cdata['Reference'] = $this->Reference->findById($cdata['Material']['id_reference']);
        $cdata['Reference']['ListOfSpecies']['accepted'] = $this->ListOfSpecies->findById($cdata['Reference']['ListOfSpecies']['id_accepted_name']);
        $cdata['Material']['Worlds'] = $this->WorldL4->find('all', array(
            'fields' => array('WorldL4.description', 'WorldL3.description', 'WorldL2.description', 'WorldL1.description'),
            'joins' => $this->_prepareWorldJoins(),
            'conditions' => array('WorldL4.id' => $cdata['Material']['id_world_4'])));
        $cdata['ListOfSpeciesNewest'] = $this->ListOfSpecies->findById($cdata['LatestRevision']['id_standardised_name']);
        $tree = $this->Dcomment->generateTreeList(
                array('Dcomment.id_cdata' => $cdata['Cdata']['id']), null, null, '_'
        );
        $comments = array();
        foreach ($tree as $id => $val) {
            $comment = $this->Dcomment->find('first', array(
                'fields' => array('Dcomment.id', 'Dcomment.username', 'Dcomment.annotation', 'Dcomment.date_posted'),
                'conditions' => array('Dcomment.id' => $id, 'Dcomment.approved' => true)));
            $comment['Dcomment']['nested'] = substr_count($val, '_');
            $comments[] = $comment;
        }
        $this->set(compact('cdata', 'comments'));
    }

    public function view_rtf() {
        set_time_limit(0);
        $request = $this->request->data;
        $ids = $request['Data']['exportIds'];
        $explIds = explode('|', $ids);
        $cdataIds = array();
        if (count($explIds) > 0) {
            $type = $request['Data']['exportType'];
            $conditions = array();
            foreach ($explIds as $losid) {
                $cdatas = $this->_chromosomes($type, $losid, $conditions);
                $cdataIds = array_merge($cdataIds, Set::classicExtract($cdatas, '{n}.Cdata.id'));
            }
        }
        $data = $this->Cdata->find('all', array(
            'conditions' => array('Cdata.id' => $cdataIds)));
        foreach ($data as &$d) {
            $d['Reference'] = $this->Reference->findById($d['Material']['id_reference']);
            $d['Reference']['ListOfSpecies']['accepted'] = $this->ListOfSpecies->findById($d['Reference']['ListOfSpecies']['id_accepted_name']);
            $d['Material']['Worlds'] = $this->WorldL4->find('all', array(
                'fields' => array('WorldL4.description', 'WorldL3.description', 'WorldL2.description', 'WorldL1.description'),
                'joins' => $this->_prepareWorldJoins(),
                'conditions' => array('WorldL4.id' => $d['Material']['id_world_4'])));
            $d['ListOfSpeciesNewest'] = $this->ListOfSpecies->findById($d['LatestRevision']['id_standardised_name']);
        }
        ini_set('memory_limit', '512M');

        $this->set('data', $data);
    }

    private function _chromajax($data) {
        $conditions = array();
        $this->_prepareConditionsCommon($data, $conditions);
        $results = $this->_chromosomes($data['type'], $data['subj'], $conditions);
        return $results;
    }

    private function _lastRevision($conditions) {
        $options = $this->_lastRevisionOptions($conditions); //LastRevision();
        $cdata = $this->LatestRevision->find('all', $options);
        /* foreach ($cdata as &$value) {
          $value['ListOfSpecies']['type'] = 'revision';
          } */
        return $cdata;
    }

    private function _origNames($conditions) {
        $optionsOrig = $this->_origNamesOptions($conditions);
        $cdata = $this->Cdata->find('all', $optionsOrig);
        return $cdata;
    }

    private function _allNames($conditions) {
        $cdata = array_merge($this->_origNames($conditions)/* , $this->_lastRevision($conditions) */);
        return $cdata;
    }

    private function _origNamesAll($conditions) {
        $cdata = array_merge($this->_origNames($conditions), $this->_lastRevision($conditions));
        $acc_names = Set::classicExtract($cdata, '{n}.Accepted.id');
        $condit = array('ListOfSpecies.id' => $acc_names);
        return array_merge($cdata, $this->_origNames($condit), $this->_lastRevision($condit));
    }

    private function _chromosomes($type, $subj, $conditions) {
        switch ($type) {
            case 0:
                return $subj;
            case 1:  //records after last revision
                $conditions['LatestRevision.id_standardised_name'] = $subj;
                break;
            case 2: //records with original identification only
                /* $conditions[] = array('OR' => array(
                  'Reference.id_standardised_name' => $subj,
                  'LatestRevision.id_standardised_name' => $subj
                  )
                  ); */
                $conditions['Reference.id_standardised_name'] = $subj;
                break;
            case 3: //all connections
                $conditions[] = array('OR' => array(
                        'Reference.id_standardised_name' => $subj,
                        'History.id_standardised_name' => $subj
                    )
                );
                break;
            default:
                break;
        }
        $options = array(
            //'contain' => array('Material', 'Literature', 'ListOfSpecies'),
            'fields' => array('Cdata.id', 'Cdata.n', 'Cdata.dn',
                'Dna.method', 'Dna.ch_count', 'Dna.ploidy', 'Dna.size_c', 'Dna.size_from', 'Dna.size_to', 'Dna.size_units',
                'Literature.paper_author', 'Literature.paper_title',
                'Literature.series_source', 'Literature.volume',
                'Literature.issue', 'Literature.publisher',
                'Literature.editor', 'Literature.year',
                'Literature.pages', 'Literature.journal_name',
                'Literature.display_type', 'LatestRevision.id', 'LatestRevision.id_standardised_name',
            ),
            'joins' => $this->_prepareJoinsChrom(),
            'conditions' => $conditions
        );
        $this->Cdata->unbindModel(array('hasMany' => array('Dcomment')));
        $cdata = array_unique($this->Cdata->find('all', $options), SORT_REGULAR);
        return $cdata;
    }

    private function _lastRevisionOptions($conditions) {
        $options = array(
            'fields' => array(
                'ListOfSpecies.id', 'ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors',
                'ListOfSpecies.hybrid', 'ListOfSpecies.genus_h', 'ListOfSpecies.species_h', 'ListOfSpecies.subsp_h', 'ListOfSpecies.var_h', 'ListOfSpecies.subvar_h', 'ListOfSpecies.forma_h', 'ListOfSpecies.authors_h',
                'ListOfSpecies.publication', 'ListOfSpecies.syn_type', 'ListOfSpecies.tribus',
                'Accepted.id', 'Accepted.genus', 'Accepted.species', 'Accepted.subsp', 'Accepted.var', 'Accepted.subvar', 'Accepted.forma', 'Accepted.authors',
                'Accepted.hybrid', 'Accepted.genus_h', 'Accepted.species_h', 'Accepted.subsp_h', 'Accepted.var_h', 'Accepted.subvar_h', 'Accepted.forma_h', 'Accepted.authors_h',
                'Accepted.publication', 'Accepted.syn_type', 'Accepted.tribus'),
            'joins' => $this->_prepareJoinsHistory(),
            'order' => array('ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors'),
            'conditions' => $conditions
        );
        return $options;
    }

    private function _origNamesOptions($conditions) {
        $options = array(
            'fields' => array(
                'ListOfSpecies.id', 'ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors',
                'ListOfSpecies.hybrid', 'ListOfSpecies.genus_h', 'ListOfSpecies.species_h', 'ListOfSpecies.subsp_h', 'ListOfSpecies.var_h', 'ListOfSpecies.subvar_h', 'ListOfSpecies.forma_h', 'ListOfSpecies.authors_h',
                'ListOfSpecies.publication', 'ListOfSpecies.syn_type', 'ListOfSpecies.tribus', 
                'Accepted.id', 'Accepted.genus', 'Accepted.species', 'Accepted.subsp', 'Accepted.var', 'Accepted.subvar', 'Accepted.forma', 'Accepted.authors',
                'Accepted.hybrid', 'Accepted.genus_h', 'Accepted.species_h', 'Accepted.subsp_h', 'Accepted.var_h', 'Accepted.subvar_h', 'Accepted.forma_h', 'Accepted.authors_h',
                'Accepted.publication', 'Accepted.syn_type', 'Accepted.tribus'),
            'joins' => $this->_prepareJoinsAllNames(),
            'order' => array('ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors'),
            'conditions' => $conditions
        );
        return $options;
    }

    private function _allPublNames($conditions) {
        $options = array(
            'fields' => array('DISTINCT Reference.name_as_published', 'Reference.id', 'ListOfSpecies.id_accepted_name'),
            'joins' => $this->_prepareJoinsAllNames(),
            'order' => 'Reference.name_as_published',
            'conditions' => $conditions
        );
        return $options;
    }

    /*
      private function _allNamesNoSynOptions($conditions) {
      $options = array(
      'fields' => array('DISTINCT Reference.name_as_published', 'Reference.id', 'ListOfSpecies.id_accepted_name'),
      'joins' => $this->_prepareJoinsAllNames(),
      'conditions' => array('ListOfSpecies.id_accepted_name' => NULL),
      'order' => 'Reference.name_as_published'
      );
      return $options;
      } */

    /**
     * select los.*
     * from history h
     * left join list_of_species los on h.id_standardised_name = los.id
     * left join list_of_species losa on los.id_accepted_name = losa.id
     * left join cdata c on h.id_data = c.id
     * left join material m on c.id_material = m.id
     * left join reference r on m.id_reference = r.id
     * left join literature l on r.id_literature = l.id
     * left join world_l4 w4 on m.id_world_4 = w4.id
     * left join world_l3 w3 on w4.id_parent = w3.id
     * left join world_l2 w2 on w3.id_world_4 = w2.id
     * left join world_l1 w1 on w2.id_parent = w1.id
     */
    private function _prepareJoinsHistory() {
        $arrays = array(
            array(
                'table' => 'material',
                'alias' => 'Material',
                'type' => 'LEFT',
                'conditions' => array(
                    'Cdata.id_material = Material.id'
                )
            ),
            array(
                'table' => 'reference',
                'alias' => 'Reference',
                'type' => 'LEFT',
                'conditions' => array(
                    'Reference.id = Material.id_reference'
                )
            ), array(
                'table' => 'dna',
                'alias' => 'Dna',
                'type' => 'LEFT',
                'conditions' => array(
                    'Cdata.id_dna = Dna.id'
                )
            )
        );
        $joins = array_merge($arrays, $this->_prepareBasicJoins());
        return $joins;
    }

    private function _prepareJoinsAllNames() {
        $arrays = array(
            array(
                'table' => 'reference',
                'alias' => 'Reference',
                'type' => 'LEFT',
                'conditions' => array(
                    'Reference.id = Material.id_reference'
                )
            ),
            array(
                'table' => 'list_of_species',
                'alias' => 'ListOfSpecies',
                'type' => 'LEFT',
                'conditions' => array(
                    'ListOfSpecies.id = Reference.id_standardised_name'
                )
            )
        );
        $joins = array_merge($arrays, $this->_prepareBasicJoins());
        return $joins;
    }

    private function _prepareBasicJoins() {
        $joins = array(
            array(
                'table' => 'list_of_species',
                'alias' => 'Accepted',
                'type' => 'LEFT',
                'conditions' => array(
                    'ListOfSpecies.id_accepted_name = Accepted.id'
                )
            ),
            array(
                'table' => 'literature',
                'alias' => 'Literature',
                'type' => 'LEFT',
                'conditions' => array(
                    'Literature.id = Reference.id_literature'
                )
            ), array(
                'table' => 'world_l4',
                'alias' => 'WorldL4',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL4.id = Material.id_world_4'
                )
            ), array(
                'table' => 'world_l3',
                'alias' => 'WorldL3',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL4.id_parent = WorldL3.id'
                )
            ), array(
                'table' => 'world_l2',
                'alias' => 'WorldL2',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL3.id_parent = WorldL2.id'
                )
            ), array(
                'table' => 'world_l1',
                'alias' => 'WorldL1',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL2.id_parent = WorldL1.id'
                )
            )
        );
        return $joins;
    }

    protected function _prepareJoinsChrom() {
        $joins = array(
            array(
                'table' => 'reference',
                'alias' => 'Reference',
                'type' => 'LEFT',
                'conditions' => array(
                    'Material.id_reference = Reference.id'
                )
            ), array(
                'table' => 'literature',
                'alias' => 'Literature',
                'type' => 'LEFT',
                'conditions' => array(
                    'Reference.id_literature = Literature.id'
                )
            ), array(
                'table' => 'list_of_species',
                'alias' => 'ListOfSpeciesR',
                'type' => 'LEFT',
                'conditions' => array(
                    'Reference.id_standardised_name = ListOfSpeciesR.id'
                )
            ), array(
                'table' => 'history',
                'alias' => 'History',
                'type' => 'LEFT',
                'conditions' => array(
                    'Cdata.id = History.id_data'
                )
            ), array(
                'table' => 'list_of_species',
                'alias' => 'ListOfSpeciesH',
                'type' => 'LEFT',
                'conditions' => array(
                    'History.id_standardised_name = ListOfSpeciesH.id'
                )
            ), /* array(
              'table' => 'dna',
              'alias' => 'Dna',
              'type' => 'LEFT',
              'conditions' => array(
              'Cdata.id_dna = Dna.id'
              )
              ), */ array(
                'table' => 'world_l4',
                'alias' => 'WorldL4',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL4.id = Material.id_world_4'
                )
            ), array(
                'table' => 'world_l3',
                'alias' => 'WorldL3',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL4.id_parent = WorldL3.id'
                )
            )
        );
        return array_merge($joins, $this->_prepareWorldJoins());
    }

    private function _prepareWorldJoins() {
        $joins = array(
            array(
                'table' => 'world_l2',
                'alias' => 'WorldL2',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL2.id = WorldL3.id_parent'
                )
            ),
            array(
                'table' => 'world_l1',
                'alias' => 'WorldL1',
                'type' => 'LEFT',
                'conditions' => array(
                    'WorldL1.id = WorldL2.id_parent'
                )
            )
        );
        return $joins;
    }

    private function _prepareConditions($data, $type) {
        $conditions = array();
        if (isset($data['genus']) && !empty($data['genus'])) {
            $genus = '%' . strtolower($data['genus']) . '%';
            if ($type == 3) { //type == 2 || $type == 4
                $conditions[] = array('OR' => array('ListOfSpecies.genus ILIKE' => $genus, 'Accepted.genus ILIKE' => $genus,
                        'ListOfSpecies.genus_h ILIKE' => $genus, 'Accepted.genus_h ILIKE' => $genus));
            } else {
                $conditions[] = array('OR' => array('ListOfSpecies.genus ILIKE' => $genus, 'ListOfSpecies.genus_h ILIKE' => $genus));
            }
        }
        if (isset($data['species']) && !empty($data['species'])) {
            $species = '%' . strtolower($data['species']) . '%';
            if ($type == 3) { //type == 2 || $type == 4
                $conditions[] = array('OR' => array('ListOfSpecies.species ILIKE' => $species, 'Accepted.species ILIKE' => $species,
                        'ListOfSpecies.species_h ILIKE' => $species, 'Accepted.species_h ILIKE' => $species));
            } else {
                $conditions[] = array('OR' => array('ListOfSpecies.species ILIKE' => $species, 'ListOfSpecies.species_h ILIKE' => $species));
            }
        }
        if (isset($data['infra']) && !empty($data['infra'])) {
            $infra_like = '%' . strtolower($data['infra']) . '%';
            if ($type == 3) {
                $conditions[] = array('OR' => array('ListOfSpecies.subsp ILIKE' => $infra_like, 'ListOfSpecies.var ILIKE' => $infra_like,
                        'ListOfSpecies.subvar ILIKE' => $infra_like, 'ListOfSpecies.forma ILIKE' => $infra_like, 'ListOfSpecies.subsp_h ILIKE' => $infra_like,
                        'ListOfSpecies.var_h ILIKE' => $infra_like, 'ListOfSpecies.subvar_h ILIKE' => $infra_like, 'ListOfSpecies.forma_h ILIKE' => $infra_like,
                        'Accepted.subsp ILIKE' => $infra_like, 'Accepted.var ILIKE' => $infra_like,
                        'Accepted.subvar ILIKE' => $infra_like, 'Accepted.forma ILIKE' => $infra_like, 'Accepted.subsp_h ILIKE' => $infra_like,
                        'Accepted.var_h ILIKE' => $infra_like, 'Accepted.subvar_h ILIKE' => $infra_like, 'Accepted.forma_h ILIKE' => $infra_like));
            } else {
                $conditions[] = array('OR' => array('ListOfSpecies.subsp ILIKE' => $infra_like, 'ListOfSpecies.var ILIKE' => $infra_like,
                        'ListOfSpecies.subvar ILIKE' => $infra_like, 'ListOfSpecies.forma ILIKE' => $infra_like, 'ListOfSpecies.subsp_h ILIKE' => $infra_like,
                        'ListOfSpecies.var_h ILIKE' => $infra_like, 'ListOfSpecies.subvar_h ILIKE' => $infra_like, 'ListOfSpecies.forma_h ILIKE' => $infra_like));
            }
        }
        if (isset($data['literatureId']) && !empty($data['literatureId'])) { //for purpose of searching by literature
            $conditions['Literature.id'] = $data['literatureId'];
        }
        $this->_prepareConditionsCommon($data, $conditions);
        return $conditions;
    }

    private function _prepareConditionsCommon($data, &$conditions) {
        if (isset($data['authorPu']) && !empty($data['authorPu'])) {
            $conditions['Literature.paper_author ILIKE'] = '%' . strtolower($data['authorPu']) . '%';
        }
        if (isset($data['authorAn']) && !empty($data['authorAn'])) {
            $conditions['Cdata.counted_by'] = $data['authorAn'];
        }
        if (isset($data['world1']) && !empty($data['world1'])) {
            $conditions['WorldL1.id'] = $data['world1'];
        }
        if (isset($data['world2']) && !empty($data['world2'])) {
            $conditions['WorldL2.id'] = $data['world2'];
        }
        if (isset($data['world3']) && !empty($data['world3'])) {
            $conditions['WorldL3.id'] = $data['world3'];
        }
        if (isset($data['world4']) && !empty($data['world4'])) {
            $conditions['WorldL4.id'] = $data['world4'];
        }
        if (isset($data['chromX']) && !empty($data['chromX'])) {
            $conditions['Cdata.x_revised'] = $data['chromX'];
        }
        if (isset($data['chromN']) && !empty($data['chromN'])) {
            $conditions['Cdata.n'] = $data['chromN'];
        }
        if (isset($data['chromDn']) && !empty($data['chromDn'])) {
            $conditions[] = array('OR' =>
                array('Cdata.dn' => $data['chromDn'], 'Dna.ch_count' => $data['chromDn']));
        }
        if (isset($data['chromPloidy']) && !empty($data['chromPloidy'])) {
            $conditions[] = array('OR' =>
                array('Cdata.ploidy_level_revised' => $data['chromPloidy'], 'Dna.ploidy_revised' => $data['chromPloidy']));
        }
        $this->_coordinatesConditions($data, $conditions);
    }

    private function _coordinatesConditions($data, &$conditions) {
        if ((!empty($data['latDegrees']) || !empty($data['latMinutes']) || !empty($data['latSeconds'])) &&
                (!empty($data['lonDegrees']) || !empty($data['lonMinutes']) || !empty($data['lonSeconds'])) &&
                isset($data['range']) && !empty($data['range'])) {
            $lat = Utility::latLon($data['latitude']) * Utility::coordinates($data['latDegrees'], $data['latMinutes'], $data['latSeconds']);
            $lon = Utility::latLon($data['longitude']) * Utility::coordinates($data['lonDegrees'], $data['lonMinutes'], $data['lonSeconds']);
            $nw_lat = $lat + doubleval($data['range']);
            $nw_lon = $lon - doubleval($data['range']);
            $se_lat = $lat - doubleval($data['range']);
            $se_lon = $lon + doubleval($data['range']);
            $conditions['Material.coordinates_lat_dec <='] = $nw_lat;
            $conditions['Material.coordinates_lon_dec >='] = $nw_lon;
            $conditions['Material.coordinates_lat_dec >='] = $se_lat;
            $conditions['Material.coordinates_lon_dec <='] = $se_lon;
            $this->set('coords', "$nw_lat, $nw_lon; $se_lat, $se_lon");
        }
    }

    private function _createLosOutput($data, $type) {
//$dat = array();
//if ($type == 1 || $type == 2) {
        $dat = array(
            'standardised_name' => $data['ListOfSpecies'],
            'accepted_name' => array()
        );
        if (!empty($data['Accepted']['id'])) {
            $dat['accepted_name'] = $data['Accepted'];
        }
        if (!empty($dat['History']) && $type == 1) { //chceme najaktualnejsiu reviziu
            $los = $this->ListOfSpecies->findById($dat['History'][0]['id_standardised_name']);
            $dat['standardised_name'] = $los['ListOfSpecies'];
            $dat['accepted_name'] = $los['Accepted'];
        }
        /* } else {
          //$dat['id_link'] = $data['Reference']['name_as_published'];
          $dat['standardised_name']['name'] = $data['Reference']['name_as_published'];
          $dat['accepted_name'] = array();
          } */
        return $dat;
    }

    private function _searchOutput($cdata, $type) {
        $results = array();
        if ($cdata) {
            foreach ($cdata as &$dat) {
                $dat['ListOfSpecies'] = $this->_createLosOutput($dat, $type);
            }
            $loss = array_unique(Set::classicExtract($cdata, '{n}.ListOfSpecies'), SORT_REGULAR);

            foreach ($loss as $value) {
                $results[] = array('ListOfSpecies' => $value);
            }
        }
        return $results;
    }

}
