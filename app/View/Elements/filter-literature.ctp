<?php
echo $this->Form->create('Filter', array('type' => 'post', 'url' => array('controller' => 'literatures', 'action' => 'search'),
    'class' => 'formHorizontal', 'role' => 'form', 'inputDefaults' => array('label' => false, 'div' => false)));
?>
<div class="row">
    <div class="col-md-6">
        <h4>Author search</h4>
        <div class="form-group">
            <?php echo $this->Form->label('author', 'Publication (co-)author:', array('class' => 'control-label col-md-5')); ?>
            <div class="col-md-7">
                <?php echo $this->Form->input('author', array('type' => 'text', 'class' => 'form-control')); ?>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <h4>Year of publication search</h4>
        <div class="form-group">
            <?php echo $this->Form->input('year', array('type' => 'number', 'label' => false, 'class' => 'col-md-3 form-control')); ?>
            <div class="col-md-9"></div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5"></div>
    <div class="col-md-2 text-center">
        <?php echo $this->Form->end(array('label' => 'Find', 'class' => 'btn btn-default', 'div' => false)); ?>
    </div>
    <div class="col-md-5"></div>
</div>