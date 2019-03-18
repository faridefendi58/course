<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends CI_Model {

    const PAYMENT_TYPE_CASH = 1;
    const PAYMENT_TYPE_CREDIT = 2;

    const STATUS_PENDING = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_SUSPENDED = 2;
    const STATUS_CANCELED = 3;
    const STATUS_COMPLETED = 4;

    function __construct()
    {
        parent::__construct();
        /*cache control*/
        $this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
        $this->output->set_header('Pragma: no-cache');
    }

    public function get_order($id = 0) {
        $this->db->select('orders.*, users.first_name, 
        users.last_name, course.title AS course_name, invoice.cash AS invoice_total, invoice.status AS invoice_status');
        if ($id > 0) {
            $this->db->where('orders.id', $id);
        }

        $this->db->join('users', 'orders.user_id = users.id', 'left');
        $this->db->join('course', 'orders.course_id = course.id', 'left');
        $this->db->join('invoice', 'orders.invoice_id = invoice.id', 'left');

        return $this->db->get('orders');
    }

    public function get_all_order($id = 0) {
        if ($id > 0) {
            $this->db->where('id', $id);
        }

        return $this->db->get('orders');
    }

    public function add_order() {
        $data['user_id'] = $this->input->post('user_id');
        $data['course_id'] = $this->input->post('course_id');
        $data['promo_id'] = $this->input->post('promo_id');
        $data['group_id'] = $this->input->post('group_id');
        $data['group_master'] = $this->input->post('group_master');
        $data['title'] = html_escape($this->input->post('title'));
        $data['invoice_id'] = $this->input->post('invoice_id');
        $data['quantity'] = $this->input->post('quantity');
        $data['price'] = $this->input->post('price');
        $data['discount'] = $this->input->post('discount');
        $data['currency_id'] = $this->input->post('currency_id');
        $data['change_value'] = $this->input->post('change_value');
        $data['type'] = self::PAYMENT_TYPE_CASH;
        $data['status'] = 1;
        $data['date_added'] = strtotime(date("Y-m-d H:i:s"));
        return $data;

        $this->db->insert('orders', $data);
        $order_id = $this->db->insert_id();
        $this->session->set_flashdata('flash_message', get_phrase('order_added_successfully'));
    }

    public function edit_order($order_id = "") { // Admin does this editing
        $data = $this->input->post('Order', TRUE);
        $data['last_modified'] = time();
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data);
        $this->session->set_flashdata('flash_message', get_phrase('order_update_successfully'));
    }

    public function delete_order($order_id = "") {
        $this->db->where('id', $order_id);
        $this->db->delete('orders');
        $this->session->set_flashdata('flash_message', get_phrase('order_deleted_successfully'));
    }

    public function getNextGroupId()
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

    public function activate($order_id)
    {
        $model = $this->get_order($order_id)->row();

        $data1 = [
            'status' => self::STATUS_ACTIVE,
            'last_modified' => time(),
            'activated_at' => time(),
        ];
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data1);

        // purchase course
        $is_enrolled = $this->crud_model->is_already_enrolled($model->course_id, $model->user_id);
        if (!$is_enrolled) {
            $data['user_id'] = $model->user_id;
            $data['payment_type'] = 'transferbank';
            $data['course_id'] = $model->course_id;
            $course_details = $this->crud_model->get_course_by_id($model->course_id)->row_array();
            if ($course_details['discount_flag'] == 1) {
                $data['amount'] = $course_details['discounted_price'];
            }else {
                $data['amount'] = $course_details['price'];
            }
            if (get_user_role('role_id', $course_details['user_id']) == 1) {
                $data['admin_revenue'] = $data['amount'];
                $data['instructor_revenue'] = 0;
                $data['instructor_payment_status'] = 1;
            }else {
                if (get_settings('allow_instructor') == 1) {
                    $instructor_revenue_percentage = get_settings('instructor_revenue');
                    $data['instructor_revenue'] = ceil(($data['amount'] * $instructor_revenue_percentage) / 100);
                    $data['admin_revenue'] = $data['amount'] - $data['instructor_revenue'];
                }else {
                    $data['instructor_revenue'] = 0;
                    $data['admin_revenue'] = $data['amount'];
                }
                $data['instructor_payment_status'] = 0;
            }
            $data['date_added'] = strtotime(date('D, d-M-Y'));
            $this->db->insert('payment', $data);
        }
    }

    public function suspend($order_id)
    {
        $model = $this->get_order($order_id)->row();

        $data1 = [
            'status' => self::STATUS_SUSPENDED,
            'last_modified' => time(),
            'suspended_at' => time(),
        ];
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data1);

        // purchase course
        $is_enrolled = $this->crud_model->is_already_enrolled($model->course_id, $model->user_id);
        if ($is_enrolled) {
        }
    }

    public function unsuspend($order_id)
    {
        $model = $this->get_order($order_id)->row();

        $data1 = [
            'status' => self::STATUS_ACTIVE,
            'last_modified' => time(),
            'suspended_at' => null,
        ];
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data1);

        // purchase course
        $is_enrolled = $this->crud_model->is_already_enrolled($model->course_id, $model->user_id);
        if ($is_enrolled) {
        }
    }

    public function complete($order_id)
    {
        $model = $this->get_order($order_id)->row();

        $data1 = [
            'status' => self::STATUS_COMPLETED,
            'last_modified' => time(),
            'completed_at' => time(),
        ];
        $this->db->where('id', $order_id);
        $this->db->update('orders', $data1);
    }

    public function get_orders_by_status($status = 0) {
        $this->db->select('orders.*, users.first_name, users.last_name, course.title AS course_name');
        $this->db->where('orders.status', $status);

        $this->db->join('users', 'orders.user_id = users.id', 'left');
        $this->db->join('course', 'orders.course_id = course.id', 'left');

        return $this->db->get('orders');
    }

    public function getStatus($id_status = -1) {
        $items = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_ACTIVE => 'Active',
            self::STATUS_SUSPENDED => 'Suspended',
            self::STATUS_CANCELED => 'Canceled',
            self::STATUS_COMPLETED => 'Completed',
        ];

        if ($id_status >= 0) {
            return $items[$id_status];
        }

        return $items;
    }
}
