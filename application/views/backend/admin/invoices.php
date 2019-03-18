<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="#" class="active"><?php echo get_phrase('invoices'); ?></a> </li>
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
                        <th><?php echo get_phrase('total'); ?></th>
                        <th><?php echo get_phrase('date_added'); ?></th>
                        <th><?php echo get_phrase('actions'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($invoices->result_array() as $invoice): ?>
                        <tr class="gradeU">
                            <td>
                                <a href="<?php echo site_url('/admin/user_form/edit_user_form/'. $invoice['user_id']); ?>" target="_blank">
                                <?php echo $invoice['first_name']. ' '. $invoice['last_name']; ?>
                                </a>
                            </td>
                            <td><?php echo $this->invoice_model->getInvoiceFormatedNumber(['id' => $invoice['id']]); ?></td>
                            <td style="text-align: right;"><?php echo currency($invoice['cash']); ?></td>
                            <td><?php echo date('D, d-M-Y', $invoice['date_added']); ?></td>
                            <td>
                                <div class="btn-group">
                                    <button type="button" class="btn btn-default" data-toggle="dropdown"> <i class = "fa fa-ellipsis-v"></i> </button>
                                    <ul class="dropdown-menu pull-right">
                                        <li>
                                            <a href="<?php echo site_url('admin/invoices/detail/'.$invoice['id']) ?>">
                                                <?php echo get_phrase('detail');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="<?php echo site_url('admin/invoices/mark_as_paid/'.$invoice['id']) ?>">
                                                <?php echo get_phrase('mark_as_paid');?>
                                            </a>
                                        </li>
                                        <li class="divider"></li>
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo site_url('admin/invoices/delete/'.$invoice['id']); ?>');">
                                                <?php echo get_phrase('delete');?>
                                            </a>
                                        </li>
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


<div class="row">
    <div class="col-md-12">
        <div class="grid simple">
            <div class="grid-body no-border">


                <div class="row">
                    <br>

                </div>
            </div>
        </div>
    </div>
</div>
