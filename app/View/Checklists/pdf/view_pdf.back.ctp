<style>
    div { border-bottom: 1px solid #cecece; }
</style>

<?php foreach ($data as $d) : ?>
    <div>
        <?php
        $name = '<b>' . $this->Format->los($d['ListOfSpecies'], true) . '</b>';
        if ($d['ListOfSpecies']['tribus']) {
            $name .= ' (tribus ' . $d['ListOfSpecies']['tribus'] . ')';
        }
        echo $this->Format->detailValue('', $name, false, '');
        echo $this->Format->detailValue('', $this->Format->status($d['ListOfSpecies']['ntype'], false, ''));
        if (!empty($d['ListOfSpecies']['publication'])) {
            echo $this->Format->detailValue('', $d['ListOfSpecies']['publication'], false, '');
        }
        //echo "<br />";
        if ($d['ListOfSpecies']['ntype'] == 'A' || $d['ListOfSpecies']['ntype'] == 'PA' || $d['ListOfSpecies']['ntype'] == 'U') {
            //echo $this->Format->detailValue('Synonyms:', $result[])
            $syns = array();
            foreach ($d['Synonyms'] as $r) {
                $syns[$r['id']] = $this->Format->los($r, true) . "; " . $r['publication'];
            }
            echo $this->Format->detailValue('Synonyms: ', $syns);
        } else {
            echo $this->Format->detailValue('Accecpted name: ', $this->Format->los($d['Accepted'], true), false, '');
            //echo 'Accepted name: ' . $this->Format->los($d['Accepted'], true) . "<br />";
        }
        if ($d['Basionym']['id']) {
            //echo "<br />";
            //echo 'Basionym: ' . $this->Format->los($d['Basionym'], true);
            echo $this->Format->detailValue('Basionym: ', $this->Format->los($d['Basionym'], true), false, '');
        }
        if (!empty($d['BasionymFor'])) {
            $basfor = array();
            foreach ($d['BasionymFor'] as $b) {
                $basfor[$b['id']] = $this->Format->los($b, true) . "; " . $b['publication'];
            }
            //echo "<br />";
            echo $this->Format->detailValue('Basionym for: ', $basfor);
        }
        ?>
        <br />
    </div>
    <?php
endforeach;
