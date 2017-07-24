<?php

// require_once 'D:\Apache Software Foundation\xampp\htdocs\AlyssumPortalCake\app\Lib\dBug.php';
App::uses('AppController', 'Controller');
class ChecklistsController extends AppController {

	public $helpers = array(
			'Html',
			'Form',
			'Format' 
	);

	public $uses = array(
			'ListOfSpecies',
			'LosComment' 
	);

	public $components = array(
			'RequestHandler' 
	);

	public function beforeFilter() {
		parent::beforeFilter();
		$this->set('page', 'nomen');
	}

	public function index() {
	}

	public function search() {
		if ($this->request->is('post')) {
			$data = $this->request->data;
			$genus_like = '%' . strtolower($data['Filter']['genus']) . '%';
			$species_like = '%' . strtolower($data['Filter']['species']) . '%';
			$options = array(
					'conditions' => array(
							'ListOfSpecies.hybrid' => false,
							'OR' => array(
									'ListOfSpecies.syn_type !=' => 0, //TODO
									'ListOfSpecies.syn_type' => null 
							),
							'ListOfSpecies.genus ILIKE' => $genus_like,
							'ListOfSpecies.species LIKE' => $species_like 
					),
					'order' => array(
							'ListOfSpecies.ntype_order',
							'ListOfSpecies.genus',
							'ListOfSpecies.species',
							'ListOfSpecies.subsp ASC NULLS FIRST',
							'ListOfSpecies.var ASC NULLS FIRST',
							'ListOfSpecies.subvar ASC NULLS FIRST',
							'ListOfSpecies.forma ASC NULLS FIRST',
							'ListOfSpecies.authors',
							'ListOfSpecies.id' 
					) 
			);
			if ($data['Filter']['authors']) {
				$options['conditions']['ListOfSpecies.authors LIKE'] = '%' . $data['Filter']['authors'] . '%';
			}
			if ($data['Filter']['infra']) {
				$infra_like = '%' . strtolower($data['Filter']['infra']) . '%';
				$options['conditions'][] = array(
						'OR' => array(
								'ListOfSpecies.subsp ILIKE' => $infra_like,
								'ListOfSpecies.var ILIKE' => $infra_like,
								'ListOfSpecies.subvar ILIKE' => $infra_like,
								'ListOfSpecies.forma ILIKE' => $infra_like 
						) 
				);
			}
			/*
			 * if ($data['Filter']['synonyms'] == 2) {
			 * $options['conditions']['ListOfSpecies.ntype !='] = array('S', 'DS');
			 * }
			 */
			if (is_array($data['Filter']['types']) && !empty($data['Filter']['types'])) {
				$types = array(
						'OR' => array() 
				);
				foreach ($data['Filter']['types'] as $value) {
					switch ($value) {
						case 2 :
							$types['OR'][] = array(
									'ListOfSpecies.ntype' => 'A' 
							);
							break;
						case 3 :
							$types['OR'][] = array(
									'ListOfSpecies.ntype' => 'PA' 
							);
							break;
						case 4 :
							$types['OR'][] = array(
									'ListOfSpecies.ntype' => 'S' 
							);
							break;
						case 5 :
							$types['OR'][] = array(
									'ListOfSpecies.ntype' => 'DS' 
							);
							break;
						case 6 :
							$types['OR'][] = array(
									'ListOfSpecies.ntype' => 'U' 
							);
							break;
						case 7 :
							$types['OR'][] = array(
									'ListOfSpecies.syn_type' => 1 
							);
							break;
						default :
							break;
					}
				}
				$options['conditions'][] = $types;
			}
			// print_r($options['conditions']);
			// exit();
			$results = $this->ListOfSpecies->find('all', $options);
			$this->set(compact('results'));
		}
	}

	public function detail($id) {
		$this->ListOfSpecies->unbindModel(array(
				'hasMany' => array(
						'History',
						'LatestRevision',
						'Reference' 
				) 
		));
		$this->ListOfSpecies->recursive = -1;
		$result = $this->ListOfSpecies->find('first', array(
				'conditions' => array(
						'ListOfSpecies.id' => $id 
				),
				'contain' => array(
						'Accepted',
						'Basionym',
						'BasionymFor',
						'Replaced',
						'ReplacedFor',
						'SynonymsInvalid',
						'SynonymsTaxonomic' => array(
								'SynonymsNomenclatoric' => array(
										'conditions' => array(
												'show_in_tree' => true 
										),
										'order' => 'rorder'
								),
						),
						'SynonymsNomenclatoric' 
				) 
		
		));
		if (empty($result)) {
			// throw new InvalidArgumentException('Name with given id does not exist');
		}
		/*
		 * if ($result['ListOfSpecies']['ntype'] == 'A' || $result['ListOfSpecies']['ntype'] == 'PA') {
		 * $nom_syn = $this->_nominalSynonyms($result);
		 *
		 * $result['Synonyms'] = array_merge($result['Synonyms'], $nom_syn);
		 * foreach ($result['Synonyms'] as &$s) {
		 * $s['BasionymFor'] = $this->_basionymFor($s);
		 * }
		 * }
		 * $basId = $result['Basionym']['id'] ? $result['Basionym']['id'] : $result['Replaced']['id'];
		 * $result['SynonymsTriple'] = $this->_tripleSynonyms($basId, $id, $result['Accepted']['id'], $result['ListOfSpecies']['id_superior_name']);
		 *
		 */
		$tree = $this->LosComment->generateTreeList(array(
				'LosComment.id_list_of_species' => $id 
		), null, null, '_');
		$comments = array();
		foreach ($tree as $idc => $val) {
			$comment = $this->LosComment->find('first', array(
					'fields' => array(
							'LosComment.id',
							'LosComment.username',
							'LosComment.institution',
							'LosComment.annotation',
							'LosComment.date_posted' 
					),
					'conditions' => array(
							'LosComment.id' => $idc,
							'LosComment.approved' => true 
					) 
			));
			if ($comment) {
				$comment['LosComment']['nested'] = substr_count($val, '_');
				$comments[] = $comment;
			}
		}
		$this->set(compact('result', 'comments'));
	}

	public function view_pdf() {
		$request = $this->request->data;
		$data = $this->_export($request);
		// increase memory limit in PHP
		// ini_set('memory_limit', '748M');
		
		App::import('Vendor', 'fpdf', array(
				'file' => 'fpdf/html2pdf.php' 
		));
		$pdf = new PDF_HTML();
		$this->set(compact('data', 'pdf'));
	}

	public function view_rtf() {
		$request = $this->request->data;
		$data = $this->_export($request);
		
		$file = new File(APP . DS . 'webroot' . DS . 'css' . DS . 'print.css', false); // 1
		$this->set('inlineCss', $file->read()); // 2
		$file->close();
		// Configure::write('debug', 0);
		$this->set(compact('data'));
	}

	private function _export($request) {
		$ids = $request['ListOfSpecies']['exportIds'];
		$explIds = explode('|', $ids);		
		/*
		$data = $this->ListOfSpecies->find('all', array(
				'conditions' => array(
						'ListOfSpecies.id' => $explIds 
				),
				'order' => array(
						'ListOfSpecies.ntype_order',
						'ListOfSpecies.genus',
						'ListOfSpecies.species',
						'ListOfSpecies.subsp ASC NULLS FIRST',
						'ListOfSpecies.var ASC NULLS FIRST',
						'ListOfSpecies.subvar ASC NULLS FIRST',
						'ListOfSpecies.forma ASC NULLS FIRST',
						'ListOfSpecies.authors',
						'ListOfSpecies.id' 
				) 
		));
		set_time_limit(0);
		foreach ($data as &$d) {
			if ($d['ListOfSpecies']['ntype'] == 'A' || $d['ListOfSpecies']['ntype'] == 'PA') {
				$nom_syn = $this->_nominalSynonyms($d);
				$d['Synonyms'] = array_merge($d['Synonyms'], $nom_syn);
				foreach ($d['Synonyms'] as &$s) {
					$s['BasionymFor'] = $this->_basionymFor($s);
				}
			}
			$basId = $d['Basionym']['id'];
			$d['SynonymsTriple'] = $this->_tripleSynonyms($basId, $d['ListOfSpecies']['id'], $d['Accepted']['id'], $d['ListOfSpecies']['id_superior_name']);
		}
		*/
		$this->ListOfSpecies->unbindModel(array(
				'hasMany' => array(
						'History',
						'LatestRevision',
						'Reference'
				)
		));
		$this->ListOfSpecies->recursive = -1;
		set_time_limit(0);
		$data = $this->ListOfSpecies->find('all', array(
				'conditions' => array(
						'ListOfSpecies.id' => $explIds
				),
				'contain' => array(
						'Accepted',
						'Basionym',
						'BasionymFor',
						'Replaced',
						'ReplacedFor',
						'SynonymsInvalid',
						'SynonymsTaxonomic' => array(
								'SynonymsNomenclatoric' => array(
										'conditions' => array(
												'show_in_tree' => true
										),
										'order' => 'rorder'
								)
						),
						'SynonymsNomenclatoric'
				),
				'order' => array(
						'ListOfSpecies.ntype_order',
						'ListOfSpecies.genus',
						'ListOfSpecies.species',
						'ListOfSpecies.subsp ASC NULLS FIRST',
						'ListOfSpecies.var ASC NULLS FIRST',
						'ListOfSpecies.subvar ASC NULLS FIRST',
						'ListOfSpecies.forma ASC NULLS FIRST',
						'ListOfSpecies.authors',
						'ListOfSpecies.id'
				)
				
		));
		return $data;
	}

	/**
	 * This function searches for all implicit synonyms of the name.
	 *
	 * @param type $los        	
	 * @return type
	 */
	private function _nominalSynonyms($los) {
		if (!$los['ListOfSpecies']['subsp'] && !$los['ListOfSpecies']['var'] && !$los['ListOfSpecies']['subvar'] && !$los['ListOfSpecies']['forma']) { // only genus, species, authors
			$genus = trim($los['ListOfSpecies']['genus']);
			$species = trim($los['ListOfSpecies']['species']);
			$conditions = array(
					'ListOfSpecies.genus' => $genus,
					'ListOfSpecies.species' => $species,
					'ListOfSpecies.id_superior_name' => $los['ListOfSpecies']['id'] 
			);
			$nominal = $this->ListOfSpecies->find('first', array(
					'conditions' => $conditions 
			));
			if ($nominal) {
				return array_merge($nominal['Synonyms']);
			}
			return array();
		}
		return array();
	}

	private function _basionymFor($s) {
		$basfor = $this->ListOfSpecies->find('all', array(
				'fields' => 'ListOfSpecies.*',
				'conditions' => array(
						'ListOfSpecies.id_basionym' => $s['id'],
						'ListOfSpecies.syn_type' => 3 
					/*
				 * 'OR' => array('ListOfSpecies.syn_type' => 3,
				 * $this->_subordinate($s['species'])
				 * )
				 */
				) 
		));
		return Set::classicExtract($basfor, '{n}.ListOfSpecies');
	}

	private function _tripleSynonyms($basId, $nameId, $accNameId = null, $superiorId = null) {
		/*
		 * $conditions = array('OR' => array(
		 * //'ListOfSpecies.id' => $basId,
		 * 'ListOfSpecies.id_basionym' => $nameId, //podmienka b)
		 * array('ListOfSpecies.id_basionym' => $basId, 'ListOfSpecies.id !=' => $nameId), //podmienka a)
		 * array('ListOfSpecies.id_basionym' => null, 'ListOfSpecies.syn_type' => 3, 'ListOfSpecies.id_accepted_name' => $nameId)
		 * ), 'ListOfSpecies.ntype !=' => 'A', 'ListOfSpecies.ntype !=' => 'PA');
		 * if (!$basId) {
		 * $conditions = array('OR' => array(
		 * 'ListOfSpecies.id_basionym' => $nameId,
		 * array('ListOfSpecies.id_basionym' => null, 'ListOfSpecies.syn_type' => 3, 'ListOfSpecies.id_accepted_name' => $nameId)
		 * ), 'ListOfSpecies.ntype !=' => 'A', 'ListOfSpecies.ntype !=' => 'PA');
		 * }
		 */
		// find all records matching conditions
		if (!$accNameId) {
			$accNameId = $nameId;
		}
		$conditions = array(
				'OR' => array(
						// 'ListOfSpecies.id' => $basId,
						'ListOfSpecies.id_basionym' => $nameId, // mena, ktore maju toto meno ako basionym
						array(
								'ListOfSpecies.id_basionym' => $basId,
								'ListOfSpecies.id !=' => $nameId,
								'ListOfSpecies.syn_type !=' => 0 
						), // mena, ktore maju rovnake basionymum s vynimkou seba sameho
						array(
								'ListOfSpecies.id_basionym' => null,
								'ListOfSpecies.syn_type' => 3,
								'ListOfSpecies.id_accepted_name' => $nameId 
						), // mena, ktore maju toto meno ako akceptovane
						array(
								'ListOfSpecies.id_basionym' => null,
								'ListOfSpecies.syn_type' => 3,
								'ListOfSpecies.id_accepted_name' => $accNameId 
						), // mena, ktore maju spolocne akceptovane meno a su k nemu priradene trojito
						'ListOfSpecies.id_superior_name' => $nameId  // mena, ktore su podradene tomuto menu
							                                            // array('ListOfSpecies.id_accepted_name' => $nameId, 'ListOfSpecies.id_superior_name' => $basId) //
				),
				'ListOfSpecies.id !=' => $accNameId,
				'ListOfSpecies.id !=' => $nameId 
		);
		if (!$basId) {
			$conditions = array(
					'OR' => array(
							'ListOfSpecies.id_basionym' => $nameId,
							array(
									'ListOfSpecies.id_basionym' => null,
									'ListOfSpecies.syn_type' => 3,
									'ListOfSpecies.id_accepted_name' => $nameId 
							),
							array(
									'ListOfSpecies.id_basionym' => null,
									'ListOfSpecies.syn_type' => 3,
									'ListOfSpecies.id_accepted_name' => $accNameId 
							), // mena, ktore maju spolocne akceptovane meno a su k nemu priradene trojito
							'ListOfSpecies.id_superior_name' => $nameId 
					),
					'ListOfSpecies.id !=' => $accNameId,
					'ListOfSpecies.id !=' => $nameId  /* , 'ListOfSpecies.ntype !=' => 'A', 'ListOfSpecies.ntype !=' => 'PA' */
			);
		}
		if ($superiorId) {
			$conditions['OR']['ListOfSpecies.id'] = $superiorId;
		}
		$this->ListOfSpecies->unbindModel(array(
				'hasMany' => array(
						'History',
						'LatestRevision',
						'Reference',
						'Synonyms',
						'SynonymsInvalid',
						'NomenNovumFor',
						'ReplacedFor',
						'LosComment' 
				) 
		));
		$tripleSyns = $this->ListOfSpecies->find('all', array(
				'fields' => 'ListOfSpecies.*',
				'conditions' => $conditions,
				'order' => array(
						'ListOfSpecies.genus',
						'ListOfSpecies.species',
						'ListOfSpecies.subsp',
						'ListOfSpecies.var',
						'ListOfSpecies.subvar',
						'ListOfSpecies.forma',
						'ListOfSpecies.authors' 
				) 
		));
		
		/* $nomin = array(); */
		/*
		 * $ts = array(); //synonyms where accepted name has some superior name -- now unwanted functionality
		 * foreach ($tripleSyns as &$syn) {
		 * ///* if (empty($syn['ListOfSpecies']['subsp']) && empty($syn['ListOfSpecies']['var']) &&
		 * empty($syn['ListOfSpecies']['subvar']) && empty($syn['ListOfSpecies']['forma'])) {
		 * $genus = $syn['ListOfSpecies']['genus'];
		 * $species = $syn['ListOfSpecies']['species'];
		 * $this->ListOfSpecies->unbindModel(array('hasMany' => array('History', 'LatestRevision', 'Reference', 'Synonyms', 'SynonymsInvalid', 'NomenNovumFor', 'ReplacedFor', 'LosComment'),
		 * 'belongsTo' => array('Accepted', 'Basionym', 'Replaced')));
		 * $nominals = $this->ListOfSpecies->find('all', array('conditions' => array(
		 * 'ListOfSpecies.genus' => $genus,
		 * 'ListOfSpecies.species' => $species,
		 * array('OR' => $this->_subordinate($species)
		 * ),
		 * 'ListOfSpecies.hybrid' => false, 'ListOfSpecies.id !=' => $nameId
		 * )));
		 * foreach ($nominals as &$n) {
		 * $n['ListOfSpecies']['BasionymFor'] = $n['BasionymFor'];
		 * $nomin[] = $n;
		 * }
		 * } else { ///*
		 * if ($syn['ListOfSpecies']['id_superior_name'] == $nameId) {
		 * $this->ListOfSpecies->unbindModel(array('hasMany' => array('History', 'LatestRevision', 'Reference', 'Synonyms', 'SynonymsInvalid', 'NomenNovumFor', 'ReplacedFor', 'BasionymFor', 'LosComment'),
		 * 'belongsTo' => array('Accepted', 'Basionym', 'Replaced')));
		 * //hladame len synonyma, cize tie maju vzdy nejaky syn_type
		 * $t = $this->ListOfSpecies->find('all', array('conditions' => array(
		 * 'ListOfSpecies.id_accepted_name' => $syn['ListOfSpecies']['id'],
		 * 'ListOfSpecies.id !=' => $syn['ListOfSpecies']['id_basionym'],
		 * 'ListOfSpecies.syn_type !=' => 0
		 * )));
		 * $ts = array_merge($ts, $t);
		 * }
		 * $syn['ListOfSpecies']['BasionymFor'] = $syn['BasionymFor'];
		 * //}
		 * }
		 * $tripleSyns = array_merge($tripleSyns, $ts);
		 *
		 */
		if (count($tripleSyns) > 0) {
			return Set::classicExtract($tripleSyns, '{n}.ListOfSpecies');
		}
		return array();
	}

	private function _subordinate($species) {
		$c = array(
				array(
						'ListOfSpecies.subsp' => $species,
						'ListOfSpecies.var' => null,
						'ListOfSpecies.subvar' => null,
						'ListOfSpecies.forma' => null 
				),
				array(
						'ListOfSpecies.subsp' => null,
						'ListOfSpecies.var' => $species,
						'ListOfSpecies.subvar' => null,
						'ListOfSpecies.forma' => null 
				),
				array(
						'ListOfSpecies.subsp' => null,
						'ListOfSpecies.var' => null,
						'ListOfSpecies.subvar' => $species,
						'ListOfSpecies.forma' => null 
				),
				array(
						'ListOfSpecies.subsp' => null,
						'ListOfSpecies.var' => null,
						'ListOfSpecies.subvar' => null,
						'ListOfSpecies.forma' => $species 
				) 
		);
		return $c;
	}
}
