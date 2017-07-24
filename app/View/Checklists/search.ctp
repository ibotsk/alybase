
<?php 

//echo $this->Html->link(__('Export'), array('controller' => 'checklists', 'action' => 'view_pdf', 'ext' => 'pdf', 'export'), array('id' => 'export'));
$ids = Hash::extract($results, '{n}.ListOfSpecies.id');
$idsstring = '';
if (!empty($ids)) {
    $idsstring = implode('|', $ids);
} 
echo $this->Form->create('ListOfSpecies', array('type' => 'post', 'url' => array('controller' => 'checklists', 'action' => 'view_rtf', 'ext' => 'rtf', 'export')));
echo $this->Form->hidden('exportIds', array('value' => $idsstring));
echo $this->Form->end(array('label' => 'Export', 'id' => 'exportChoice', 'class' => 'btn btn-default'));

//new dBug($results);
?>
<table id="checklistResults" class="table table-striped table-bordered table-condensed table-responsive">
    <col width="50%"/>
    <tr>
        <th>Name</th>
        <th>Status</th>
    </tr>
    <?php
//new dBug($results);
    foreach ($results as $result) :
    $name = $this->Format->los($result['ListOfSpecies'], array('italic' => true, 'publication' => false, 'special' => 'is_isonym'));
        ?>
        <tr>
            <td><?php echo $this->Html->link($name, array('controller' => 'checklists', 'action' => 'detail', $result['ListOfSpecies']['id']), array('escape' => false)); ?></td>
            <td><?php echo $this->Format->status($result['ListOfSpecies']['ntype'], array('parent' => $result['Accepted'], 'italic' => true, 'is_invalid' => $result['ListOfSpecies']['syn_type'] == '1')); ?></td>
        </tr>
        <?php
    endforeach;
    ?>
</table>
<?php
//echo $this->element('sql_dump');