
<style>
<?php
if (isset($inlineCss)) {
    echo $inlineCss;
}
?>
</style>

<?php foreach ($data as $d) : ?>
    <div>
        <?php
        $name = '<b>' . $this->Format->los($d['ListOfSpecies'], true, false, 'is_isonym') . '</b>';
        /* if ($d['ListOfSpecies']['tribus']) {
          $name .= ' (tribus ' . $d['ListOfSpecies']['tribus'] . ')';
          } */
        echo $name . "<br />";
        echo $this->Format->status($d['ListOfSpecies']['ntype'], array(), false, $d['ListOfSpecies']['syn_type']) . "<br />";
        if (!empty($d['ListOfSpecies']['publication'])) {
            echo $d['ListOfSpecies']['publication'];
        }
        if ($d['Basionym']['id']) {
            echo '<br />';
            echo 'Basionym: ' . $this->Format->los($d['Basionym'], true);
        }
        if ($d['Replaced']['id']) {
            echo '<br />';
            echo 'Replaced name: ' . $this->Format->los($d['Replaced'], true);
        }

        if ((!empty($d['SynonymsTriple']) || !empty($d['Synonyms'])) && ($d['ListOfSpecies']['ntype'] == 'A' || $d['ListOfSpecies']['ntype'] == 'PA')) {
            echo '<br /><br />';
            echo '<b>Synonyms:</b>';
        }
        $tss = $this->Format->losList($d['SynonymsTriple'], true, 'id', 'BasionymFor', true, 'is_isonym');
        if (!empty($tss)) {
            $triplsyn = str_replace('<li class="triple">', '<li class="triple">&equiv;', $this->Format->detailValue('', $tss, false, '', '', array('triple', 'triple'), 'isonym'));
            echo str_replace('<li class="isonym">', '<li class="isonym">&ndash;', $triplsyn);
        }
        if ($d['ListOfSpecies']['ntype'] == 'A' || $d['ListOfSpecies']['ntype'] == 'PA') {
            echo "<br />";
            $syns = $this->Format->losList($d['Synonyms'], true, 'id', 'BasionymFor', true, 'is_isonym');
            if (!empty($syns)) {
                echo "<br />";
                $synout = $this->Format->detailValue('', $syns, false, '', '', array('double', 'triple'), 'isonym');
                $synout = str_replace('<li class="triple">', '<li class="triple">&equiv;', $synout);
                $synout = str_replace('<li class="double">', '<li class="double">=', $synout);
                echo str_replace('<li class="isonym">', '<li class="isonym">&ndash;', $synout);
            }
            $synsInv = $this->Format->losList($d['SynonymsInvalid'], true);
            if (!empty($synsInv)) {
                echo '<br />';
                echo str_replace('<li class="invalid">', '<li class="invalid">&ndash;', $this->Format->detailValue('Designations not validly published: ', $synsInv, false, '', '', array('invalid')));
            }
        } else if ($d['Accepted']['id'] && ($d['ListOfSpecies']['ntype'] == 'S' || $d['ListOfSpecies']['ntype'] == 'DS' || ($d['ListOfSpecies'] == 'U' && $d['ListOfSpecies']['syn_type'] == null))) {
            echo $this->Format->detailValue('Accepted name:', $this->Format->los($d['Accepted'], true));
        } else if ($d['Accepted']['id'] && $d['ListOfSpecies']['ntype'] == 'U' && $d['ListOfSpecies']['syn_type'] == '3') {
            echo $this->Format->detailValue('Illegitimate, superfluous name (Art. 52) for: ', $this->Format->los($d['Accepted'], true));
        } else if ($d['Accepted']['id'] && $d['ListOfSpecies']['ntype'] == 'H') {
            echo $this->Format->detailValue('', $this->Format->los($d['Accepted'], true));
        }

        if (!empty($d['BasionymFor'])) {
            $basfor = $this->Format->losList($d['BasionymFor'], true);
            echo "<br />";
            echo $this->Format->detailValue('Basionym for: ', $basfor, false, '');
        }
        if (!empty($d['NomenNovumFor'])) {
            $nomnovfor = $this->Format->losList($d['NomenNovumFor'], true);
            echo "<br />";
            echo $this->Format->detailValue('Nomen novum for: ', $nomnovfor, false, '');
        }
        if (!empty($d['ReplacedFor'])) {
            $replacedfor = $this->Format->losList($d['ReplacedFor'], true);
            echo "<br />";
            echo $this->Format->detailValue('Replaced name for: ', $replacedfor, false, '');
        }
        ?>
    </div>
    <?php
endforeach;
