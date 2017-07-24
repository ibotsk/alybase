
<?php foreach ($data as $d) : ?>
    <p>
        <?php
        $name = '<b>' . $this->Format->los($d['ListOfSpecies'], array('italic' => true)) . '</b>';
        if ($d['ListOfSpecies']['tribus']) {
            $name .= ' (tribus ' . $d['ListOfSpecies']['tribus'] . ')';
        }
        echo $name . "<br />";
        echo $this->Format->status($d['ListOfSpecies']['ntype']) . "<br />";
        if (!empty($d['ListOfSpecies']['publication'])) {
            echo $d['ListOfSpecies']['publication'];
        }
        echo "<br />";
        if ($d['ListOfSpecies']['ntype'] == 'A' || $d['ListOfSpecies']['ntype'] == 'PA') {
            //echo $this->Format->detailValue('Synonyms:', $result[])
            $replaced = '';
            $syns = array();
            foreach ($d['Synonyms'] as $r) {
                if ($r['ntype'] == 'R') {
                	$replaced = $this->Format->los($r, array('italic' => true)) . "; " . $r['publication'];
                } else {
                	$syns[$r['id']] = $this->Format->los($r, array('italic' => true)) . "; " . $r['publication'];
                }
            }
            if (!empty($syns)) {
                echo "<br />";
                echo $this->Format->detailValue('Synonyms: ', $syns);
            }
            if (!empty($replaced)) {
                echo "<br />";
                echo 'Replaced name: ' . $replaced;
            }
        } else if ($d['ListOfSpecies']['ntype'] == 'S' || $d['ListOfSpecies']['ntype'] == 'DS') {
            echo "<br />";
            echo 'Accepted name: ' . $this->Format->los($d['Accepted'], array('italic' => true)) . "<br />";
        } else if ($d['ListOfSpecies']['ntype'] == 'R') {
            echo "<br />";
            echo 'Replaced name for: ' . $this->Format->los($d['Accepted'], array('italic' => true)) . "<br />";
        }

        if ($d['Basionym']['id']) {
            echo "<br />";
            echo 'Basionym: ' . $this->Format->los($d['Basionym'], array('italic' => true)) . "; " . $d['Basionym']['publication'];
            //echo $this->Format->detailValue('Basionym: ', $this->Format->los($d['Basionym'], true), false, '');
        }
        if (!empty($d['BasionymFor'])) {
            $basfor = array();
            foreach ($d['BasionymFor'] as $b) {
            	$basfor[$b['id']] = $this->Format->los($b, array('italic' => true)) . "; " . $b['publication'];
            }
            echo "<br />";
            echo $this->Format->detailValue('Basionym for: ', $basfor);
        }
        if (!empty($d['NomenNovumFor'])) {
            $nomnovfor = array();
            foreach ($d['NomenNovumFor'] as $n) {
            	$nomnovfor[$n['id']] = $this->Format->los($n, array('italic' => true)) . "; " . $n['publication'];
            }
            echo "<br />";
            echo $this->Format->detailValue('Nomen novum for: ', $nomnovfor);
        }
        ?>
        <br />
    </p>
    <?php
endforeach;
