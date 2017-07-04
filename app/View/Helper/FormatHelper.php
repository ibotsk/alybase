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
        $out = $lit['paper_author'] . ' (' . $lit['year'] . ') ' . $lit['paper_title'] . '. ';
        if (isset($options['link'])) {
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
     * @param type $label
     * @param string $value
     * @param type $link
     * @param type $wrap indicate whether label and value spans should be wrapped in paragraph element
     * @return string
     */
    public function detailValue($label, $value, $link = false, $wrap = '', $listPrepend = '', $liclass = array(), $special = '') {
        $out = empty($wrap) ? '' : ('<' . $wrap . '>');
        if (isset($label) && !empty($label) && $label != 'null') {
            $out .= '<span class="dlabel">' . $label . '</span>';
        }

        if (isset($value) && !empty($value) && $value != 'null') {
            $out .= '<span class="value">';
            if (is_array($value)) {
                $out .= $this->_array($value, 'BasionymFor', $link, $listPrepend, $liclass, $special);
            } else {
                $out .= $value;
            }
            $out .= '</span>';
        }
        $out .= empty($wrap) ? '' : ('</' . $wrap . '>');
        return $out;
    }

    public function status($status, $accepted = array(), $italic = false, $syntype = '') {
        $syn_out = "";
        switch ($status) {
            case 'A':
                return '<span class="accepted">Accepted</span>';
            case 'PA':
                return '<span class="paccepted">Provisionally accepted</span>';
            case 'U':
                $r = $syntype == '1' ? '<span class="invalid">Designation not validly published</span><br />' : '';
                return $r . '<span class="unresolved">Unresolved</span>';
            case 'S':
                $syn_out = $syntype == '1' ? '<span class="invalid">Designation not validly published</span>' : '<span class="synonym">Synonym</span>';
                break;
            case 'DS':
                $syn_out = $syntype == '1' ? '<span class="invalid">Designation not validly published</span>' : '<span class="dsynonym">Doubtful synonym</span>';
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
        if (!empty($accepted) && $accepted['id']) {
            $syn_out .= ' of ' . $this->Html->link($this->los($accepted, $italic), array('controller' => 'checklists', 'action' => 'detail', $accepted['id']), array('escape' => false));
        }
        return $syn_out;
    }

    public function los($name, $italic = false, $publication = true, $special = '', $tribus = true) {
        if (isset($name['name'])) {
            return $publication ? $name['name'] . ", " . $name['publication'] : $name['name'];
        }
        $syntype = $name['syn_type'];
        if (!empty($special) && isset($name[$special]) && $name[$special]) {
            $syntype = 1;
        }
        $out = $this->_los($italic, $name['genus'], $name['species'], $name['subsp'], $name['var'], $name['subvar'], $name['forma'], $name['authors'], $publication ? $name['publication'] : '', $name['hybrid'], $syntype, $name['genus_h'], $name['species_h'], $name['subsp_h'], $name['var_h'], $name['subvar_h'], $name['forma_h'], $name['authors_h'], $tribus && $name['tribus'] ? $name['tribus'] : '');
        return $out;
    }

    public function losList($list, $italic = false, $linkField = 'id', $arrayField = 'BasionymFor', $publication = true, $special = '', $tribus = true) {
        $out = array();
        foreach ($list as $item) {
            $newitem = array();
            $newitem['id'] = $item[$linkField];
            $newitem['name'] = $this->los($item, $italic, $publication, $special, $tribus);
            if (!empty($item[$arrayField])) {
                $newitem['BasionymFor'] = $this->losList($item[$arrayField], $italic, $linkField, $arrayField, $publication, $special, $tribus);
            }
            $out[] = $newitem;
        }
        return $out;
    }

    private function _los($italic, $genus, $species, $subsp, $var, $subvar, $forma, $authors, $publication = '', $hybrid = false, $syntype = '', $genus_h = '', $species_h = '', $subsp_h = '', $var_h = '', $subvar_h = '', $forma_h = '', $authors_h = '', $tribus = '') {
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
            $name .= $this->_los($italic, $genus_h, $species_h, $subsp_h, $var_h, $subvar_h, $forma_h, $authors_h);
        }
        $name .= $syntype == '1' ? '"' : '';
        return $name . (empty($publication) ? '' : ', ' . $publication) . (empty($tribus) ? '' : ' (tribus ' . $tribus . ')');
    }

    private function _array($value, $linkField, $link = false, $listPrepend = '', $liclass = array(), $special = '') {
        $out = '';
        $class = empty($liclass) ? '' : $liclass[0];
        if (is_array($value)) {
            $out .= '<ul>';
            foreach ($value as $v) {
                $out .= $this->_synonym($v['name'], $v['id'], $link, $listPrepend, $class, $special, false);
                if (isset($v[$linkField]) && !empty($v[$linkField])) {
                    $out .= $this->_array($v[$linkField], $linkField, $link, $listPrepend, array_slice($liclass, 1), $special);
                }
                $out .= '</li>';
            }
            $out .= '</ul>';
        } else {
            $out .= $this->_synonym($value['name'], $value['id'], $link, $listPrepend, $class, $special);
        }
        return $out;
    }

    private function _synonym($name, $linkId, $link = false, $listPrepend = '', $liclass = '', $special = '', $close = true) {
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
