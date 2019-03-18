<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="#" class="active"><?php echo get_phrase('orders'); ?></a> </li>
</ol>
<h2><i class="fa fa-arrow-circle-o-right"></i> <?php echo $page_title; ?></h2>
<br />

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary" data-collapsed="0">
            <div class="panel-body">
                <div class="row" style="margin-left: -15px;">
                    <?php /*<div class="col-md-3">
                        <a href = "<?php echo site_url('admin/user_form/add_user_form'); ?>" class="btn btn-block btn-info" type="button"><i class="fa fa-plus"></i>&nbsp;&nbsp;<?php echo get_phrase('add_invoice'); ?></a>
                    </div> */ ?>
                </div>
                <table class="table table-bordered datatable" id="table-1">
                    <thead>
                    <tr>
                        <th><?php echo get_phrase('user_name'); ?></th>
                        <th><?php echo get_phrase('invoice_number'); ?></th>
                        <th><?php echo get_phrase('course_name'); ?></th>
                        <th><?php echo get_phrase('status'); ?></th>
                        <th><?php echo get_phrase('date_order'); ?></th>
                        <th><?php echo get_phrase('actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($orders->result_array() as $order): ?>
                        <tr class="gradeU">
                            <td>
                                <a href="<?php echo site_url('/admin/user_form/edit_user_form/'. $order['user_id']); ?>" target="_blank">
                                    <?php echo $order['first_name']. ' '. $order['last_name']; ?>
                                </a>
                            </td>
                            <td><?php echo $this->invoice_model->getInvoiceFormatedNumber(['id' => $order['invoice_id']]); ?></td>
                            <td><?php echo $order['course_name']; ?></td>
                            <td style="text-align: center;"><?php echo $this->order_model->getStatus($order['status']); ?></td>
                            <td><?php echo date('D, d-M-Y', $order['date_added']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default" data-toggle="dropdown"> <i class = "fa fa-ellipsis-v"></i> </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="<?php echo site_url('admin/orders/detail/'.$order['id']) ?>">
                                                <?php echo get_phrase('detail');?>
                                            </a>
                                        </li>
                                        <?php if ($order['status'] == 0): ?>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="<?php echo site_url('admin/orders/activate/'.$order['id']) ?>"
                                               onclick="return confirm_action(this);" msg="<?php echo get_phrase('are_you_sure_to_activate?'); ?>">
                                                <?php echo get_phrase('activate');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo site_url('admin/orders/delete/'.$order['id']); ?>');">
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
                                        <?php endif; ?>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    function confirm_action(dt) {
        var msg = $(dt).attr('msg');
        if (confirm(msg)) {
            var href = $(dt).attr('href');
            window.location.href = href;
        }

        return false;
    }
</script>
