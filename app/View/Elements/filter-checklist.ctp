<?php
echo $this->Form->create('Filter', array('type' => 'post', 'url' => array('controller' => 'checklists', 'action' => 'search'),
    'class' => 'formHorizontal', 'role' => 'form', 'inputDefaults' => array('label' => false, 'div' => false)));
?>
<div class="row">
    <div class="col-md-4">
        <h4>Name search:</h4>
        <div class="form-group">
            <?php echo $this->Form->label('genus', 'Genus:', array('class' => 'control-label col-md-3')); ?>
            <div class="col-md-9">
                <?php echo $this->Form->input('genus', array('type' => 'text', 'class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('species', 'Species:', array('class' => 'control-label col-md-3')); ?>
            <div class="col-md-9">
                <?php echo $this->Form->input('species', array('type' => 'text', 'class' => 'form-control')); ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('infra', 'Infraspecific epithet:', array('class' => 'control-label col-md-3')); ?>
            <div class="col-md-9">
                <?php echo $this->Form->input('infra', array('type' => 'text', 'class' => 'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <h4>Author search:
            <small>Standard form (<a href="http://www.ipni.org/ipni/authorsearchpage.do">IPNI</a>)</small></h4>
        <div class="form-group">
            <?php echo $this->Form->label('infra', 'Authors:', array('class' => 'control-label col-md-3')); ?>
            <div class="col-md-9">
                <?php echo $this->Form->input('authors', array('type' => 'text', 'class' => 'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="checkbox">
            <?php echo $this->Form->input('types', array('type' => 'checkbox', 'value' => 1, 'label' => 'All names', 'checked' => true)); ?>
        </div>
        <div>or (multiple choices possible):</div>
        <?php
        $options = array(2 => 'Accepted names', 3 => 'Provisionally accepted names', 4 => 'Synonyms', 5 => 'Doubtful synonyms', 6 => 'Unresolved names', 7 => 'Designations not validly published');
        echo $this->Form->input('types', array('type' => 'select', 'multiple' => 'checkbox', 'options' => $options, 'label' => false, 'hiddenField' => false, 'div' => array('id' => 'FilterTypesMultiple', 'class' => null)));
        ?>
    </div>
</div>
<div class="col-md-5"></div>
    <div class="col-md-2">
        <?php echo $this->Form->end(array('label' => 'Find', 'class' => 'btn btn-default', 'div' => false)); ?>
    </div>
    <div class="col-md-5"></div>
<?php
