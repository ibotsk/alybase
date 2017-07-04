<div id="home" class="row">
    <div class="col-md-7 col-sm-12 text-justify">
        <div>
            <h3>Database</h3>
            <p>
                Database of published chromosome numbers and ploidy-level estimates of the 
                <a href="#tribe-alysseae">tribe Alysseae</a> is presented, together with the revised generic concept and 
                the list of accepted names and their synonyms, to reflect the most recent 
                taxonomic and phylogenetic studies in Alysseae. The tribe encompasses 24 
                genera and 277 species. Chromosome numbers and/or ploidy levels are known 
                for 171 out of 297 recognised taxa. Of these, 95 (55.6%) taxa are diploids, 
                43 (25.1%) are polyploids, and 33 (19.3%) involve both diploids and polyploids. 
                The most common base chromosome number in the tribe is x = 8 and less 
                frequent is x = 7. The highest variation in base chromosome numbers (x = 7, 8, 11, 15) 
                is found in the genus <i>Hormathophylla</i>. The database will be continuously updated.
            </p>
            <ul>
                <li><a href="#technical-solution">Technical solution</a></li>
                <li><a href="#recommended-citation">Recommended citation</a></li>
            </ul>
        </div>
        <div id="technical-solution" class="ahidden">
            <h3>Technical solution</h3>
            <p>
                The production version of entire project is deployed on a server that runs 
                Debian Linux 2.6, "lenny" distribution. The database platform used is PostgreSQL 8.3. 
                The web server installation is Apache 2.2 HTTP with support of PHP. Client 
                side uses CakePHP framework with HTML5, CSS, and JavaScript with the usage 
                of jQuery 1.10 and jQueryUI 1.11 libraries. Visualisation of geographic 
                data is done by Google Maps API v3.
            </p>
        </div>
        <div id="tribe-alysseae" class="ahidden">
            <h3>Tribe Alysseae</h3>
            <p>
                Alysseae DC. is the third largest tribe of the family Brassicaceae (Cruciferae). 
                Its native range is Eurasia and North Africa, and the centre of its greatest 
                diversity is the Mediterranean and Irano-Turanian regions. Members of the 
                Alysseae are annual or perennial herbs or subshrubs morphologically characterized 
                by having stellate trichomes, yellow or white (rarely pink) petals, appendaged 
                filaments, and latiseptate or terete (rarely angustiseptate) few-seeded silicles.
            </p>
        </div>
        <div id="recommended-citation" class="ahidden">
            <h3>Recommended citation</h3>
            <p>
                Španiel, S., Kempa, M., Salmerón-Sánchez, E., Fuertes-Aguilar, J., Francisco Mota, J., Al-Shehbaz, I.A., 
                German, D.A., Olšavská, K., Šingliarová, B., Zozomová-Lihová, J. &amp; Marhold, K. 2015. 
                AlyBase – database of names, chromosome numbers, and ploidy levels of Alysseae (Brassicaceae), 
                with a new generic concept of the tribe. Plant Systematics and Evolution 301: 2463–2491.
            </p>
            <p>
                <?php echo $this->Html->link('Download pdf for personal use only', '/files/2015_PSE_Spaniel_et_al_AlyBase_with_online_app.pdf'); ?>
            </p>
        </div>
    </div>
    
    <div class="col-md-5 hidden-sm hidden-xs text-center">
        <div id="home-slideshow" class="cycle-slideshow" data-cycle-caption="#caption" data-cycle-caption-template="<i>{{cycleTitle}}</i> <span class=small>&copy; Stanislav Španiel</span>">
            <?php
            echo $this->Html->image('gallery/atlanticum_s.jpg', array('alt' => 'Alyssum atlanticum', 'data-cycle-title' => 'Alyssum atlanticum'));
            echo $this->Html->image('gallery/cacuminum_s.jpg', array('alt' => 'Alyssum cacuminum', 'data-cycle-title' => 'Alyssum cacuminum'));
            echo $this->Html->image('gallery/fastigiatum_s.jpg', array('alt' => 'Alyssum fastigiatum', 'data-cycle-title' => 'Alyssum fastigiatum'));
            echo $this->Html->image('gallery/flexicaule_s.jpg', array('alt' => 'Alyssum flexicaule', 'data-cycle-title' => 'Alyssum flexicaule'));
            echo $this->Html->image('gallery/gallaecicum_s.jpg', array('alt' => 'Alyssum gallaecicum', 'data-cycle-title' => 'Alyssum gallaecicum'));
            echo $this->Html->image('gallery/loiseleurii_s.jpg', array('alt' => 'Alyssum loiseleurii', 'data-cycle-title' => 'Alyssum loiseleurii'));
            echo $this->Html->image('gallery/montanum_s.jpg', array('alt' => 'Alyssum montanum', 'data-cycle-title' => 'Alyssum montanum'));
            echo $this->Html->image('gallery/orophilum_s.jpg', array('alt' => 'Alyssum orophilum', 'data-cycle-title' => 'Alyssum orophilum'));
            ?>
        </div>
        <div id="caption"></div>
    </div>
</div>