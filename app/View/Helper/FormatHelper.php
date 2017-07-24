<?php

App::uses('AppHelper', 'Helper');

class FormatHelper extends AppHelper {

    public $helpers = array('Html');

    public function activeClass($var, $value, $class) {
        return $var == $value ? ('class="' . $class . '"') : '';
    }

    public function chromosomes($n, $dn, $dna_c = '', $sign = '=') {
        $out = '';
        if (!empty($dn)) {
            $out = '2n ' . $sign . ' ' . $dn;
        } else if (!empty($n)) {
            $out = '2n ' . $sign . ' ' . (2 * $n);
        } else if (!empty($dna_c)) {
            $out = '2n ' . $sign . ' ' . $dna_c;
        } else {
            $out = 'N/A';
        }
        return $out;
    }

    public function literature($lit, $options = array()) {
    	$options = array_merge(
    			array('link' => ''),
    			(array)$options
    			);
    	
        $out = $lit['paper_author'] . ' (' . $lit['year'] . ') ' . $lit['paper_title'] . '. ';
        
        if (!empty($options['link'])) {
            $out = $this->Html->link($out, $options['link']);
        }
        $voliss = $lit['volume'] . ($lit['issue'] ? '(' . $lit['issue'] . ')' : '');
        switch ($lit['display_type']) {
            case 1:
                $out .= $lit['journal_name'] . ', ' . $voliss . ':';
                break;
            case 2:
                $out .= $lit['publisher'] . '. ';
                break;
            case 3:
                $out .= 'In: (eds.) ' . $lit['editor'] . ', ' . $lit['series_source'] . '. ' . $lit['publisher'] . '. ';
                break;
            case 4:
                $out .= 'In: (eds.) ' . $lit['editor'] . ', ' . $lit['series_source'] . '. ' . $lit['publisher'] . '. ';
                break;
            case 5:
                $out .= 'In: (eds.) ' . $lit['editor'] . ', ' . $lit['series_source'] . '. ' . $lit['journal_name'] . ', ' . $voliss . ':';
                break;
            default:
                break;
        }
        $out .= $lit['pages'];
        return $out;
    }

    public function genomeSize($c, $from, $to, $units) {
        if (empty($from) && empty($to)) {
            return '';
        }
        $from = empty($from) ? '' : $from;
        $to = empty($to) ? '' : $to;
        return "$c = $from $to $units";
    }

    public function coordinates($coord) {
        if ($coord == 'null' || empty($coord)) {
            return '';
        }
        $out = array();
        $coords = explode("-", $coord);
        foreach ($coords as $coord) {
            $tokens = explode(":", $coord);
            $deg = $tokens[0] . "°";
            $min = (empty($tokens[1]) ? "00" : $tokens[1]) . "'";
            $sec = (empty($tokens[2]) ? "00" : $tokens[2]) . "''";
            $out[] = $deg . $min . $sec . $tokens[3];
        }
        return implode(" - ", $out);
    }

    /**
     * 
     * @param unknown $label
     * @param unknown $value
     * @param array $options
     * @return string
     * 
     * $link = false, $wrap = '', $listPrepend = '', $liclass = array(), $special = ''
     */
    public function detailValue($label, $value, $options = array()) {
    	$options = array_merge(
    			array('arrayField' => '', 'link' => false, 'wrap' => '', 'listPrepend' => '', 'liclass' => array(),  'special' => ''),
    			(array)$options
    			);
    	
    	$wrap = $options['wrap'];
        $out = empty($wrap) ? '' : ('<' . $wrap . '>');
        if (isset($label) && !empty($label) && $label != 'null') {
            $out .= '<span class="dlabel">' . $label . '</span>';
        }

        if (isset($value) && !empty($value) && $value != 'null') {
            $out .= '<span class="value">';
            if (is_array($value)) {
                $out .= $this->_array($value, $options['arrayField'], $options);
            } else {
                $out .= $value;
            }
            $out .= '</span>';
        }
        $out .= empty($wrap) ? '' : ('</' . $wrap . '>');
        return $out;
    }

    /**
     * Creates a HTML markup based on the $status
     * @param string $status Status of the name - A = accepted, PA = provisionally accepted,
     * U - unresolved, S - synonym, DS - doubtful synonym, B - basionym, R - replaced name, H - hybrid
     * @param array $options Additional options: parent - defines a parent of the name in case of basionym or replaced name (e.g. X is a basionym of parent)
     * italic - whether the parent name should be in italic
     * is_invalid - mark synonym or doubtful synonym as Designation not validly published
     * @return string HTML markup if status is valid, empty string otherwise
     * 
     * $accepted = array(), $italic = false, $syntype = ''
     */
    public function status($status, $options = array()) {
    	$options = array_merge(
    			array('parent' => array(), 'italic' => false, 'is_invalid' => false),
    			(array)$options
    			);
    	
    	$invalid = $options['is_invalid'];
    	$parent = $options['parent'];
    	
        $syn_out = "";
        switch ($status) {
            case 'A':
                return '<span class="accepted">Accepted</span>';
            case 'PA':
                return '<span class="paccepted">Provisionally accepted</span>';
            case 'U':
            	$r = $invalid ? '<span class="invalid">Designation not validly published</span><br />' : '';
                return $r . '<span class="unresolved">Unresolved</span>';
            case 'S':
            	$syn_out = $invalid ? '<span class="invalid">Designation not validly published</span>' : '<span class="synonym">Synonym</span>';
                break;
            case 'DS':
            	$syn_out = $invalid ? '<span class="invalid">Designation not validly published</span>' : '<span class="dsynonym">Doubtful synonym</span>';
                break;
            case 'B':
                $syn_out = '<span class="basionym">Basionym</span>';
                break;
            case 'R':
                $syn_out = '<span class="replaced">Replaced name</span>';
                break;
            case 'H':
                $syn_out = '<span class="hybrid">Hybrid</span>';
            default:
                break;
        }
        if (!empty($parent) && $parent['id']) {
        	$name = $this->los($parent, array('italic' => $options['italic']));
        	$syn_out .= ' of ' . $this->Html->link($name, array('controller' => 'checklists', 'action' => 'detail', $parent['id']), array('escape' => false));
        }
        return $syn_out;
    }

    
    /**
     * 
     * @param unknown $name
     * @param array $options
     * @return string|unknown|string
     * 
     * italic, publication, special, tribus
     */
    public function los($name, $options = array()) {
    	$options = array_merge(
    			array('special' => '', 'publication' => true, 'tribus' => true, 'italic' => false, 'debug' => false),
    			(array)$options
    			);
    	
    	$special = $options['special'];
    	$publication = $options['publication'];
    	$tribus = $options['tribus'];
    	
        if (isset($name['name'])) {
            return $publication ? $name['name'] . ", " . $name['publication'] : $name['name'];
        }
        $syntype = $name['syn_type'];
        if (!empty($special) && isset($name[$special]) && $name[$special]) {
            $syntype = 1;
        }
        $out = $this->_los($name['genus'], $name['species'], $name['subsp'], $name['var'], $name['subvar'], $name['forma'], $name['authors'], 
        		array('publication' => ($publication ? $name['publication'] : ''), 'ishybrid' => $name['hybrid'], 'syntype' => $syntype, 
        				'genus_h' => $name['genus_h'], 'species_h' => $name['species_h'], 'subsp_h' => $name['subsp_h'], 'var_h' => $name['var_h'], 
        				'subvar_h' => $name['subvar_h'], 'forma_h' => $name['forma_h'], 'authors_h' => $name['authors_h'], 'tribus' => ($tribus && $name['tribus'] ? $name['tribus'] : ''),
        				'italic' => $options['italic']
        		));
        //prepend id if debug is true
        $prep = $options['debug'] && isset($name['id']) && !empty($name['id']) ? ($name['id'] . ' - ') : '';
        return $prep . $out;
    }

    /**
     * 
     * @param unknown $list
     * @param array $options
     * @return string[][]|unknown[][]|string[][][][]|unknown[][][][]|NULL[][][][]
     * 
     * $italic = false, $linkField = 'id', $arrayField = 'BasionymFor', $publication = true, $special = '', $tribus = true
     */
    public function losList($list, $options = array()) {
    	$options = array_merge(
    			array('linkField' => 'id', 'arrayField' => '', 'special' => '', 'publication' => true, 'tribus' => true, 'italic' => false, 'debug' => false),
    			(array)$options
    			);
    	$arrayField = $options['arrayField'];
    	$out = array();
    	foreach ($list as $item) {
    		$newitem = array();
    		$newitem['id'] = $item[$options['linkField']];
    		$newitem['name'] = $this->los($item, $options);
    		if (!empty($item[$arrayField])) {
    			$newitem[$arrayField] = $this->losList($item[$arrayField], $options);
    		}
    		$out[] = $newitem;
    	}
    	return $out;
    }


    private function _los($genus, $species, $subsp, $var, $subvar, $forma, $authors, $options = array()) {
    	$options = array_merge(
    			array('publication' => '', 'ishybrid' => false, 'syntype' => '', 'genus_h' => '',
    					'species_h' => '', 'subsp_h' => '', 'var_h' => '', 'subvar_h' => '', 'forma_h' => '', 'authors_h' => '', 'tribus' => '', 'italic' => true),
    			(array)$options
    			);
    				
    	$publication = $options['publication'];
    	$hybrid = $options['ishybrid'];
    	$syntype = $options['syntype'];
    	$genus_h = $options['genus_h'];
    	$species_h = $options['species_h'];
    	$subsp_h = $options['subsp_h'];
    	$var_h = $options['var_h'];
    	$subvar_h = $options['subvar_h'];
    	$forma_h = $options['forma_h'];
    	$authors_h = $options['authors_h'];
    	$tribus = $options['tribus'];
    	$italic = $options['italic'];
    				
        $name = '';
        $autLast = true;
        $sl = false;
        $name .= $syntype == '1' ? '"' : '';
        if (strpos($species, 's.l.')) {
            $species = trim(str_replace('s.l.', '', $species));
            $sl = true;
        }
        $name .= $italic ? "<i>$genus $species</i>" : "$genus $species";
        $name .= $sl ? ' s.l.' : '';
        if (trim($subsp) == trim($species) || trim($var) == trim($species) || trim($forma) == trim($species)) {
            $name .= " $authors";
            $autLast = false;
        }
        if (!empty($subsp)) {
            $subsp_r = $subsp;
            $subsp_n = "";
            if (strpos($subsp, "[unranked]") !== false) {
                $subsp_r = str_replace("[unranked]", "", $subsp_r);
                $subsp_n .= ' [unranked]';
            }
            if (strpos($subsp, "proles") !== false) {
                $subsp_r = str_replace("proles", "", $subsp_r);
                $subsp_n .= ' "proles"';
            }
            $subsp_r = $italic ? ("<i>" . trim($subsp_r) . "</i>") : trim($subsp_r);
            $name .=!empty($subsp_n) ? " $subsp_n $subsp_r" : " subsp. $subsp_r";
        }
        if (!empty($var)) {
            $var = $italic ? "<i>$var</i>" : $var;
            $name .= " var. $var";
        }
        if (!empty($subvar)) {
            $subvar = $italic ? "<i>$subvar</i>" : $subvar;
            $name .= " subvar. $subvar";
        }
        if (!empty($forma)) {
            $forma = $italic ? "<i>$forma</i>" : $forma;
            $name .= " forma $forma";
        }
        if ($autLast) {
            $name .= " $authors";
        }
        if ($hybrid) {
            $name .= " x ";
            $name .= $this->_los($genus_h, $species_h, $subsp_h, $var_h, $subvar_h, $forma_h, $authors_h, array('italic' => $italic));
        }
        $name .= $syntype == '1' ? '"' : '';
        return $name . (empty($publication) ? '' : ', ' . $publication) . (empty($tribus) ? '' : ' (tribus ' . $tribus . ')');
    }

    private function _array($value, $linkField, $options = array()) {
    	$options = array_merge(
    			array('link' => false, 'listPrepend' => '', 'liclass' => array(), 'special' => ''),
    			(array)$options
    			);
    	$liclass = $options['liclass'];
    	$listPrepend = $options['listPrepend'];
    	$special = $options['special'];
    	
        $out = '';
        $class = empty($liclass) ? '' : $liclass[0];
        
        if (is_array($value)) {
            $out .= '<ul>';
            foreach ($value as $v) {
                $out .= $this->_synonym($v['name'], $v['id'], false, array('link' => $options['link'], 'listPrepend' => $listPrepend, 'special' => $special, 'liclass' => $class));
                
                if (isset($v[$linkField]) && !empty($v[$linkField])) {
                	$options['liclass'] = array_slice($liclass, 1);
                    $out .= $this->_array($v[$linkField], $linkField, $options);
                }
                $out .= '</li>';
            }
            $out .= '</ul>';
        } else {
        	$out .= $this->_synonym($value['name'], $value['id'], true, array('link' => $options['link'], 'listPrepend' => $listPrepend, 'special' => $special, 'liclass' => $class));
        }
        return $out;
    }

    /**
     * 
     * @param unknown $name
     * @param unknown $linkId
     * @param array $options
     * @return string
     * 
     *  $link = false, $listPrepend = '', $liclass = '', $special = '', $close = true
     */
    private function _synonym($name, $linkId, $close, $options = array()) {
    	$options = array_merge(
    			array('link' => false, 'listPrepend' => '', 'liclass' => '', 'special' => ''),
    			(array)$options
    			);
    	$link = $options['link'];
    	$listPrepend = $options['listPrepend'];
    	$liclass = $options['liclass'];
    	$special = $options['special'];
    	
        $out = '<li';
        if (!empty($special) && strpos($name, $special) !== false) {
            $out .= ' class="' . $special . '">';
        } else {
            $out .= empty($liclass) ? '>' : (' class="' . $liclass . '">');
        }
        $out .= $listPrepend . ' ' . ($link ? $this->Html->link($name, array('controller' => 'checklists', 'action' => 'detail', $linkId), array('escape' => false)) : $name);
        $out .= $close? '</li>' : '';
        return $out;
    }

}
