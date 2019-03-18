<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="#" class="active"><?php echo get_phrase('invoice_detail'); ?></a> </li>
</ol>
<h2><i class="fa fa-arrow-circle-o-right"></i> <?php echo $page_title; ?></h2>
<br />

<div class="row">
    <div class="col-md-12">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#detail"><?php echo get_phrase('detail'); ?></a></li>
            <li><a data-toggle="tab" href="#update"><?php echo get_phrase('update'); ?></a></li>
            <li><a data-toggle="tab" href="#items"><?php echo get_phrase('items'); ?></a></li>
        </ul>

        <div class="tab-content">
            <div id="detail" class="tab-pane fade in active">
                <div class="col-md-8">
                    <table class="table table-hover">
                        <tbody>
                        <tr>
                            <td>ID</td>
                            <td>#<?php echo $model->id; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('invoice_number'); ?></td>
                            <td><?php echo $this->invoice_model->getInvoiceFormatedNumber(['id' => $model->id]); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('user'); ?></td>
                            <td><a href="<?php echo site_url('admin/user_form/edit_user_form/'. $model->user_id); ?>"><?php echo $model->first_name. ' '. $model->last_name; ?></a></td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('total'); ?></td>
                            <td><?php echo currency($model->cash); ?></td>
                        </tr>
                        <tr>
                            <td><?php echo get_phrase('status'); ?></td>
                            <td><?php echo $this->invoice_model->getStatus($model->status); ?></td>
                        </tr>
                        </tbody>
                    </table>
                    <?php if ($model->status == 0): ?>
                        <a href="<?php echo site_url('admin/invoices/mark_as_paid/'. $model->id); ?>" class="btn btn-info"><?php echo get_phrase('mark_as_paid'); ?></a>
                        <a href="<?php echo site_url('admin/invoices/delete/'. $model->id); ?>" class="btn btn-danger"><?php echo get_phrase('delete'); ?></a>
                    <?php elseif ($model->status == 1): ?>
                        <a href="<?php echo site_url('admin/orders/refund/'. $model->id); ?>" class="btn btn-info"><?php echo get_phrase('complete'); ?></a>
                    <?php endif; ?>
                </div>
            </div>
            <div id="update" class="tab-pane fade">
                <div class="col-md-8">
                    <form action="<?php echo site_url('admin/invoices/edit/'. $model->id); ?>"
                          method="post" class="form-horizontal">
                        <div class="form-group">
                            <label class="control-label col-sm-2">
                                <?php echo get_phrase('user'); ?>:
                            </label>
                            <div class="col-sm-8">
                                <?php $users = $this->user_model->get_all_user(); ?>
                                <select name="Invoice[user_id]" class="form-control">
                                    <?php foreach ($users->result() as $user): ?>
                                        <option value="<?php echo $user->id; ?>" <?php if ($user->id == $model->user_id):?>selected="selected"<?php endif; ?>>
                                            <?php echo $user->first_name.' '.$user->last_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">
                                <?php echo get_phrase('status'); ?>:
                            </label>
                            <div class="col-sm-5">
                                <?php $statuses = $this->invoice_model->getStatus(); ?>
                                <select name="Invoice[status]" class="form-control">
                                    <?php foreach ($statuses as $status_id => $status_name): ?>
                                        <option value="<?php echo $status_id; ?>" <?php if ($status_id == $model->status):?>selected="selected"<?php endif; ?>>
                                            <?php echo $status_name; ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-2">
                                <?php echo get_phrase('notes'); ?>:
                            </label>
                            <div class="col-sm-5">
                                <textarea name="Invoice[notes]" class="form-control"><?php echo $model->notes; ?></textarea>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-10">
                                <button type="submit" class="btn btn-info"><?php echo get_phrase('update'); ?></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div id="invoice" class="tab-pane fade">
                <div class="col-md-8">

                </div>
            </div>
        </div>
    </div>
</div>