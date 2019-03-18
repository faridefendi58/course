<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice_model extends CI_Model {

    const STATUS_PAID = 1;
    const STATUS_UNPAID = 0;
    const STATUS_REFUND = 2;

    const TYPE_ORDER = 'order';

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function get_invoice($id = 0) {
        if ($id > 0) {
            $this->db->where('id', $id);
        }

        return $this->db->get('invoice');
    }

    public function get_all_invoice($id = 0) {
        $this->db->select('invoice.*, users.first_name, users.last_name');
        if ($id > 0) {
            $this->db->where('id', $id);
        }
        $this->db->join('users', 'invoice.user_id = users.id', 'left');

        return $this->db->get('invoice');
    }

    public function get_invoices_by_status($status = 0) {
        $this->db->select('invoice.*, users.first_name, users.last_name');
        $this->db->where('status', $status);

        $this->db->join('users', 'invoice.user_id = users.id', 'left');

        return $this->db->get('invoice');
    }

    public function add_invoice($data = []) {
        $data0['user_id'] = $this->session->userdata('user_id');
        $data0['serie'] = $this->getInvoiceNumber(self::STATUS_UNPAID, 'serie');
        $data0['nr'] = $this->getInvoiceNumber(self::STATUS_UNPAID, 'nr');
        $data0['cash'] = $this->input->post('total_price_of_checking_out');
        $data0['currency_id'] = $data['currency_id'];
        $data0['change_value'] = $data['change_value'];
        $data0['status'] = 1;
        $data0['date_added'] = strtotime(date("Y-m-d H:i:s"));
        $data0['last_modified'] = strtotime(date("Y-m-d H:i:s"));

        try {
            $this->db->insert('invoice', $data0);
            $invoice_id = $this->db->insert_id();
        } catch (Exception $e) {}

        if ($invoice_id > 0) {
            $group_id = $this->getOrderNextGroupId();
            $no = 1;
            foreach ($data['cart_items'] as $course_id => $course) {
                $data1 = [];
                $data1['user_id'] = $data0['user_id'];
                $data1['course_id'] = $course_id;
                $data1['group_id'] = $group_id;
                $data1['group_master'] = ($no == 1)? 1 : 0;
                $data1['title'] = $course['title'];
                $data1['invoice_id'] = $invoice_id;
                $data1['quantity'] = 1;
                $data1['price'] = (!empty($course['discounted_price']))? $course['discounted_price'] : $course['price'];
                $data1['discount'] = (!empty($course['discounted_price']))? ($course['price'] - $course['discounted_price']) : 0;
                $data1['currency_id'] = $data['currency_id'];
                $data1['change_value'] = $data['change_value'];
                $data1['type'] = 1;
                $data1['status'] = 0;
                $data1['date_added'] = strtotime(date("Y-m-d H:i:s"));
                $data1['last_modified'] = strtotime(date("Y-m-d H:i:s"));
                $this->db->insert('orders', $data1);
                $order_id = $this->db->insert_id();
                if ($order_id) {
                    // invoice item
                    $data2 = [];
                    $data2['invoice_id'] = $invoice_id;
                    $data2['type'] = self::TYPE_ORDER;
                    $data2['rel_id'] = $order_id;
                    $data2['title'] = $course['title'];
                    $data2['quantity'] = 1;
                    $data2['price'] = (!empty($course['discounted_price']))? $course['discounted_price'] : $course['price'];
                    $data2['date_added'] = strtotime(date("Y-m-d H:i:s"));
                    $data2['last_modified'] = strtotime(date("Y-m-d H:i:s"));

                    $this->db->insert('invoice_item', $data2);
                    $invoice_item_id = $this->db->insert_id();
                }

                $no++;
            }
        }
        $this->session->set_flashdata('flash_message', get_phrase('order_added_successfully'));
    }

    public function edit_invoice($invoice_id = "") { // Admin does this editing
        $data = [];
        $this->db->where('id', $invoice_id);
        $this->db->update('invoice', $data);
        $this->session->set_flashdata('flash_message', get_phrase('invoice_update_successfully'));
    }

    public function delete_invoice($invoice_id = "") {
        $this->db->where('id', $invoice_id);
        $this->db->delete('invoice');
        $this->session->set_flashdata('flash_message', get_phrase('invoice_deleted_successfully'));
    }

    public function getInvoiceNumber($status, $type)
    {
        if ($status == self::STATUS_PAID)
            $serie = 'PAID-';
        elseif ($status == self::STATUS_UNPAID)
            $serie = 'UNPAID-';
        else
            $serie = 'REFUND-';

        $this->db->select_max('nr');
        $this->db->where('status', $status);
        $res1 = $this->db->get('invoice');

        $result = $res1->row();

        $next_nr = $result->nr +1;
        if($type == 'serie')
            return $serie;
        else
            return $next_nr;
    }

    public function getInvoiceFormatedNumber($data = array())
    {
        $this->db->where('id', $data['id']);
        $query = $this->db->get('invoice');
        $result = $query->row();

        $nr = str_repeat('0',4-strlen($result->nr));

        return $result->serie.$nr.$result->nr;
    }

    public function getOrderNextGroupId()
    {
        $this->db->select_max('group_id');
        $res1 = $this->db->get('orders');

        $result = 0;
        if ($res1->num_rows() > 0)
        {
            $res2 = $res1->result_array();
            $result = $res2[0]['group_id'];
        }

        $next_group_id = $result +1;

        return $next_group_id;
    }

    public function get_items($invoice_id = 0) {
        $this->db->select('invoice_item.*, orders.course_id, orders.invoice_id as unpaid_invoice_id');
        if ($invoice_id > 0) {
            $this->db->where('invoice_item.invoice_id', $invoice_id);
        }
        $this->db->join('orders', 'invoice_item.rel_id = orders.id', 'left');

        return $this->db->get('invoice_item');
    }

    public function mark_as_paid($invoice_id = "") {
        $model = $this->get_invoice($invoice_id)->row();
        $items = $this->get_items($invoice_id);

        $data = [
            'serie' => $this->getInvoiceNumber(self::STATUS_PAID, 'serie'),
            'nr' => $this->getInvoiceNumber(self::STATUS_PAID, 'nr'),
            'status' => self::STATUS_PAID,
            'paid_at' => time(),
            'last_modified' => time(),
        ];
        $this->db->where('id', $invoice_id);
        $this->db->update('invoice', $data);

        if ($items->num_rows() > 0) {
            foreach ($items->result_array() as $i => $item) {
                $data2['user_id'] = $model->user_id;
                $data2['course_id'] = $item['course_id'];
                $data2['date_added'] = strtotime(date('D, d-M-Y'));
                $this->db->insert('enroll', $data2);

                $this->order_model->activate($item['rel_id']);
            }
        }

        $this->session->set_flashdata('flash_message', get_phrase('invoice_marked_as_paid_successfully'));
    }

    public function getStatus($id_status = -1) {
        $items = [
            self::STATUS_UNPAID => 'Unpaid',
            self::STATUS_PAID => 'Paid',
            self::STATUS_REFUND => 'Refunded'
        ];

        if ($id_status >= 0) {
            return $items[$id_status];
        }

        return $items;
    }
}
