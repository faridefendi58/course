<?php
$this->db->where('user_id', $this->session->userdata('user_id'));
$my_invoices = $this->db->get('invoice',$per_page, $this->uri->segment(3));
?>
<section class="page-header-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#"><?php echo get_phrase('my_invoices'); ?></a></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?php echo get_phrase('my_invoices'); ?></h1>
            </div>
        </div>
    </div>
</section>


<section class="purchase-history-list-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <ul class="purchase-history-list">
                    <li class="purchase-history-list-header">
                        <div class="row">
                            <div class="col-sm-3"><h4 class="purchase-history-list-title"> <?php echo get_phrase('my_invoices'); ?> </h4></div>
                            <div class="col-sm-9 hidden-xxs hidden-xs">
                                <div class="row">
                                    <div class="col-sm-4"> <?php echo get_phrase('items'); ?> </div>
                                    <div class="col-sm-2"> <?php echo get_phrase('date'); ?> </div>
                                    <div class="col-sm-2"> <?php echo get_phrase('total_price'); ?> </div>
                                    <div class="col-sm-2"> <?php echo get_phrase('status'); ?> </div>
                                    <div class="col-sm-2"> </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php if ($my_invoices->num_rows() > 0):
                        foreach($my_invoices->result_array() as $my_invoice):?>
                            <li>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <a class="purchase-history-course-title" href="<?php echo site_url('home/my_invoices/'.$my_invoice['hash']); ?>" >
                                            <?php echo $this->invoice_model->getInvoiceFormatedNumber([ 'id' => $my_invoice['id'] ]);?>
                                        </a>
                                    </div>
                                    <div class="col-sm-9 purchase-history-detail">
                                        <div class="row">
                                            <div class="col-sm-4">
                                                <?php $items = $this->invoice_model->get_items($my_invoice['id']); ?>
                                                <ul>
                                                <?php foreach ($items->result() as $item): ?>
                                                    <li><?php echo $item->title; ?> (<?php echo currency($item->price); ?>)</li>
                                                <?php endforeach; ?>
                                                </ul>
                                            </div>
                                            <div class="col-sm-2 date">
                                                <?php echo date('D, d-M-Y', $my_invoice['date_added']); ?>
                                            </div>
                                            <div class="col-sm-2 price">
                                                <b><?php echo currency($my_invoice['cash']); ?></b>
                                            </div>
                                            <div class="col-sm-2">
                                                <?php echo $this->invoice_model->getStatus($my_invoice['status']); ?>
                                            </div>
                                            <div class="col-sm-2">
                                                <a href="<?php echo site_url('home/my_invoices/'.$my_invoice['hash']); ?>" class="btn btn-receipt"><?php echo get_phrase('detail'); ?></a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <li>
                            <div class="row" style="text-align: center;">
                                <?php echo get_phrase('no_records_found'); ?>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</section>
<nav>
    <?php echo $this->pagination->create_links(); ?>
</nav>
