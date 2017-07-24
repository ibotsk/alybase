
<style>
<?php
if (isset($inlineCss)) {
    echo $inlineCss;
}

$debug = true;
?>
</style>

<?php foreach ($data as $d) : ?>
    <div>
        <?php
        $name = '<b>' . $this->Format->los($d['ListOfSpecies'], array('italic' => true, 'publication' => false, 'special' => 'is_isonym', 'debug' => $debug)) . '</b>';
        /* if ($d['ListOfSpecies']['tribus']) {
          $name .= ' (tribus ' . $d['ListOfSpecies']['tribus'] . ')';
          } */
        echo $name . "<br />";
        echo $this->Format->status($d['ListOfSpecies']['ntype'], array('is_invalid' => $d['ListOfSpecies']['syn_type'] == '1')) . "<br />";
        if (!empty($d['ListOfSpecies']['publication'])) {
            echo $d['ListOfSpecies']['publication'];
        }
        if ($d['Basionym']['id']) {
            echo '<br />';
            echo 'Basionym: ' . $this->Format->los($d['Basionym'], array('italic' => true, 'debug' => $debug));
        }
        if ($d['Replaced']['id']) {
            echo '<br />';
            echo 'Replaced name: ' . $this->Format->los($d['Replaced'], array('italic' => true, 'debug' => $debug));
        }

        if ((!empty($d['SynonymsNomenclatoric']) || !empty($d['SynonymsTaxonomic'])) && ($d['ListOfSpecies']['ntype'] == 'A' || $d['ListOfSpecies']['ntype'] == 'PA')) {
            echo '<br /><br />';
            echo '<b>Synonyms:</b>';
        }
        
        if (!empty($d['SynonymsNomenclatoric'])) {
        	$tss = $this->Format->losList($d['SynonymsNomenclatoric'], array('italic' => true, 'arrayField' => 'SynonymsNomenclatoric', 'special' => 'is_isonym', 'debug' => $debug));
        	if (!empty($tss)) {
        		$tss_detail = $this->Format->detailValue('', $tss, array('liclass' => array('triple', 'triple'), 'special' => 'isonym'));
        		$triplsyn = str_replace('<li class="triple">', '<li class="triple">&equiv;', $tss_detail);
        		echo str_replace('<li class="isonym">', '<li class="isonym">&ndash;', $triplsyn);
        	}
        }
        //$tss = $this->Format->losList($d['SynonymsTriple'], true, 'id', 'BasionymFor', true, 'is_isonym');
        //if (!empty($tss)) {
        //    $triplsyn = str_replace('<li class="triple">', '<li class="triple">&equiv;', $this->Format->detailValue('', $tss, false, '', '', array('triple', 'triple'), 'isonym'));
        //    echo str_replace('<li class="isonym">', '<li class="isonym">&ndash;', $triplsyn);
        //}
        
        
        if ($d['ListOfSpecies']['ntype'] == 'A' || $d['ListOfSpecies']['ntype'] == 'PA') {
            echo "<br />";
            $syns = $this->Format->losList($d['SynonymsTaxonomic'], array('italic' => true, 'arrayField' => 'SynonymsNomenclatoric', 'special' => 'is_isonym', 'debug' => $debug));
            if (!empty($syns)) {
                echo "<br />";
                $synout = $this->Format->detailValue('', $syns, array('arrayField' => 'SynonymsNomenclatoric', 'liclass' => array('double', 'triple'), 'special' => 'isonym'));
                $synout = str_replace('<li class="triple">', '<li class="triple">&equiv;', $synout);
                $synout = str_replace('<li class="double">', '<li class="double">=', $synout);
                echo str_replace('<li class="isonym">', '<li class="isonym">&ndash;', $synout);
            }
            $synsInv = $this->Format->losList($d['SynonymsInvalid'], array('italic' => true, 'debug' => $debug));
            if (!empty($synsInv)) {
                echo '<br />';
                $synsInv_detail = $this->Format->detailValue('Designations not validly published: ', $synsInv, array('liclass' => array('invalid')));
                echo str_replace('<li class="invalid">', '<li class="invalid">&ndash;', $synsInv_detail);
            }
        } else if ($d['Accepted']['id']) {
        	$name = $this->Format->los($d['Accepted'], array('italic' => true, 'debug' => $debug));
        	
        	if ($d['ListOfSpecies']['ntype'] == 'S' || $d['ListOfSpecies']['ntype'] == 'DS' || ($d['ListOfSpecies'] == 'U' && $d['ListOfSpecies']['syn_type'] == null)) {
        		echo $this->Format->detailValue('Accepted name:', $name);
        	} else if ($d['ListOfSpecies']['ntype'] == 'U' && $d['ListOfSpecies']['syn_type'] == '3') {
        		echo $this->Format->detailValue('Illegitimate, superfluous name (Art. 52) for: ', $name);
        	} else if ($d['ListOfSpecies']['ntype'] == 'H') {
        		echo $this->Format->detailValue('', $name);
        	}
        }
        
        /*
        else if ($d['Accepted']['id'] && ($d['ListOfSpecies']['ntype'] == 'S' || $d['ListOfSpecies']['ntype'] == 'DS' || ($d['ListOfSpecies'] == 'U' && $d['ListOfSpecies']['syn_type'] == null))) {
            echo $this->Format->detailValue('Accepted name:', $this->Format->los($d['Accepted'], true));
        } else if ($d['Accepted']['id'] && $d['ListOfSpecies']['ntype'] == 'U' && $d['ListOfSpecies']['syn_type'] == '3') {
            echo $this->Format->detailValue('Illegitimate, superfluous name (Art. 52) for: ', $this->Format->los($d['Accepted'], true));
        } else if ($d['Accepted']['id'] && $d['ListOfSpecies']['ntype'] == 'H') {
            echo $this->Format->detailValue('', $this->Format->los($d['Accepted'], true));
        }
        */

        if (!empty($d['BasionymFor'])) {
        	$basfor = $this->Format->losList($d['BasionymFor'], array('italic' => true, 'debug' => $debug));
            echo "<br />";
            echo $this->Format->detailValue('Basionym for: ', $basfor);
        }
        if (!empty($d['NomenNovumFor'])) {
        	$nomnovfor = $this->Format->losList($d['NomenNovumFor'], array('italic' => true, 'debug' => $debug));
            echo "<br />";
            echo $this->Format->detailValue('Nomen novum for: ', $nomnovfor);
        }
        if (!empty($d['ReplacedFor'])) {
        	$replacedfor = $this->Format->losList($d['ReplacedFor'], array('italic' => true, 'debug' => $debug));
            echo "<br />";
            echo $this->Format->detailValue('Replaced name for: ', $replacedfor);
        }
        ?>
    </div>
    <?php
endforeach;
