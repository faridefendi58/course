<?php $model = $this->invoice_model->get_invoice_by_hash($hash)->row(); ?>

<section class="page-header-area">
    <div class="container">
        <div class="row">
            <div class="col">
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#"><i class="fas fa-home"></i></a></li>
                        <li class="breadcrumb-item"><a href="#"><?php echo get_phrase('my_invoice_detail'); ?></a></li>
                    </ol>
                </nav>
                <h1 class="page-title"><?php echo get_phrase('my_invoice_detail'); ?></h1>
            </div>
        </div>
    </div>
</section>

<section class="mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <table class="table table-hover">
                    <tbody>
                    <tr>
                        <td><b><?php echo get_phrase('invoice_number'); ?></b></td>
                        <td>: <?php echo $this->invoice_model->getInvoiceFormatedNumber(['id' => $model->id]); ?></td>
                        <td><b><?php echo get_phrase('issued_at'); ?></b></td>
                        <td>: <?php echo date("Y-m-d H:i:s", $model->date_added); ?></td>
                    </tr>
                    <tr>
                        <td><b><?php echo get_phrase('status'); ?></b></td>
                        <td>: <?php echo $this->invoice_model->getStatus($model->status); ?></td>
                        <td colspan="2">&nbsp;</td>
                    </tr>
                    </tbody>
                </table>

                <table class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th style="text-align: center;">No</th>
                        <th style="text-align: center;"><?php echo get_phrase('course_name'); ?></th>
                        <th style="text-align: center;"><?php echo get_phrase('price'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php $items = $this->invoice_model->get_items($model->id); ?>
                    <?php $no = 1; $total = 0; ?>
                    <?php foreach ($items->result() as $item): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $no; ?></td>
                            <td><?php echo $item->title; ?></td>
                            <td style="text-align: right;"><?php echo currency($item->price); ?></td>
                        </tr>
                        <?php $no++; $total = $total + $item->price; ?>
                    <?php endforeach; ?>
                    <tr>
                        <td colspan="2" style="text-align: center;font-weight: bold;"><?php echo strtoupper(get_phrase('total')); ?></td>
                        <td style="text-align: right;"><b><?php echo currency($total); ?></b></td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <h4><?php echo get_phrase('payment_method'); ?></h4>

                <table class="table table-bordered table-hover mt-3">
                    <tbody>
                    <tr>
                        <td>
                            <b>BCA</b><br/>
                            No Rek : 122000000<br/>
                            A.n. John Due
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>BANK Mandiri</b><br/>
                            No Rek : 122000000<br/>
                            A.n. John Due
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <b>BANK BNI</b><br/>
                            No Rek : 122000000<br/>
                            A.n. John Due
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
