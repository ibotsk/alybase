<h4>All references for chromosome number/ploidy level data</h4>

<table class="table table-striped">
    <?php
    foreach ($literatures as $lit):
        ?>
        <tr>
            <td><?php echo $this->Format->literature($lit['Literature'], array('link' => array('controller' => 'data', 'action' => 'literature', $lit['Literature']['id']))); ?></td>
        </tr>
        <?php
    endforeach;
    ?>
</table>

<!--
<ul id="lit-results">
<?php
foreach ($literatures as $lit):
    ?>
            <li>
    <?php echo $this->Format->literature($lit['Literature'], array('link' => array('controller' => 'data', 'action' => 'literature', $lit['Literature']['id']))); ?>
            </li>
    <?php
endforeach;
?>
</ul>-->