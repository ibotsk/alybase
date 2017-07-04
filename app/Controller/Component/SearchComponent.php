<?php

App::uses('Component', 'Controller');

class SearchComponent extends Component {

    public $uses = array('Cdata', 'Dcomment', 'History', 'ListOfSpecies', 'Literature', 'Material', 'Person', 'Reference', 'WorldL4', 'WorldL3', 'WorldL2', 'WorldL1');
    
    public function lastRevision($conditions) {
        $options = $this->lastRevisionOptions($conditions);
        $cdata = $this->History->find('all', $options);
        foreach ($cdata as &$value) {
            $value['ListOfSpecies']['type'] = 'revision';
        }
        return $cdata;
    }

    public function allNames($conditions) {
        $optionsOrig = $this->origNamesOptions($conditions);
        $orig = $this->Cdata->find('all', $optionsOrig);
        foreach ($orig as &$value) {
            $value['ListOfSpecies']['type'] = 'original';
        }
        $cdata = array_merge($orig, $this->lastRevision($conditions));
        return $cdata;
    }

    public function chromosomes($type, $subj) {
        //$this->autoRender = false;
        if ($type == 'publ') {
            $conditions = array('Reference.name_as_published' => $subj);
        } elseif ($type == 'rev') {
            $conditions = array('OR' => array(
                    'History.id_standardised_name' => $subj
                )
            );
            //$lso = $this->ListOfSpecies->findById($subj);
        } else {
            $conditions = array('OR' => array(
                    'Reference.id_standardised_name' => $subj//,
                //'ListOfSpeciesR.id_accepted_name' => $subj
                )
            );
        }
        $options = array(
            'fields' => array('Cdata.*', 'CountedBy.pers_name',
                'Literature.paper_author', 'Literature.paper_title',
                'Literature.iopb_source', 'Literature.volume',
                'Literature.issue', 'Literature.publisher',
                'Literature.editor', 'Literature.year',
                'Literature.pages', 'Literature.journal_name',
                'Literature.display_type'),
            'joins' => $this->prepareJoinsChrom(),
            'conditions' => $conditions
        );
        $cdata = array_unique($this->Cdata->find('all', $options), SORT_REGULAR);
        return $cdata;
    }

    public function lastRevisionOptions($conditions) {
        $options = array(
            'fields' => array(
                'ListOfSpecies.id', 'ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors',
                'ListOfSpecies.hybrid', 'ListOfSpecies.genus_h', 'ListOfSpecies.species_h', 'ListOfSpecies.subsp_h', 'ListOfSpecies.var_h', 'ListOfSpecies.subvar_h', 'ListOfSpecies.forma_h', 'ListOfSpecies.authors_h',
                'Accepted.id', 'Accepted.genus', 'Accepted.species', 'Accepted.subsp', 'Accepted.var', 'Accepted.subvar', 'Accepted.forma', 'Accepted.authors',
                'Accepted.hybrid', 'Accepted.genus_h', 'Accepted.species_h', 'Accepted.subsp_h', 'Accepted.var_h', 'Accepted.subvar_h', 'Accepted.forma_h', 'Accepted.authors_h',),
            'joins' => $this->prepareJoinsHistory(),
            'order' => array('ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors'),
            'conditions' => $conditions
        );
        return $options;
    }

    public function origNamesOptions($conditions) {
        $options = array(
            'fields' => array(
                'ListOfSpecies.id', 'ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors',
                'ListOfSpecies.hybrid', 'ListOfSpecies.genus_h', 'ListOfSpecies.species_h', 'ListOfSpecies.subsp_h', 'ListOfSpecies.var_h', 'ListOfSpecies.subvar_h', 'ListOfSpecies.forma_h', 'ListOfSpecies.authors_h',
                'Accepted.id', 'Accepted.genus', 'Accepted.species', 'Accepted.subsp', 'Accepted.var', 'Accepted.subvar', 'Accepted.forma', 'Accepted.authors',
                'Accepted.hybrid', 'Accepted.genus_h', 'Accepted.species_h', 'Accepted.subsp_h', 'Accepted.var_h', 'Accepted.subvar_h', 'Accepted.forma_h', 'Accepted.authors_h'
            ),
            'joins' => $this->prepareJoinsAllNames(),
            'order' => array('ListOfSpecies.genus', 'ListOfSpecies.species', 'ListOfSpecies.subsp', 'ListOfSpecies.var', 'ListOfSpecies.subvar', 'ListOfSpecies.forma', 'ListOfSpecies.authors'),
            'conditions' => $conditions,
            'limit' => 5
        );
        return $options;
    }

    public function allPublNames($conditions) {
        $options = array(
            'fields' => array('DISTINCT Reference.name_as_published', 'Reference.id', 'ListOfSpecies.id_accepted_name'),
            'joins' => $this->prepareJoinsAllNames(),
            'order' => 'Reference.name_as_published',
            'conditions' => $conditions
        );
        return $options;
    }

    public function allPublNamesNoSyn($conditions) {
        $options = array(
            'fields' => array('DISTINCT Reference.name_as_published', 'Reference.id', 'ListOfSpecies.id_accepted_name'),
            'joins' => $this->prepareJoinsAllNames(),
            'conditions' => array('ListOfSpecies.id_accepted_name' => NULL),
            'order' => 'Reference.name_as_published'
        );
        return $options;
    }

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
    public function prepareJoinsHistory() {
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
            )
        );
        $joins = array_merge($arrays, $this->prepareBasicJoins());
        return $joins;
    }

    public function prepareJoinsAllNames() {
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
        $joins = array_merge($arrays, $this->prepareBasicJoins());
        return $joins;
    }

    public function prepareBasicJoins() {
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

    protected function prepareJoinsChrom() {
        $joins = array(
            array(
                'table' => 'reference',
                'alias' => 'Reference',
                'type' => 'LEFT',
                'conditions' => array(
                    'Material.id_reference = Reference.id'
                )
            ),
            array(
                'table' => 'literature',
                'alias' => 'Literature',
                'type' => 'LEFT',
                'conditions' => array(
                    'Reference.id_literature = Literature.id'
                )
            ),
            array(
                'table' => 'list_of_species',
                'alias' => 'ListOfSpeciesR',
                'type' => 'LEFT',
                'conditions' => array(
                    'Reference.id_standardised_name = ListOfSpeciesR.id'
                )
            ),
            array(
                'table' => 'history',
                'alias' => 'History',
                'type' => 'LEFT',
                'conditions' => array(
                    'Cdata.id = History.id_data'
                )
            ),
            array(
                'table' => 'list_of_species',
                'alias' => 'ListOfSpeciesH',
                'type' => 'LEFT',
                'conditions' => array(
                    'History.id_standardised_name = ListOfSpeciesH.id'
                )
            )
        );
        return $joins;
    }

    public function prepareWorldJoins() {
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

    public function prepareConditions($data, $type) {
        $conditions = array();
        /* if ($data['family']) {
          $conditions['ListOfSpecies.id_family'] = $data['family'];
          } */
        /* switch ($type) {
          case 1:
          if ($data['genus']) {
          $conditions['ListOfSpecies.genus LIKE'] = '%' . $data['genus'] . '%';
          }
          if ($data['species']) {
          $conditions['ListOfSpecies.species LIKE'] = '%' . $data['species'] . '%';
          }
          break;
          case 2:
          if ($data['genus']) {
          $genus = '%' . $data['genus'] . '%';
          $conditions[] = array('OR' => array('ListOfSpecies.genus LIKE' => $genus,
          'Accepted.genus LIKE' => $genus,
          'ListOfSpeciesH.genus LIKE' => $genus));
          }
          if ($data['species']) {
          //$conditions['ListOfSpecies.species LIKE'] = '%' . $data['species'] . '%';
          $species = '%' . $data['species'] . '%';
          $conditions[] = array('OR' => array('ListOfSpecies.species LIKE' => $species,
          'Accepted.species LIKE' => $species,
          'ListOfSpeciesH.species LIKE' => $species));
          }
          } */
        if ($type == 1 || $type == 2) {
            if ($data['genus']) {
                $conditions['ListOfSpecies.genus LIKE'] = '%' . $data['genus'] . '%';
            }
            if ($data['species']) {
                $conditions['ListOfSpecies.species LIKE'] = '%' . $data['species'] . '%';
            }
        } else {
            if ($data['genus']) {
                $conditions['Reference.name_as_published LIKE'] = '%' . $data['genus'] . '%';
            }
            if ($data['species']) {
                $conditions['Reference.name_as_published LIKE'] = '%' . $data['species'] . '%';
            }
        }
        if ($data['authorPu']) {
            $conditions['Literature.paper_author LIKE'] = '%' . $data['authorPu'] . '%';
        }
        if ($data['authorAn']) {
            $conditions['Cdata.counted_by'] = $data['authorAn'];
        }
        if ($data['world1']) {
            $conditions['WorldL1.id'] = $data['world1'];
        }
        if ($data['world2']) {
            $conditions['WorldL2.id'] = $data['world2'];
        }
        if ($data['world3']) {
            $conditions['WorldL3.id'] = $data['world3'];
        }
        if ($data['world4']) {
            $conditions['WorldL4.id'] = $data['world4'];
        }
        return $conditions;
    }

    protected function createLosOutput($data, $type) {
        $dat = array();
        if ($type == 1 || $type == 2) {
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
        } else {
            //$dat['id_link'] = $data['Reference']['name_as_published'];
            $dat['standardised_name']['name'] = $data['Reference']['name_as_published'];
            $dat['accepted_name'] = array();
        }
        return $dat;
    }

    public function searchOutput($cdata, $type) {
        $results = array();
        if ($cdata) {
            foreach ($cdata as &$dat) {
                $dat['ListOfSpecies'] = $this->createLosOutput($dat, $type);
            }
            $loss = array_unique(Set::classicExtract($cdata, '{n}.ListOfSpecies'), SORT_REGULAR);

            foreach ($loss as $value) {
                $results[] = array('ListOfSpecies' => $value);
            }
        }
        return $results;
    }

}

