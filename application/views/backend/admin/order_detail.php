<ol class="breadcrumb bc-3">
    <li>
        <a href="<?php echo site_url('admin/dashboard'); ?>">
            <i class="entypo-folder"></i>
            <?php echo get_phrase('dashboard'); ?>
        </a>
    </li>
    <li><a href="#" class="active"><?php echo get_phrase('order_detail'); ?></a> </li>
</ol>
<h2><i class="fa fa-arrow-circle-o-right"></i> <?php echo $page_title; ?></h2>
<br />

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#detail"><?php echo get_phrase('detail'); ?></a></li>
                    <li><a data-toggle="tab" href="#update"><?php echo get_phrase('update'); ?></a></li>
                    <li><a data-toggle="tab" href="#invoice"><?php echo get_phrase('invoice'); ?></a></li>
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
                                    <td><?php echo get_phrase('title'); ?></td>
                                    <td><?php echo $model->title; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('user'); ?></td>
                                    <td><a href="<?php echo site_url('admin/user_form/edit_user_form/'. $model->user_id); ?>"><?php echo $model->first_name. ' '. $model->last_name; ?></a></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('course'); ?></td>
                                    <td><a href="<?php echo site_url('admin/course_form/course_edit/'. $model->course_id); ?>"><?php echo $model->course_name; ?></a></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('price'); ?></td>
                                    <td><?php echo currency($model->price); ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('discount'); ?></td>
                                    <td><?php echo ($model->discount > 0)? currency($model->discount) : 0; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('status'); ?></td>
                                    <td><?php echo $this->order_model->getStatus($model->status); ?></td>
                                </tr>
                                </tbody>
                            </table>
                            <?php if ($model->status == 0): ?>
                                <a href="<?php echo site_url('admin/orders/activate/'. $model->id); ?>"
                                   class="btn btn-info" onclick="return confirm_action(this);"
                                   msg="<?php echo get_phrase('are_you_sure_to_activate?'); ?>">
                                    <?php echo get_phrase('activate'); ?></a>
                                <a href="#" class="btn btn-danger"
                                   onclick="confirm_modal('<?php echo site_url('admin/orders/delete/'. $model->id); ?>">
                                    <?php echo get_phrase('delete'); ?>
                                </a>
                            <?php elseif ($model->status == 1): ?>
                                <a href="<?php echo site_url('admin/orders/complete/'. $model->id); ?>"
                                   class="btn btn-info" onclick="return confirm_action(this);"
                                   msg="<?php echo get_phrase('are_you_sure_to_complete?'); ?>">
                                    <?php echo get_phrase('complete'); ?></a>
                                <a href="<?php echo site_url('admin/orders/suspend/'. $model->id); ?>"
                                   class="btn btn-warning" onclick="return confirm_action(this);"
                                   msg="<?php echo get_phrase('are_you_sure_to_suspend?'); ?>">
                                    <?php echo get_phrase('suspend'); ?></a>
                            <?php elseif ($model->status == 2): ?>
                                <a href="<?php echo site_url('admin/orders/unsuspend/'. $model->id); ?>"
                                   class="btn btn-info" onclick="return confirm_action(this);"
                                   msg="<?php echo get_phrase('are_you_sure_to_suspend?'); ?>">
                                    <?php echo get_phrase('unsuspend'); ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="update" class="tab-pane fade">
                        <div class="col-md-8">
                            <form action="<?php echo site_url('admin/orders/edit/'. $model->id); ?>"
                                  method="post" class="form-horizontal">
                                <div class="form-group">
                                    <label class="control-label col-sm-2">
                                        <?php echo get_phrase('user'); ?>:
                                    </label>
                                    <div class="col-sm-8">
                                        <?php $users = $this->user_model->get_all_user(); ?>
                                        <select name="Order[user_id]" class="form-control">
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
                                        <?php echo get_phrase('title'); ?>:
                                    </label>
                                    <div class="col-sm-10">
                                        <input type="text" name="Order[title]" value="<?php echo $model->title; ?>" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">
                                        <?php echo get_phrase('price'); ?>:
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" name="Order[price]" value="<?php echo $model->price; ?>" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">
                                        <?php echo get_phrase('discount'); ?>:
                                    </label>
                                    <div class="col-sm-5">
                                        <input type="text" name="Order[discount]" value="<?php echo $model->discount; ?>" class="form-control"/>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">
                                        <?php echo get_phrase('course'); ?>:
                                    </label>
                                    <div class="col-sm-10">
                                        <?php $courses = $this->crud_model->get_courses(); ?>
                                        <select name="Order[course_id]" class="form-control">
                                            <?php foreach ($courses->result() as $course): ?>
                                                <option value="<?php echo $course->id; ?>" <?php if ($course->id == $model->course_id):?>selected="selected"<?php endif; ?>>
                                                    <?php echo $course->title; ?>
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
                                        <?php $statuses = $this->order_model->getStatus(); ?>
                                        <select name="Order[status]" class="form-control">
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
                                        <textarea name="Order[notes]" class="form-control"><?php echo $model->notes; ?></textarea>
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
                            <table class="table table-hover">
                                <tbody>
                                <tr>
                                    <td><?php echo get_phrase('invoice_number'); ?></td>
                                    <td>
                                        <a href="<?php echo site_url('admin/invoices/detail/'. $model->invoice_id); ?>">
                                            <?php echo $this->invoice_model->getInvoiceFormatedNumber(['id' => $model->invoice_id]); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('total'); ?></td>
                                    <td><?php echo ($model->invoice_total > 0)? currency($model->invoice_total) : 0; ?></td>
                                </tr>
                                <tr>
                                    <td><?php echo get_phrase('status'); ?></td>
                                    <td><?php echo $this->invoice_model->getStatus($model->invoice_status); ?></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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