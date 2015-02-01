<?php
//Include CSS
echo $this->Html->css('OpiaProxy.opia_proxy.css', array('inline' => true));
?>

<div class="row">
    <div class="col-lg-12">
        <h2>Status</h2>

        <div class="row">
            <div class="col-lg-3">
                <span class="">
                    Network
                    ::
                     <span
                         class="label label-status <?php echo $api_status['network'] ? 'label-success fui-triangle-up ' : 'label-danger fui-triangle-down ' ?>">
                          <b> <?php echo $api_status['network'] ? 'UP!' : 'DOWN!'; ?></b>
                    </span>
                </span>
            </div>

            <div class="col-lg-3">
                  <span class="">
                    Location
                    ::
                     <span
                         class="label label-status <?php echo $api_status['location'] ? 'label-success fui-triangle-up ' : 'label-danger fui-triangle-down ' ?>">
                          <b> <?php echo $api_status['location'] ? 'UP!' : 'DOWN!'; ?></b>
                    </span>
                </span>
            </div>

            <div class="col-lg-3">
               <span class="">
                    Travel
                    ::
               <span
                   class="label label-status <?php echo $api_status['travel'] ? 'label-success fui-triangle-up ' : 'label-danger fui-triangle-down ' ?>">
                          <b> <?php echo $api_status['travel'] ? 'UP!' : 'DOWN!'; ?></b>
                    </span>
                </span>
            </div>

            <div class="col-lg-3">
                   <span class="">
                    Version
                    ::
                    <span
                        class="label label-status <?php echo $api_status['version'] ? 'label-success fui-triangle-up ' : 'label-danger fui-triangle-down ' ?>">
                          <b><?php echo $api_status['version'] ? 'UP!' : 'DOWN!'; ?></b>
                    </span>
                </span>
            </div>
        </div>

    </div>

</div>


<div class="row">
    <div class="col-lg-12">
        <h2>Cache</h2>

        <div class="row">
            <div class="col-lg-6">
                <p>Cache Type: <b><?php echo $cache_status['cache_type'] ?> cache</b></p>
            </div>

            <div class="col-lg-6">
                <button type="button" class="btn btn-default btn-danger ">
                    <?php
                    echo $this->Html->link(
                        'Purge Cache',
                        array(
                            'controller' => 'opia_proxy',
                            'action' => 'purge',
                            'full_base' => true,
                        ),
                        array('class' => 'purge_link')
                    );
                    ?>
                </button>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 cache-items-list">
                Cache Items:
                <ul class="list-group">
                    <?php

                    foreach ($cache_status['cache_items'] as $i => $list_item) {
                        $expired = $list_item['expired'];
                        $list_class = $expired ? 'list-group-item-danger' : 'list-group-item-success';
                        $label_class = $expired ? 'label-danger' : 'label-success'
                        ?>

                        <li class="list-group-item <?php echo $list_class ?> ">
                            <span>Item: <?php echo $list_item['item_name']; ?></span>
                            <br>
                            <span>TTL: <?php echo $list_item['ttl']; ?></span>
                            <br>
                            <span
                                class="label  <?php echo $label_class ?>">Expired: <?php echo $list_item['expired'] ? 'Yes' : 'No'; ?></span>
                        </li>

                    <?php
                    }
                    ?>

                </ul>
            </div>

            <div class="col-lg-6 cache-items-content">
                Contents:
                <ul class="list-group">
                    <?php

                    foreach ($cache_status['cache_items'] as $i => $list_item) {
                        $expired = $list_item['expired'];
                        $list_class = $expired ? 'list-group-item-danger' : 'list-group-item-success';
                        ?>

                        <li class="list-group-item <?php echo $list_class ?> cache-items-list-content">
                            <span>Item: <?php echo $list_item['contents']; ?></span>
                        </li>
                    <?php
                    }
                    ?>
                </ul>
            </div>

        </div>
    </div>
</div>


<div class="row">
    <div class="col-lg-12">
        <h2>Log View</h2>
    </div>
    <div class="col-lg-12 log-view-list">
        <ul class="list-group">
            <?php
            foreach ($log_tail as $lti => $ltd) {
                $li_list_class = 'list-group-item-info';
                $list_class = 'text-info';


                if (stripos($ltd, 'info') !== false) {
                    $list_class = 'text-info';
                    $li_list_class = 'list-group-item-success';
                }
                if (stripos($ltd, 'warning') !== false) {
                    $list_class = 'text-warning';
                    $li_list_class = 'list-group-item-warning';
                }
                ?>
                <li class="list-group-item <?php echo $li_list_class ?> ">
                    <span class="<?php echo $list_class ?>"><?php echo $ltd; ?></span>
                </li>
            <?php
            }
            ?>
        </ul>
    </div>
</div>