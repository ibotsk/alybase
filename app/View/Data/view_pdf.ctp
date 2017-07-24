<?php
//new dBug($data);
$okImg = 'ok-16.png';
$nokImg = 'nok-16.png';
foreach ($data as $d) :
    ?>
    <div>
        <?php
        echo $this->Format->detailValue('Newest name:', $this->Format->los($d['ListOfSpeciesNewest']['ListOfSpecies'], array('italic' => true)), array('controller' => 'checklists', 'action' => 'detail', $d['ListOfSpeciesNewest']['ListOfSpecies']['id']));
        echo $this->Format->detailValue('Published name:', $this->Format->los($d['Reference']['ListOfSpecies'], array('italic' => true)));
        echo $this->Format->detailValue('Published name (with errors):', $d['Reference']['Reference']['name_as_published']);
        ?>
        <h3>Caryology</h3>
        <?php
        echo $this->Format->detailValue('Chromosome count:', $this->Format->chromosomes($d['Cdata']['n'], $d['Cdata']['dn']));
        echo $this->Format->detailValue('Ploidy:', $d['Cdata']['ploidy_level']);
        echo $this->Format->detailValue('Counted by:', $d['CountedBy']['pers_name']);
        echo $this->Format->detailValue('Date:', $d['Cdata']['counted_date']);
        echo $this->Format->detailValue('Number of plants analysed:', $d['Cdata']['number_of_analysed_plants']);
        echo $this->Format->detailValue('Slide number:', $d['Cdata']['slide_no']);
        echo $this->Format->detailValue('Depisted in:', $d['Cdata']['deposited_in']);
        echo $this->Format->detailValue('Karyotype:', $d['Cdata']['karyotype']);
        ?>
        <p>
            <span class="label">Photo:</span><span class="value"><?php echo $this->Html->image($d['Cdata']['photo'] ? $okImg : $nokImg, array('alt' => ($d['Cdata']['photo'] ? "photo-yes" : "photo-no"))); ?></span>
            <span class="label">Idiogram:</span><span class="value"><?php echo $this->Html->image($d['Cdata']['idiogram'] ? $okImg : $nokImg, array('alt' => ($d['Cdata']['idiogram'] ? "photo-yes" : "photo-no"))); ?></span>
            <span class="label">Drawing:</span><span class="value"><?php echo $this->Html->image($d['Cdata']['drawing'] ? $okImg : $nokImg, array('alt' => ($d['Cdata']['drawing'] ? "photo-yes" : "photo-no"))); ?></span>
        </p>
    </div>
    <?php if (!empty($d['Dna'])) : ?>
        <h3>DNA</h3>
        <?php
        echo $this->Format->detailValue('Method:', $d['Dna']['method']);
        echo $this->Format->detailValue('DNA ploidy level:', $d['Dna']['ploidy']);
        echo $this->Format->detailValue('Chromosome count:', $d['Dna']['ch_count']);
        echo $this->Format->detailValue('Genome size:', $this->Format->genomeSize($d['Dna']['size_c'], $d['Dna']['size_from'], $d['Dna']['size_to'], $d['Dna']['size_units']));
        echo $this->Format->detailValue('Plants analysed:', $d['Dna']['plants_analysed']);
        echo $this->Format->detailValue('Number of analyses:', $d['Dna']['number_analyses']);
        echo $this->Format->detailValue('Note:', $d['Dna']['note']);
        ?>
    <?php endif; ?>
    <h3>Locality</h3>
    <?php
    echo $this->Format->detailValue('World 1:', empty($d['Material']['Worlds']) ? '' : $d['Material']['Worlds'][0]['WorldL1']['description']);
    echo $this->Format->detailValue('World 2:', empty($d['Material']['Worlds']) ? '' : $d['Material']['Worlds'][0]['WorldL2']['description']);
    echo $this->Format->detailValue('World 3:', empty($d['Material']['Worlds']) ? '' : $d['Material']['Worlds'][0]['WorldL3']['description']);
    echo $this->Format->detailValue('World 4:', empty($d['Material']['Worlds']) ? '' : $d['Material']['Worlds'][0]['WorldL4']['description']);
    echo $this->Format->detailValue('Closest village:', $d['Material']['closest_village_town']);
    echo $this->Format->detailValue('Description:', $d['Material']['description']);
    echo $this->Format->detailValue('Exposition:', $d['Material']['exposition']);
    echo $this->Format->detailValue('Altitude:', $d['Material']['altitude']);
    ?>
    <p>
        <span class="label">Published coordinates:</span>
        <span class="value"><?php echo $this->Format->coordinates($d['Material']['coordinates_n']); ?></span>
        <span class="value"><?php echo $this->Format->coordinates($d['Material']['coordinates_e']); ?></span>
        <input id="detail-published-lat" type="hidden" value="<?php echo $d['Material']['coordinates_n']; ?>" />
        <input id="detail-published-lon" type="hidden" value="<?php echo $d['Material']['coordinates_e']; ?>" />
    </p>
    <p>
        <span class="label">Georeferenced coordinates:</span>
        <span class="value"><?php echo $this->Format->coordinates($d['Material']['coordinates_georef_lat']); ?></span>
        <span class="value"><?php echo $this->Format->coordinates($d['Material']['coordinates_georef_lon']); ?></span>
        <input id="detail-georef-lat" type="hidden" value="<?php echo $d['Material']['coordinates_georef_lat']; ?>" />
        <input id="detail-georef-lon" type="hidden" value="<?php echo $d['Material']['coordinates_georef_lon']; ?>" />
    </p>
    <br />
    <?php
    echo $this->Format->detailValue('Central european mapping unit:', $d['Material']['central_european_mapping_unit']);
    echo $this->Format->detailValue('Geographical district:', $d['Material']['geographical_district']);
    echo $this->Format->detailValue('Phytogeographical district:', '');
    echo $this->Format->detailValue('Administrative unit:', $d['Material']['administrative_unit']);
    ?>
    <h3>Material</h3>
    <p>
        <span class="label">Collected by:</span><span class="value"><?php //echo $collectedBy          ?></span>
        <span class="label">Date:</span><span class="value"><?php echo $d['Material']['collected_date']; ?></span>
    </p>
    <p><span class="label">Identified by:</span><span class="value"><?php //echo $identif;          ?></span></p>
    <p>
        <span class="label">Voucher:</span><span class="value"><?php echo $d['Material']['voucher_specimen_no']; ?></span>
        <span class="label">Stored in:</span><span class="value"><?php echo $d['Material']['deposited_in']; ?></span>
    </p>
    <h3>Literature</h3>
    <p><span class="value"><?php echo $this->Format->literature($d['Reference']['Literature']); ?></span></p>
    <p><span class="label">Pages:</span><span class="value"><?php echo $d['Reference']['Reference']['page'] ?></span></p>
    <p><span class="label">Notes:</span><span class="value"><?php echo $d['Reference']['Reference']['note']; ?></span></p>
    <?php
endforeach;
