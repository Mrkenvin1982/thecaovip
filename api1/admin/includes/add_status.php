<td style="text-align: center;">
    <?php 
        if($obj->status == 1) {
            ?>
                <img href="0" id="status_<?= $obj->getId() ?>" data-field='status' onclick="changeStatus(<?= $obj->getId() ?>,this)" src="<?= $base_url ?>/admin/images/tick.png"/>
            <?php
        }
        else {
            ?>
                <img href="1" id="status_<?= $obj->getId() ?>" data-field='status' onclick="changeStatus(<?= $obj->getId() ?>,this)" src="<?= $base_url ?>/admin/images/publish_x.png"/>
            <?php
        }
    ?>

</td>