<div id="tabs">
    <ul>
        <li><a href="#tabs-1">Records</a></li>
        <li><a href="#tabs-2">Map</a></li>
    </ul>
    <div id="tabs-1">
        <table id="chromResults">
            <col width="15%"/>
            <col />
            <col width="60%" />
            <tr>
                <th>Chromosome count</th>
                <th>Counted by</th>
                <th>Published in</th>
            </tr>
            <?php foreach ($cdata as $data) : ?>
                <tr>
                    <td id="<?php echo $data['Cdata']['id']; ?>"><?php echo $this->Html->link($this->Format->chromosomes($data['Cdata']['n'], $data['Cdata']['dn']), array('controller' => 'data', 'action' => 'detail', $data['Cdata']['id'])); ?></td>
                    <td><?php echo $data['CountedBy']['pers_name']; ?></td>
                    <td><?php echo $this->Format->literature($data['Literature']); ?></td>
                </tr>
                <?php
            endforeach;
            ?>
        </table>
    </div>
    <div id="tabs-2">
        <input id="map-showing-name" type="hidden" value="<?php echo $name; ?>" />
        <div id="chromMap"></div>
        <div id="chromMapLegend"></div>
    </div>
</div>