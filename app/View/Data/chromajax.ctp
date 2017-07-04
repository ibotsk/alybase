
<?php
header('Content-type: text/html');
//new dBug($results);
?>
<li>
    Found <?php echo count($results); ?> records, <a class="showmap" href="#">View on map</a>
</li>
<?php
foreach ($results as $value) :
    ?>
    <li id="<?php echo $value['Cdata']['id']; ?>">
        <div>
            <?php
            if (!empty($value['Cdata']['n']) || !empty($value['Cdata']['dn'])) {
                $label = '<span>Chromosome number: </span><span class="value">' . $this->Format->chromosomes($value['Cdata']['n'], $value['Cdata']['dn'], $value['Dna']['ch_count']) . '</span>';
                echo $this->Html->link($label, array('controller' => 'data', 'action' => 'detail', $value['Cdata']['id']), array('escape' => false));
            }
            if (!empty($value['Dna']['ploidy']) || !empty($value['Dna']['size_from'])) {
                $label = "<span>Ploidy level and/or DNA content: </span>";
                $ploi = $value['Dna']['ploidy'];
                $label .= ($ploi == '' ? '' : '<span class="value">' . $ploi . '</span>');
                if (!empty($value['Dna']['size_from'])) {
                    $label .= ($ploi == '' ? '<span class="value">' : ', ') . $this->Format->genomeSize($value['Dna']['size_c'], $value['Dna']['size_from'], $value['Dna']['size_to'], $value['Dna']['size_units']) . ($ploi == '' ? '</span>' : '');
                }
                echo $this->Html->link($label, array('controller' => 'data', 'action' => 'detail', $value['Cdata']['id']), array('escape' => false));
            }
            ?>
        </div>
        <!--<p><em>Counted by: </em><?php //echo $value['CountedBy']['pers_name'];     ?></p>-->
        <div><em>Published in: </em><?php echo $this->Format->literature($value['Literature']); ?></div>
    </li>
    <?php
endforeach;
//echo $this->element('sql_dump');
//new dBug($results);
