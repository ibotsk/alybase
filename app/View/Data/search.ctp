<div class="buttons">
    <?php

//echo $this->element('sql_dump');
    $ids = Set::classicExtract($results, '{n}.ListOfSpecies.standardised_name.id');
    $idsstring = '';
    if (!empty($ids)) {
        $idsstring = implode('|', $ids);
        echo $this->Form->create('Data', array('type' => 'post', 'url' => array('controller' => 'data', 'action' => 'view_rtf', 'ext' => 'rtf', 'export')));
        echo $this->Form->hidden('exportIds', array('value' => $idsstring));
        echo $this->Form->hidden('exportType', array('value' => $type));
        echo $this->Form->end(array('label' => 'Export', 'class' => 'btn btn-default'));
    }
    ?>
    <div>
        <!--<a id="showAllOnMap" href="#">View all on map</a>-->
    </div>
</div>
<div id="fade"></div>
<div id="chromMapWrap">
    <div id="loader">
        <?php echo $this->Html->image('loader.gif', array('width' => '100px')); ?>
    </div>
    <a id="closeMap">Close</a>
    <div id="chromMap"></div>
    <div id="chromMapLegend"></div>
</div>
<table id="chromResults" class="table table-bordered table-condensed table-responsive">
    <col width="50%"/>
    <tr>
        <th>Name</th>
        <th>Accepted name</th>
    </tr>
    <?php
    foreach ($results as $record) :
        $sd_name = $record['ListOfSpecies']['standardised_name'];
        $acc_name = $record['ListOfSpecies']['accepted_name'];
        $std_name_str = $this->Format->los($sd_name, array('italic' => false, 'publication' => false));
        ?>
        <tr id="<?php echo $type . '/' . $sd_name['id']; ?>">
            <td>
                <span class="arrow"><?php echo $this->Html->image('icon-arrow-down.png', array('width' => '10px')); ?></span>
                <?php echo $std_name_str; ?>
            </td>
            <td>
                <?php
                if (!empty($acc_name)) {
                    //echo $this->Html->link($acc_name, array('controller' => 'data', 'action' => 'chromosomes', $link, $record['ListOfSpecies']['id_link']));
                	echo $this->Format->los($acc_name, array('italic' => false, 'publication' => false));
                } else {
                    echo $std_name_str;
                }
                ?>
            </td>
        </tr>
        <tr>
            <td colspan="2" class="detail">
                <ul>
                </ul>
            </td>
        </tr>
        <?php
    endforeach;
    ?>

</table>