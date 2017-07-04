<?php
echo $this->Form->create('Filter', array('type' => 'post', 'url' => array('controller' => 'data', 'action' => 'search'),
    'class' => 'formHorizontal', 'role' => 'form', 'inputDefaults' => array('label' => false, 'div' => false)));
?>
<!--<div class="group">
<?php ?>
</div>-->
<div class="row">
    <div class="col-md-4"><h4>Chromosome/ploidy search:</h4></div>
    <div class="col-md-8"></div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <?php echo $this->Form->input('chromN', array('type' => 'text', 'label' => 'Meiotic (gametophytic) chromosome counts (n):', 'class' => 'form-control')); ?>
        </div>
        <div class="form-group">
            <?php echo $this->Form->input('chromDn', array('type' => 'text', 'label' => 'Mitotic (sporophytic) chromosome counts (2n):', 'class' => 'form-control')); ?>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <?php
            $x = array('7' => '7', '8' => '8', '11' => '11', '15' => '15', '23' => '23');
            echo $this->Form->input('chromX', array('type' => 'select', 'options' => $x, 'empty' => '--', 'label' => 'Base chromosome number (revised):', 'class' => 'form-control'));
            ?>
        </div>
        <div class="form-group">
            <?php
            $ploidy = array('2x' => '2x', '3x' => '3x', '4x' => '4x', '6x' => '6x', '8x' => '8x', '12x' => '12x');
            echo $this->Form->input('chromPloidy', array('type' => 'select', 'options' => $ploidy, 'empty' => '--', 'label' => 'Ploidy level (revised):', 'class' => 'form-control'));
            ?>
        </div>
    </div>
    <div class="col-md-4"></div>
</div>
<div class="row">
    <div class="col-md-3">
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
        <div class="form-group" style="clear: both;">
            <?php
            $toptions = array(
                1 => 'Identification based on last revision',
                2 => 'Identification in the original publication',
                3 => 'All identifications and corresponding accepted names or synonyms');
            $type = !isset($type) ? 1 : $type;
            echo $this->Form->input('outtype', array('type' => 'radio', 'options' => $toptions, 'value' => $type,
                'legend' => false,
                'separator' => '</label></div><div class="radio"><label>',
                'before' => '<div class="radio"><label>',
                'after' => '</label></div>'));
            ?>
        </div>
    </div>
    <div class="col-md-3">
        <h4>Author search:</h4>
        <div class="form-group">
            <?php
            $poptions = $this->requestAction('literatures/view');
            echo $this->Form->input('authorPu', array('type' => 'select', 'options' => $poptions, 'empty' => '---', 'label' => 'Publication (co-)author:', 'class' => 'form-control'));
            ?>
        </div>
        <div class="form-group">
            <?php
            $aoptions = $this->requestAction('persons/view');
            echo $this->Form->input('authorAn', array('type' => 'select', 'options' => $aoptions, 'empty' => '---', 'label' => 'Analysis (co-)author:', 'class' => 'form-control'));
            ?>
        </div>
        <div>
            <?php echo $this->Html->link('References for chromosome number/ploidy level data', array('controller' => 'literatures')); ?>
        </div>
    </div>
    <div class="col-md-3">
        <h4>Location search:</h4>
        <div class="form-group">
            <?php echo $this->Form->label('infra', 'Level 1:', array('class' => 'control-label col-md-4')); ?>
            <div class="col-md-8">
                <?php
                $w1options = $this->requestAction('worlds/view/1');
                echo $this->Form->input('world1', array('type' => 'select', 'options' => $w1options, 'empty' => '---', 'class' => 'form-control'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('infra', 'Level 2:', array('class' => 'control-label col-md-4')); ?>
            <div class="col-md-8">
                <?php
                $w2options = $this->requestAction('worlds/view/2');
                echo $this->Form->input('world2', array('type' => 'select', 'options' => $w2options, 'empty' => '---', 'class' => 'form-control'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('infra', 'Level 3:', array('class' => 'control-label col-md-4')); ?>
            <div class="col-md-8">
                <?php
                $w3options = $this->requestAction('worlds/view/3');
                echo $this->Form->input('world3', array('type' => 'select', 'options' => $w3options, 'empty' => '---', 'class' => 'form-control'));
                ?>
            </div>
        </div>
        <div class="form-group">
            <?php echo $this->Form->label('infra', 'Level 4:', array('class' => 'control-label col-md-4')); ?>
            <div class="col-md-8">
                <?php
                $w4options = $this->requestAction('worlds/view/4');
                echo $this->Form->input('world4', array('type' => 'select', 'options' => $w4options, 'empty' => '---', 'class' => 'form-control'));
                ?>
            </div>
        </div>

        <!--<div class="form-group">
        <?php
        $w2options = $this->requestAction('worlds/view/2');
        echo $this->Form->input('world2', array('type' => 'select', 'options' => $w2options, 'empty' => '---', 'label' => 'Level 2:', 'class' => 'form-control'));
        ?>
        </div>
        <div class="form-group">
        <?php
        $w3options = $this->requestAction('worlds/view/3');
        echo $this->Form->input('world3', array('type' => 'select', 'options' => $w3options, 'empty' => '---', 'label' => 'Level 3:', 'class' => 'form-control'));
        ?>
        </div>
        <div class="form-group">
        <?php echo $this->Form ?>
        <?php
        $w4options = $this->requestAction('worlds/view/4');
        echo $this->Form->input('world4', array('type' => 'select', 'options' => $w4options, 'empty' => '---', 'label' => 'Level 4:', 'class' => 'form-control'));
        ?>
        </div>-->
    </div>
    <div class="col-md-3">
        <h4>Location according to geographical coordinates:</h4>
        <div class="form-group">
            <span class="col-md-4">Latitude:</span>
            <span class="col-md-6">
                <?php
                echo $this->Form->input('latitude', array('type' => 'radio', 'options' => array('N' => 'N', 'S' => 'S'), 'value' => 'N',
                    'legend' => false,
                    'separator' => '</label><label class="radio-inline">',
                    'before' => '<label class="radio-inline">',
                    'after' => '</label>'));
                ?>
            </span>
        </div>
        <div class="form-group latlong">
            <span class="col-md-3">
                <?php echo $this->Form->input('latDegrees', array('type' => 'number', 'class' => 'form-control', 'min' => '0', 'max' => '180')); ?>
            </span>
            <?php echo $this->Form->label('latDegrees', '°', array('class' => 'control-label col-md-1')); ?>
            <span class="col-md-3">
                <?php echo $this->Form->input('latMinutes', array('type' => 'number', 'class' => 'form-control', 'min' => '0', 'max' => '59')); ?>
            </span>
            <?php echo $this->Form->label('latMinutes', "'", array('class' => 'control-label col-md-1')); ?>
            <span class="col-md-3">
                <?php echo $this->Form->input('latSeconds', array('type' => 'number', 'class' => 'form-control', 'min' => '0', 'max' => '59.999')); ?>
            </span>
            <?php echo $this->Form->label('latSeconds', "''", array('class' => 'control-label col-md-1')); ?>
        </div>
        <div class="form-group">
            <span class="col-md-4">Longitude:</span>
            <span class="col-md-6">
                <?php
                echo $this->Form->input('longitude', array('type' => 'radio', 'options' => array('W' => 'W', 'E' => 'E'), 'value' => 'E',
                    'legend' => false,
                    'separator' => '</label><label class="radio-inline">',
                    'before' => '<label class="radio-inline">',
                    'after' => '</label>'));
                ?>
            </span>
        </div>
        <div class="form-group latlong">
            <span class="col-md-3">
                <?php echo $this->Form->input('lonDegrees', array('type' => 'number', 'class' => 'form-control', 'min' => '0', 'max' => '180')); ?>
            </span>
            <?php echo $this->Form->label('lonDegrees', '°', array('class' => 'control-label col-md-1')); ?>
            <span class="col-md-3">
                <?php echo $this->Form->input('lonMinutes', array('type' => 'number', 'class' => 'form-control', 'min' => '0', 'max' => '59')); ?>
            </span>
            <?php echo $this->Form->label('lonMinutes', "'", array('class' => 'control-label col-md-1')); ?>
            <span class="col-md-3">
                <?php echo $this->Form->input('lonSeconds', array('type' => 'number', 'class' => 'form-control', 'min' => '0', 'max' => '59.999')); ?>
            </span>
            <?php echo $this->Form->label('lonSeconds', "''", array('class' => 'control-label col-md-1')); ?>
        </div>
        <div class="form-group latlong">
            <div><label class="control-label">Range in degrees:</label></div>
            <?php echo $this->Form->label('range', '&PlusMinus;', array('class' => 'control-label col-md-1')); ?>
            <span class="col-md-10">
                <?php echo $this->Form->input('range', array('type' => 'number', 'min' => '0', 'step' => '0.001', 'class' => 'form-control')); ?>
            </span>
            <?php echo $this->Form->label('range', '°', array('class' => 'control-label col-md-1')); ?>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-5"></div>
    <div class="col-md-2">
        <?php echo $this->Form->end(array('label' => 'Find', 'class' => 'btn btn-default', 'div' => false)); ?>
    </div>
    <div class="col-md-3"></div>
    <div class="col-md-2">
        <?php echo $this->Form->button('Clear fields', array('id' => 'ClearFieldsData', 'class' => 'btn btn-default', 'div' => false)); ?>
    </div>
    
</div>
