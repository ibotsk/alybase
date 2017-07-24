<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
?>
<!DOCTYPE html>
<html>
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            AlyBase
            <?php //echo $this->fetch('title'); ?>
        </title>
        <?php
        echo $this->Html->meta('icon');
        echo $this->Html->meta(array('name' => 'viewport', 'content' => 'width=device-width, initial-scale=1'));

        echo $this->Html->css('jquery-ui.min');
        echo $this->Html->css('bootstrap.min.css');
        echo $this->Html->css('bootstrap-theme.min.css');
        echo $this->Html->css('style');
        echo $this->Html->css('annotations');

        echo $this->Html->script('jquery-1.10.2');
        echo $this->Html->script('jquery-ui.min');
        echo $this->Html->script('jquery.cycle2.min');
        echo $this->Html->script('bootstrap.min');
        echo $this->Html->script('https://maps.googleapis.com/maps/api/js?key=AIzaSyAPfXxTTVEBVoV7WmUbET8qsQxr16-v6lE');
        echo $this->Html->script('oms.min');
        echo $this->Html->script('markerclusterer_compiled');
        echo $this->Html->script('jExpand');
        echo $this->Html->script('maps');
        echo $this->Html->script('main');
        echo $this->Html->script('comments');
        //echo $this->Html->script('export');

        echo $this->fetch('meta');
        echo $this->fetch('css');
        echo $this->fetch('script');
        ?>
    </head>
    <body>
        <div id="header" class="container hidden-sm hidden-xs"></div>
        <nav id="menu" class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menuNavbar">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span> 
                    </button>
                    <?php
                    echo $this->Html->link(
                            $this->Html->image('AlyBase_4.png', array('alt' => 'AlyBase', 'id' => 'navbarLogo')), 'pages/home', array('escapeTitle' => false, 'id' => 'navbarHeaderLink', 'class' => 'navbar-brand', 'title' => 'Home')
                    );
                    ?>
                    <!--<a id="navbarHeaderLink" class="navbar-brand" href="#">
                        <img id="navbarLogo" src="img/AlyBase_4.png" alt="Alybase" />
                    </a>-->
                </div>
                <div class="collapse navbar-collapse" id="menuNavbar">
                    <ul class="nav navbar-nav">
                        <li><?php echo $this->Html->link('Home', array('controller' => 'pages', 'action' => 'display', 'home')); ?></li>
                        <li><?php echo $this->Html->link('Names', array('controller' => 'checklists', 'action' => 'index')); ?></li>
                        <li><?php echo $this->Html->link('Chromosome and ploidy level data', array('controller' => 'data', 'action' => 'index')); ?></li>
                        <li><?php echo $this->Html->link('Key to genera', 'http://flora.huh.harvard.edu/Brassicaceae/navikey/Brassicaceae_Genera_World/Brassicaceae_Genera_World_NaviKey.html', array('target' => '_blank')); ?></li>
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Descriptions of genera
                                <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><?php echo $this->Html->link('Acuston', '/pages/acuston'); ?></li>
                                <li><?php echo $this->Html->link('Alyssoides', '/pages/alyssoides'); ?></li>
                                <li><?php echo $this->Html->link('Alyssum', '/pages/alyssum'); ?></li>
                                <li><?php echo $this->Html->link('Aurinia', '/pages/aurinia'); ?></li>
                                <li><?php echo $this->Html->link('Berteroa', '/pages/berteroa'); ?></li>
                                <li><?php echo $this->Html->link('Bornmuellera', '/pages/bornmuellera'); ?></li>
                                <li><?php echo $this->Html->link('Brachypus', '/pages/brachypus'); ?></li>
                                <li><?php echo $this->Html->link('Clastopus', '/pages/clastopus'); ?></li>
                                <li><?php echo $this->Html->link('Clypeola', '/pages/clypeola'); ?></li>
                                <li><?php echo $this->Html->link('Cuprella', '/pages/cuprella'); ?></li>
                                <li><?php echo $this->Html->link('Degenia', '/pages/degenia'); ?></li>
                                <li><?php echo $this->Html->link('Fibigia', '/pages/fibigia'); ?></li>
                                <li><?php echo $this->Html->link('Galitzkya', '/pages/galitzkya'); ?></li>
                                <li><?php echo $this->Html->link('Hormathophylla', '/pages/hormathophylla'); ?></li>
                                <li><?php echo $this->Html->link('Irania', '/pages/irania'); ?></li>
                                <li><?php echo $this->Html->link('Lepidotrichum', '/pages/lepidotrichum'); ?></li>
                                <li><?php echo $this->Html->link('Lutzia', '/pages/lutzia'); ?></li>
                                <li><?php echo $this->Html->link('Meniocus', '/pages/meniocus'); ?></li>
                                <li><?php echo $this->Html->link('Odontarrhena', '/pages/odontarrhena'); ?></li>
                                <li><?php echo $this->Html->link('Phyllolepidum', '/pages/phyllolepidum'); ?></li>
                                <li><?php echo $this->Html->link('Physoptychis', '/pages/physoptychis'); ?></li>
                                <li><?php echo $this->Html->link('Pterygostemon', '/pages/pterygostemon'); ?></li>
                                <li><?php echo $this->Html->link('Resetnikia', '/pages/resetnikia'); ?></li>
                                <li><?php echo $this->Html->link('Takhtajaniella', '/pages/takhtajaniella'); ?></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div id="filter" class="container">
            <?php
            if ($this->name == 'Checklists') {
                echo $this->element('filter-checklist');
            } else if ($this->name == 'Literatures') {
                echo $this->element('filter-literature');
            } else if ($this->name == 'Data') {
                echo $this->element('filter-data');
            }
            ?>
        </div>
        <div id="results" class="container">
<?php echo $this->fetch('content'); ?>
        </div>
        <div id="footer" class="container">
            <div class="row">
                <div class="col-xs-11">
                    Institute of Botany, SAS Bratislava, Slovakia
                </div>
                <div id="logos" class="col-xs-1">
<?php echo $this->Html->image('logosav.png', array('alt' => 'SAV', 'height' => '30', 'url' => 'http://www.sav.sk/')); ?>
                    <?php echo $this->Html->image('BU_logo.png', array('alt' => 'BU SAV', 'height' => '30', 'url' => 'http://ibot.sav.sk/')); ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>