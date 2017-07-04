<style type="text/css">
    div { border-bottom: 1px solid #cecece; padding: 20px 0; }
    p { margin: 0; padding: 0; }
    ul { list-style: none; margin: 0 auto;}
    h4 { margin: 0; padding: 0; }
</style>

<?php
foreach ($data as $cdata) :
    ?>
    <div class="detail taxonomy">
        <?php
        echo $this->Format->detailValue('Name after last revision: ', $this->Html->link($this->Format->los($cdata['ListOfSpeciesNewest']['ListOfSpecies'], true), array('controller' => 'checklists', 'action' => 'detail', $cdata['ListOfSpeciesNewest']['ListOfSpecies']['id']), array('escape' => false)));
        echo $this->Format->detailValue('Name as originally published (standardised version): ', $this->Html->link($this->Format->los($cdata['Reference']['ListOfSpecies'], true), array('controller' => 'checklists', 'action' => 'detail', $cdata['Reference']['ListOfSpecies']['id']), array('escape' => false)));
        echo $this->Format->detailValue('Name exactly as originally published: ', $cdata['Reference']['Reference']['name_as_published']);
        ?>
    </div>
    <div class="detail caryology">
        <h3>Chromosome number</h3>
        <?php
        echo $this->Format->detailValue('Chromosome count: ', $this->Format->chromosomes($cdata['Cdata']['n'], $cdata['Cdata']['dn']));
        echo $this->Format->detailValue('Ploidy as published in original source: ', $cdata['Cdata']['ploidy_level']);
        echo $this->Format->detailValue('Ploidy after last revision: ', $cdata['Cdata']['ploidy_level_revised']);
        echo $this->Format->detailValue('Counted by: ', $cdata['CountedBy']['pers_name']);
        echo $this->Format->detailValue('Date: ', $cdata['Cdata']['counted_date']);
        echo $this->Format->detailValue('Number of analysed plants: ', $cdata['Cdata']['number_of_analysed_plants']);
        echo $this->Format->detailValue('Slide number: ', $cdata['Cdata']['slide_no']);
        echo $this->Format->detailValue('Deposited in: ', $cdata['Cdata']['deposited_in']);
        echo $this->Format->detailValue('Karyotype: ', $cdata['Cdata']['karyotype']);
        ?>
        <p>
            <span class="label">Photo:</span><span class="value"><?php echo $this->Html->image($cdata['Cdata']['photo'] ? $okImg : $nokImg, array('alt' => ($cdata['Cdata']['photo'] ? "photo-yes" : "photo-no"))); ?></span>
            <span class="label">Idiogram:</span><span class="value"><?php echo $this->Html->image($cdata['Cdata']['idiogram'] ? $okImg : $nokImg, array('alt' => ($cdata['Cdata']['idiogram'] ? "photo-yes" : "photo-no"))); ?></span>
            <span class="label">Drawing:</span><span class="value"><?php echo $this->Html->image($cdata['Cdata']['drawing'] ? $okImg : $nokImg, array('alt' => ($cdata['Cdata']['drawing'] ? "photo-yes" : "photo-no"))); ?></span>
        </p>
    </div>
    <?php if (!empty($cdata['Dna']['method'])) : ?>
        <div class="detail dna">
            <h3>Ploidy level estimated according to the DNA content</h3>
            <?php
            echo $this->Format->detailValue('Method: ', strtoupper($cdata['Dna']['method']));
            echo $this->Format->detailValue('DNA ploidy level as published in the original source: ', $cdata['Dna']['ploidy']);
            echo $this->Format->detailValue('DNA ploidy level after last revision: ', $cdata['Dna']['ploidy_revised']);
            echo $this->Format->detailValue('Chromosome count: ', $cdata['Dna']['ch_count']);
            echo $this->Format->detailValue('Genome size: ', $this->Format->genomeSize($cdata['Dna']['size_c'], $cdata['Dna']['size_from'], $cdata['Dna']['size_to'], $cdata['Dna']['size_units']));
            echo $this->Format->detailValue('Number of analysed plants: ', $cdata['Dna']['plants_analysed']);
            echo $this->Format->detailValue('Number of analyses: ', $cdata['Dna']['number_analyses']);
            echo $this->Format->detailValue('Note: ', $cdata['Dna']['note']);
            ?>
        </div>
    <?php endif; ?>
    <div class="detail locality">
        <h3>Locality</h3>
        <p>World Geographical Scheme for Recording Plant Distributions (<a href="http://www.nhm.ac.uk/hosted_sites/tdwg/TDWG_geo2.pdf">Brummitt 2001</a>)</p>
        <?php
        echo $this->Format->detailValue('Level 1: ', empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL1']['description']);
        echo $this->Format->detailValue('Level 2: ', empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL2']['description']);
        echo $this->Format->detailValue('Level 3: ', empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL3']['description']);
        echo $this->Format->detailValue('Level 4: ', empty($cdata['Material']['Worlds']) ? '' : $cdata['Material']['Worlds'][0]['WorldL4']['description']);
        echo $this->Format->detailValue('Closest city/town/willage/settlement: ', $cdata['Material']['closest_village_town']);
        echo $this->Format->detailValue('Description of the locality: ', $cdata['Material']['description']);
        echo $this->Format->detailValue('Exposition: ', $cdata['Material']['exposition']);
        echo $this->Format->detailValue('Altitude: ', $cdata['Material']['altitude']);
        ?>
        <p>
            <span class="label">Published geographical coordinates: </span>
            <span class="value"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_n']); ?></span>
            <span class="value"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_e']); ?></span>
            <input id="detail-published-lat" type="hidden" value="<?php echo $cdata['Material']['coordinates_n']; ?>" />
            <input id="detail-published-lon" type="hidden" value="<?php echo $cdata['Material']['coordinates_e']; ?>" />
        </p>
        <p>
            <span class="label">Estimated geographical coordinates: </span>
            <span class="value"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_georef_lat']); ?></span>
            <span class="value"><?php echo $this->Format->coordinates($cdata['Material']['coordinates_georef_lon']); ?></span>
            <input id="detail-georef-lat" type="hidden" value="<?php echo $cdata['Material']['coordinates_georef_lat']; ?>" />
            <input id="detail-georef-lon" type="hidden" value="<?php echo $cdata['Material']['coordinates_georef_lon']; ?>" />
        </p>
        <br />
        <?php
        echo $this->Format->detailValue('Central european mapping unit: ', $cdata['Material']['central_european_mapping_unit']);
        echo $this->Format->detailValue('Geographical district: ', $cdata['Material']['geographical_district']);
        echo $this->Format->detailValue('Phytogeographical district: ', '');
        echo $this->Format->detailValue('Administrative unit: ', $cdata['Material']['administrative_unit']);
        ?>
    </div>
    <div class="detail material">
        <h3>Material</h3>
        <p>
            <?php
            echo $this->Format->detailValue('Collected by: ', '', false, '');
            echo $this->Format->detailValue('Date: ', $cdata['Material']['collected_date'], false, '');
            ?>
        </p>
        <?php echo $this->Format->detailValue('Identified by: ', '', false); ?>
        <p>
            <?php
            echo $this->Format->detailValue('Voucher: ', $cdata['Material']['voucher_specimen_no'], false, '');
            echo $this->Format->detailValue('Voucher deposited in: ', $cdata['Material']['deposited_in'], false, '');
            ?>
        </p>
    </div>
    <div class="detail literature">
        <h3>Reference</h3>
        <p><span class="value"><?php echo $this->Format->literature($cdata['Reference']['Literature']); ?></span></p>
        <p><span class="label">Exact page(s) on which the record is published: </span><span class="value"><?php echo $cdata['Reference']['Reference']['page'] ?></span></p>
        <p><span class="label">Notes: </span><span class="value"><?php echo $cdata['Reference']['Reference']['note']; ?></span></p>
    </div>
    <?php
endforeach;
