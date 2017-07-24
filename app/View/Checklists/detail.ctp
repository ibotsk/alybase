
<?php 
	$params = $this->params['pass'];
	$debug = false;
	if (!empty($params[1]) && $params[1] == 'debug') {
		$debug = true;
		echo $this->Html->link('Next', array('controller' => 'checklists', 'action' => 'detail', $params[0] + 1, 'debug'));
	}
?>

<?php if (isset($result) && !empty($result)) : ?>


<div id="taxonomy" class="well well-sm">
    <h3><?php echo $this->Format->los($result['ListOfSpecies'], array('italic' => true, 'publication' => false, 'special' => 'is_isonym', 'debug' => $debug)); ?></h3>
    <h4><?php echo $this->Format->status($result['ListOfSpecies']['ntype'], array('is_invalid' => $result['ListOfSpecies']['syn_type'] == '1')); ?></h4>
</div>
<div class="dblock">
    <div class="row">
        <div class="col-sm-12">
            <?php echo $this->Format->detailValue('Published in:', $result['ListOfSpecies']['publication']); ?>
        </div>
    </div>
</div>
<?php
if ($result['Basionym']['id']) :
    ?>
    <div class="dblock">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $basionym = $this->Html->link($this->Format->los($result['Basionym'], array('italic' => true, 'debug' => $debug)), array('controller' => 'checklists', 'action' => 'detail', $result['Basionym']['id']), array('escape' => false));
                echo $this->Format->detailValue('Basionym:', $basionym);
                ?>
            </div>
        </div>
    </div>
    <?php
endif;
if ($result['Replaced']['id']) :
    ?>
    <div class="dblock">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $replaced = $this->Html->link($this->Format->los($result['Replaced'], array('italic' => true, 'debug' => $debug)), array('controller' => 'checklists', 'action' => 'detail', $result['Replaced']['id']), array('escape' => false));
                echo $this->Format->detailValue('Replaced name:', $replaced);
                ?>
            </div>
        </div>
    </div>
    <?php
endif;
?>
<div id="synonyms" class="dblock">
    <?php if ($result['ListOfSpecies']['ntype'] == 'A' || $result['ListOfSpecies']['ntype'] == 'PA') : ?>
        <div class="row">
            <span class="dlabel col-sm-12">Synonyms:</span>
        </div>
    <?php endif;
    ?>
    <div class="row">
        <div class="col-sm-12">
            <?php
            if (!empty($result['SynonymsNomenclatoric'])) :
                ?>
                <?php
                $tss = $this->Format->losList($result['SynonymsNomenclatoric'], array('italic' => true, 'arrayField' => 'SynonymsNomenclatoric', 'special' => 'is_isonym', 'debug' => $debug));
                echo $this->Format->detailValue('', $tss, array('link' => true, 'liclass' => array('triple', 'triple'), 'special' => 'isonym'));
                ?>
                <?php
            endif;
            if ($result['ListOfSpecies']['ntype'] == 'A' || $result['ListOfSpecies']['ntype'] == 'PA') {
            	$syns = $this->Format->losList($result['SynonymsTaxonomic'], array('italic' => true, 'arrayField' => 'SynonymsNomenclatoric', 'special' => 'is_isonym', 'debug' => $debug));
                if (!empty($syns)) {
                    echo $this->Format->detailValue('', $syns, array('link' => true, 'arrayField' => 'SynonymsNomenclatoric', 'liclass' => array('double', 'triple'), 'special' => 'isonym'));
                }
                
                $synsInv = $this->Format->losList($result['SynonymsInvalid'], array('italic' => true, 'debug' => $debug));
                if (!empty($synsInv)) {
                    echo '<br />';
                    echo $this->Format->detailValue('Designations not validly published: ', $synsInv, array('link' => true, 'wrap' => 'p', 'liclass' => array('invalid')));
                }
                
            } else if ($result['Accepted']['id']) {
            	
            	$name = $this->Format->los($result['Accepted'], array('italic' => true, 'debug' => $debug));
            	$linkName = $this->Html->link($name, array('controller' => 'checklists', 'action' => 'detail', $result['Accepted']['id']), array('escape' => false));
            	
            	if ($result['ListOfSpecies']['ntype'] == 'S' || $result['ListOfSpecies']['ntype'] == 'DS' || ($result['ListOfSpecies'] == 'U' && $result['ListOfSpecies']['syn_type'] == null)) {
            		echo $this->Format->detailValue('Accepted name:', $linkName);
            	} else if ($result['ListOfSpecies']['ntype'] == 'U' && $result['ListOfSpecies']['syn_type'] == '3') {
            		echo $this->Format->detailValue('Illegitimate, superfluous name (Art. 52) for: ', $linkName);
            	} else if ($result['ListOfSpecies']['ntype'] == 'H') {
            		echo $this->Format->detailValue('', $linkName);
            	}
            	
            }
            
            /*
            else if ($result['Accepted']['id'] && ($result['ListOfSpecies']['ntype'] == 'S' || $result['ListOfSpecies']['ntype'] == 'DS' || ($result['ListOfSpecies'] == 'U' && $result['ListOfSpecies']['syn_type'] == null))) {
            	$name = $this->Format->los($result['Accepted'], array('italic' => true));
                echo $this->Format->detailValue('Accepted name:', $this->Html->link($name, array('controller' => 'checklists', 'action' => 'detail', $result['Accepted']['id']), array('escape' => false)));
            } else if ($result['Accepted']['id'] && $result['ListOfSpecies']['ntype'] == 'U' && $result['ListOfSpecies']['syn_type'] == '3') {
            	$name = $this->Format->los($result['Accepted'], array('italic' => true));
                echo $this->Format->detailValue('Illegitimate, superfluous name (Art. 52) for: ', $this->Html->link($name, array('controller' => 'checklists', 'action' => 'detail', $result['Accepted']['id']), array('escape' => false)));
            } else if ($result['Accepted']['id'] && $result['ListOfSpecies']['ntype'] == 'H') {
            	$name = $this->Format->los($result['Accepted'], array('italic' => true));
                echo $this->Format->detailValue('', $this->Html->link($name, array('controller' => 'checklists', 'action' => 'detail', $result['Accepted']['id']), array('escape' => false)));
            }
            */
            ?>
        </div>
    </div>
</div>
<?php
if (!empty($result['BasionymFor'])) :
    ?>
    <div class="dblock">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $basfor = $this->Format->losList($result['BasionymFor'], array('italic' => true, 'debug' => $debug));
                echo $this->Format->detailValue('Basionym for:', $basfor, array('link' => true));
                ?>
            </div>
        </div>
    </div>
    <?php
endif;
if (!empty($result['NomenNovumFor'])) :
    ?>
    <div class="dblock">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $nomnovfor = $this->Format->losList($result['NomenNovumFor'], array('italic' => true, 'debug' => $debug));
                echo $this->Format->detailValue('Nomen novum for:', $nomnovfor, array('link' => true));
                ?>
            </div>
        </div>
    </div>
    <?php
endif;
if (!empty($result['ReplacedFor'])) :
    ?>
    <div class="dblock">
        <div class="row">
            <div class="col-sm-12">
                <?php
                $replacedfor = $this->Format->losList($result['ReplacedFor'], array('italic' => true, 'debug' => $debug));
                echo $this->Format->detailValue('Replaced name for:', $replacedfor, array('link' => true));
                ?>
            </div>
        </div>
    </div>
    <?php
endif;
?>
<hr />
<div class="dblock">
    <?php
    $url = array('controller' => 'checklists', 'action' => 'view_rtf', 'ext' => 'rtf', 'export');

    echo $this->Form->create('ListOfSpecies', array('type' => 'post', 'url' => $url));
    echo $this->Form->hidden('exportIds', array('value' => $result['ListOfSpecies']['id'], 'id' => 'exportIds'));
    echo $this->Form->end(array('label' => 'Export', 'id' => 'exportChoice', 'class' => 'btn btn-default', 'div' => false));
    ?>
</div>

<hr />
<div class="dblock comments">
    <h4>
        Annotations<br />
        <small>Annotations will be reviewed and posted after approval</small>
    </h4>
    <?php foreach ($comments as $comm) : ?>
        <div class="comment" style="margin-left: <?php echo $comm['LosComment']['nested'] * 12; ?>px;">
            <?php echo $this->Form->hidden('commentId', array('value' => $comm['LosComment']['id'])); ?>
            <div class="head">
                <span class="username"><?php echo $comm['LosComment']['author'] . ' - ' . $comm['LosComment']['institution']; ?></span>
                <span class="date"><?php echo preg_replace('/\.[0-9]*/', '', $comm['LosComment']['date_posted']); ?></span>
            </div>
            <div class="body">
                <?php echo $comm['LosComment']['annotation']; ?>
            </div>
            <div class="foot">
                <a class="reply" href="">Reply</a>
                <a class="closeReply" href="">Close</a>
            </div>
        </div>
        <?php
    endforeach;
    ?>
    <button id="addNewComment" class="btn btn-default">Add new annotation...</button>
    <div id="replyField">
        <?php
        echo $this->Form->create('LosComment', array('method' => 'post', 'url' => array('controller' => 'loscomments', 'action' => 'add')));
        echo $this->Form->hidden('parent_id', array('value' => 'null', 'id' => 'ParentId'));
        echo $this->Form->hidden('id_list_of_species', array('value' => $result['ListOfSpecies']['id']));
        ?>
        <div class="form-group">
            <?php echo $this->Form->input('author', array('label' => 'Name', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('institution', array('label' => 'Institution', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('annotation', array('type' => 'textarea', 'rows' => '5', 'cols' => '60', 'label' => 'Annotation', 'class' => 'form-control')); ?>
        </div>
        <?php echo $this->Form->end(array('label' => 'Submit', 'class' => 'btn btn-default', 'div' => false)); ?>
    </div>
</div>

<?php 
endif;

//new dBug($result);

