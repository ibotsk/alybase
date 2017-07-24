<?php
//echo $this->element('sql_dump');
//new dBug($cdata);

$okImg = 'ok-16.png';
$nokImg = 'nok-16.png';
?>

<div class="row">
    <div class="col-md-6">
        <div class="dblock row">
            <div class="col-md-12">
                <h4>
                    <small>Name after last revision: </small>
                    <?php echo $this->Html->link($this->Format->los($cdata['ListOfSpeciesNewest']['ListOfSpecies'], array('italic' => true)), array('controller' => 'checklists', 'action' => 'detail', $cdata['ListOfSpeciesNewest']['ListOfSpecies']['id']), array('escape' => false)); ?>
                </h4>
                <h4>
                    <small>Name as originally published (standardised version): </small>
                    <?php echo $this->Html->link($this->Format->los($cdata['Reference']['ListOfSpecies'], array('italic' => true)), array('controller' => 'checklists', 'action' => 'detail', $cdata['Reference']['ListOfSpecies']['id']), array('escape' => false)); ?>
                </h4>
                <h4>
                    <small>Name exactly as originally published: </small>
                    <?php echo $cdata['Reference']['Reference']['name_as_published']; ?>
                </h4>
            </div>
        </div>
        <div class="dblock row">
            <div class="col-md-12">
                <h3>Chromosome number</h3>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Chromosome count: </span>
                    <span class="col-sm-6 text-left"><?php echo $this->Format->chromosomes($cdata['Cdata']['n'], $cdata['Cdata']['dn']); ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Ploidy as published in original source: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['Cdata']['ploidy_level']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Ploidy after last revision: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['Cdata']['ploidy_level_revised']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Base chromosome number (x) after last revision: </span>
                    <span class="col-sm-6 text-left"><?php echo empty($cdata['Cdata']['x_revised']) ? 'not assigned' : $cdata['Cdata']['x_revised']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Counted by: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['CountedBy']['pers_name']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Date: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['Cdata']['counted_date']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Number of analysed plants: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['Cdata']['number_of_analysed_plants']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Slide number: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['Cdata']['slide_no']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Deposited in: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['Cdata']['deposited_in']; ?></span>
                </div>
                <div class="row">
                    <span class="col-sm-6 text-left dlabel2">Karyotype: </span>
                    <span class="col-sm-6 text-left"><?php echo $cdata['Cdata']['karyotype']; ?></span>
                </div>
                <div class="row">
                    <div class="col-sm-12">
                        <span class="dlabel">Photo:</span><span class="value"><?php echo $this->Html->image($cdata['Cdata']['photo'] ? $okImg : $nokImg, array('alt' => ($cdata['Cdata']['photo'] ? "photo-yes" : "photo-no"))); ?></span>
                        <span class="dlabel">Idiogram:</span><span class="value"><?php echo $this->Html->image($cdata['Cdata']['idiogram'] ? $okImg : $nokImg, array('alt' => ($cdata['Cdata']['idiogram'] ? "photo-yes" : "photo-no"))); ?></span>
                        <span class="dlabel">Drawing:</span><span class="value"><?php echo $this->Html->image($cdata['Cdata']['drawing'] ? $okImg : $nokImg, array('alt' => ($cdata['Cdata']['drawing'] ? "photo-yes" : "photo-no"))); ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div id="detailMap" class="mapInfo"></div>
    </div>
</div>
<div class="row">
    <span class="col-sm-3 text-left dlabel2">Note: </span>
    <span class="col-sm-9 text-left"><?php echo $cdata['Cdata']['public_note']; ?></span>
</div>
<?php if (!empty($cdata['Dna']['method'])) : ?>
    <div class="dblock">
        <h3>Estimated ploidy level and/or DNA content</h3>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">Method: </span>
            <span class="col-sm-9 text-left"><?php echo $cdata['Dna']['method']; ?></span>
        </div>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">DNA ploidy level as published in the original source: </span>
            <span class="col-sm-9 text-left"><?php echo $cdata['Dna']['ploidy']; ?></span>
        </div>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">DNA ploidy level after last revision: </span>
            <span class="col-sm-9 text-left"><?php echo $cdata['Dna']['ploidy_revised']; ?></span>
        </div>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">Chromosome count: </span>
            <span class="col-sm-9 text-left"><?php
                $chc = $this->Format->chromosomes('', $cdata['Dna']['ch_count'], '', '&TildeTilde;');
                if ($chc != 'N/A') {
                    echo $chc;
                }
                ?>
            </span>
        </div>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">Genome size: </span>
            <span class="col-sm-9 text-left"><?php echo $this->Format->genomeSize($cdata['Dna']['size_c'], $cdata['Dna']['size_from'], $cdata['Dna']['size_to'], $cdata['Dna']['size_units']); ?></span>
        </div>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">Number of analysed plants: </span>
            <span class="col-sm-9 text-left"><?php echo $cdata['Dna']['plants_analysed']; ?></span>
        </div>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">Number of analyses: </span>
            <span class="col-sm-9 text-left"><?php echo $cdata['Dna']['number_analyses']; ?></span>
        </div>
        <div class="row">
            <span class="col-sm-3 text-left dlabel2">Note: </span>
            <span class="col-sm-9 text-left"><?php echo $cdata['Dna']['note']; ?></span>
        </div>
    </div>
<?php endif; ?>
<div class="dblock">
    <h3>Locality</h3>
    <div class="row">
        <span class="col-sm-12 dlabel2">World Geographical Scheme for Recording Plant Distributions (<a href="http://www.nhm.ac.uk/hosted_sites/tdwg/TDWG_geo2.pdf">Brummitt 2001</a>)</span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Level 1: </span>
        <span class="col-sm-9 text-left"><?php echo empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL1']['description']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Level 2: </span>
        <span class="col-sm-9 text-left"><?php echo empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL2']['description']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Level 3: </span>
        <span class="col-sm-9 text-left"><?php echo empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL3']['description']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Level 4: </span>
        <span class="col-sm-9 text-left"><?php echo empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL4']['description']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Closest city/town/village/settlement: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['closest_village_town']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Description of the locality: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['description']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Exposition: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['exposition']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Altitude: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['altitude']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Published geographical coordinates: </span>
        <span class="col-sm-2 text-left"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_n']); ?></span>
        <span class="col-sm-7 text-left"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_e']); ?></span>
        <input id="detail-published-lat" type="hidden" value="<?php echo $cdata['Material']['coordinates_n']; ?>" />
        <input id="detail-published-lon" type="hidden" value="<?php echo $cdata['Material']['coordinates_e']; ?>" />
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Estimated geographical coordinates: </span>
        <span class="col-sm-2 text-left"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_georef_lat']); ?></span>
        <span class="col-sm-7 text-left"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_georef_lon']); ?></span>
        <input id="detail-georef-lat" type="hidden" value="<?php echo $cdata['Material']['coordinates_georef_lat']; ?>" />
        <input id="detail-georef-lon" type="hidden" value="<?php echo $cdata['Material']['coordinates_georef_lon']; ?>" />
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Central european mapping unit: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['central_european_mapping_unit']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Geographical district: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['geographical_district']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Phytogeographical district: </span>
        <span class="col-sm-9 text-left"><?php echo ''; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Administrative unit: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['administrative_unit']; ?></span>
    </div>
</div>
<div class="dblock">
    <h3>Material</h3>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Collected by: </span>
        <span class="col-sm-9 text-left"><?php echo ''; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Date: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['collected_date']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Identified by: </span>
        <span class="col-sm-9 text-left"><?php echo ''; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Voucher: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['voucher_specimen_no']; ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Voucher deposited in: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Material']['deposited_in']; ?></span>
    </div>
</div>
<div class="dblock">
    <h3>Reference</h3>
    <div class="row">
        <span class="col-sm-12 text-left"><?php echo $this->Format->literature($cdata['Reference']['Literature']); ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Exact page(s) on which the record is published: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Reference']['Reference']['page'] ?></span>
    </div>
    <div class="row">
        <span class="col-sm-3 text-left dlabel2">Notes: </span>
        <span class="col-sm-9 text-left"><?php echo $cdata['Cdata']['note']; ?></span>
    </div>
</div>

<hr />
<?php
echo $this->Form->create('Data', array('type' => 'post', 'url' => array('controller' => 'data', 'action' => 'view_rtf', 'ext' => 'rtf', 'export')));
echo $this->Form->hidden('exportIds', array('value' => $cdata['Cdata']['id']));
echo $this->Form->hidden('exportType', array('value' => 0));
echo $this->Form->end(array('label' => 'Export', 'id' => 'exportChoice', 'class' => 'btn btn-default', 'div' => false));
?>

<hr />

<div class="dblock comments">
    <h4>
        Annotations<br />
        <small>Annotations will be reviewed and posted after approval</small>
    </h4>
    <?php foreach ($comments as $comm) : ?>
        <div class="comment" style="margin-left: <?php echo $comm['Dcomment']['nested'] * 12; ?>px;">
            <?php echo $this->Form->hidden('commentId', array('value' => $comm['Dcomment']['id'])); ?>
            <div class="head">
                <span class="username"><?php echo $comm['Dcomment']['author'] . ' - ' . $comm['LosComment']['institution']; ?></span>
                <span class="date"><?php echo preg_replace('/\.[0-9]*/', '', $comm['Dcomment']['date_posted']); ?></span>
            </div>
            <div class="body">
                <?php echo $comm['Dcomment']['annotation']; ?>
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
        echo $this->Form->create('Dcomment', array('method' => 'post', 'url' => array('controller' => 'dcomments', 'action' => 'add')));
        echo $this->Form->hidden('parent_id', array('value' => 'null', 'id' => 'ParentId'));
        echo $this->Form->hidden('id_cdata', array('value' => $cdata['Cdata']['id']));
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