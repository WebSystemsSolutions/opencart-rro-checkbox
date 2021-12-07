<?php

class ControllerModuleCheckbox extends Controller
{
    private $error = array();
    private $json = array();

    public function index()
    {
        $this->load->language('module/checkbox');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('checkbox', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->redirect($this->url->link('extension/module', 'token=' . $this->session->data['token'], true));
        }

        $data['heading_title'] = $this->language->get('heading_title');

        $data['text_edit'] = $this->language->get('text_edit');
        $data['text_enabled'] = $this->language->get('text_enabled');
        $data['text_disabled'] = $this->language->get('text_disabled');

        $data['entry_rro_login'] = $this->language->get('entry_rro_login');
        $data['entry_rro_password'] = $this->language->get('entry_rro_password');
        $data['entry_rro_cashbox_key'] = $this->language->get('entry_rro_cashbox_key');

        $data['entry_rro_receipt_header'] = $this->language->get('entry_rro_receipt_header');
        $data['entry_rro_receipt_footer'] = $this->language->get('entry_rro_receipt_footer');

        $data['entry_status'] = $this->language->get('entry_status');

        $data['button_save'] = $this->language->get('button_save');
        $data['button_cancel'] = $this->language->get('button_cancel');

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['breadcrumbs'] = array();

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_home'),
            'href'      => $this->url->link('common/home', 'token=' . $this->session->data['token'], true),
            'separator' => false
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('text_module'),
            'href'      => $this->url->link('extension/module', 'token=' . $this->session->data['token'], true),
            'separator' => ' :: '
        );

        $data['breadcrumbs'][] = array(
            'text'      => $this->language->get('heading_title'),
            'href'      => $this->url->link('module/checkbox', 'token=' . $this->session->data['token'], true),
            'separator' => ' :: '
        );


        $data['action'] = $this->url->link('module/checkbox', 'token=' . $this->session->data['token'], true);

        $data['cancel'] = $this->url->link('extension/module', 'token=' . $this->session->data['token'], true);

        if (isset($this->request->post['checkbox_rro_login'])) {
            $data['checkbox_rro_login'] = $this->request->post['checkbox_rro_login'];
        } else {
            $data['checkbox_rro_login'] = $this->config->get('checkbox_rro_login');
        }

        if (isset($this->request->post['checkbox_rro_password'])) {
            $data['checkbox_rro_password'] = $this->request->post['checkbox_rro_password'];
        } else {
            $data['checkbox_rro_password'] = $this->config->get('checkbox_rro_password');
        }

        if (isset($this->request->post['checkbox_rro_cashbox_key'])) {
            $data['checkbox_rro_cashbox_key'] = $this->request->post['checkbox_rro_cashbox_key'];
        } else {
            $data['checkbox_rro_cashbox_key'] = $this->config->get('checkbox_rro_cashbox_key');
        }

        if (isset($this->request->post['checkbox_rro_is_dev'])) {
            $data['checkbox_rro_is_dev'] = $this->request->post['checkbox_rro_is_dev'];
        } else {
            $data['checkbox_rro_is_dev'] = $this->config->get('checkbox_rro_is_dev');
        }

        if (isset($this->request->post['checkbox_rro_receipt_header'])) {
            $data['checkbox_rro_receipt_header'] = $this->request->post['checkbox_rro_receipt_header'];
        } else {
            $data['checkbox_rro_receipt_header'] = $this->config->get('checkbox_rro_receipt_header');
        }

        if (isset($this->request->post['checkbox_rro_receipt_footer'])) {
            $data['checkbox_rro_receipt_footer'] = $this->request->post['checkbox_rro_receipt_footer'];
        } else {
            $data['checkbox_rro_receipt_footer'] = $this->config->get('checkbox_rro_receipt_footer');
        }

        if (isset($this->request->post['checkbox_status'])) {
            $data['checkbox_status'] = $this->request->post['checkbox_status'];
        } else {
            $data['checkbox_status'] = $this->config->get('checkbox_status');
        }

        $data['instruction'] = '1) для роботи з касою запустіть Checkbox підпис або HSM (DepositSign)';

        $this->data = $data;
        $this->template = 'module/checkbox.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }


    public function getViewInfo()
    {
        if (!$this->config->get('checkbox_status')) {
            return '';
        }

        if (isset($this->request->get['order_id'])) {
            $data['order_id'] = $this->request->get['order_id'];
        } else {
            $data['order_id'] = 0;
        }

        if (isset($this->request->get['token'])) {
            $data['token'] = $this->request->get['token'];
        } else {
            $data['token'] = 0;
        }

        $this->data = $data;
        $this->template = 'module/checkbox_order_info.tpl';
        return $this->render();
    }

    public function getViewList()
    {

        if (!$this->config->get('checkbox_status')) {
            return '';
        }

        if (isset($this->request->get['token'])) {
            $data['token'] = $this->request->get['token'];
        } else {
            $data['token'] = 0;
        }

        $this->data = $data;
        $this->template = 'module/checkbox_order_list.tpl';
        return $this->render();
    }

    public function createReceipt()
    {

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->request->get['payment_type'])) {
            $payment_type = $this->request->get['payment_type'];
        } else {
            $payment_type = 'CASH';
        }

        if (isset($this->request->get['send_email'])) {
            $send_email = $this->request->get['send_email'];
        } else {
            $send_email = true;
        }

        if (isset($this->request->get['is_return'])) {
            $is_return = ($this->request->get['is_return'] > 0);
        } else {
            $is_return = false;
        }

        $this->load->model('sale/order');
        $this->load->model('extension/payment/checkbox');

        $order_info = $this->model_extension_payment_checkbox->getOrder($order_id);


        $email = $order_info['email'];
        $cashier_name = $order_info['firstname'] . ' ' . $order_info['lastname'];
        $departament = 'store';


        $params = array();

        $goods = array();

        $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

        $total = 0;

        foreach ($products as $product) {

            $price = (float)number_format((float)$product['price'], 2, '', '');

            $good = array(
                'code'  => $product['product_id'] . '-' . $product['name'],
                'name'  => $product['name'],
                'price' => $price
            );

            $total += $price;

            $item = array(
                'good'     => $good,
                'quantity' => (int)$product['quantity'] * 1000
            );

            if ($is_return) {
                $item['is_return'] = true;
            }

            $goods[] = $item;
        }

        $params['goods'] = $goods;
        $params['cashier_name'] = $cashier_name;
        $params['departament'] = $departament;

        if ($send_email) {
            $params['delivery'] = array('email' => $email);
        }


        $order_totals = $this->model_sale_order->getOrderTotals($this->request->get['order_id']);

        foreach ($order_totals as $order_total) {

            if ('total' == $order_total['code']) {
                $total = (float)number_format((float)$order_total['value'], 2, '', '');
            }

            if (in_array($order_total['code'], array('sub_total', 'total'))) {
                continue;
            }

            if ($order_total['value'] > 0) {
                $discount_type = 'EXTRA_CHARGE';
            } else {
                $discount_type = 'DISCOUNT';
            }

            $discount_price = abs((float)number_format((float)$order_total['value'], 2, '', ''));

            if ($discount_price > 0){
                $params['discounts'][] = array(
                    'type'  => $discount_type,
                    'mode'  => 'VALUE',
                    'value' => $discount_price,
                    'name'  => strip_tags($order_total['title']),
                );
            }
        }

        $params['payments'][] = [
            'type'  => ( in_array($payment_type, array('CASH', 'CASHLESS',  'CARD'))) ? $payment_type : 'CASH',
            'value' => $total
        ];

        if ($this->config->get('checkbox_rro_receipt_header')) {
            $params['header'] = $this->config->get('checkbox_rro_receipt_header');
        }

        if ($this->config->get('checkbox_rro_receipt_footer')) {
            $params['footer'] = $this->config->get('checkbox_rro_receipt_footer');
        }

        $receipt = $this->model_extension_payment_checkbox->create_receipt($params);

        $this->json['return_$params'] = $params;
        $this->json['return_$receipt'] = $receipt;

        if (isset($receipt['id'])) {
            if ($is_return) {
                $this->db->query("UPDATE " . DB_PREFIX . "order SET checkbox_return_receipt_id = '" . $this->db->escape($receipt['id']) . "' WHERE order_id = '" . (int)$order_id . "'");
            } else {
                $this->db->query("UPDATE " . DB_PREFIX . "order SET checkbox_receipt_id = '" . $this->db->escape($receipt['id']) . "' WHERE order_id = '" . (int)$order_id . "'");
            }
        }

        if (isset($receipt['message'])) {
            $this->json['error'] = '<pre style="color: blue">' . print_r($receipt['message'], 1) . '</pre>';
        }


        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($this->json));

    }

    public function getReceiptHtml()
    {

        if (isset($this->request->get['receipt_id'])) {
            $receipt_id = $this->request->get['receipt_id'];
        } else {
            $receipt_id = '';
        }

        $this->load->model('extension/payment/checkbox');

        if ($receipt_id) {
            echo $this->model_extension_payment_checkbox->getReceiptHtml($receipt_id);
        }
    }

    public function orderInfo()
    {

        sleep(2);

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        if (isset($this->request->get['token'])) {
            $token = $this->request->get['token'];
        } else {
            $token = 0;
        }

        $data['token'] = $token;
        $data['order_id'] = $order_id;

        $this->load->model('extension/payment/checkbox');

        $order_info = $this->model_extension_payment_checkbox->getOrder($order_id);


        list($shift_id, $is_connected, $status) = $this->getShiftStatus();

        $data['link_html_receipt'] = $this->url->link('module/checkbox/getReceiptHtml', 'token=' . $token . '&receipt_id=' . $order_info['checkbox_receipt_id'], true);
        $data['link_html_receipt_return'] = $this->url->link('module/checkbox/getReceiptHtml', 'token=' . $token . '&receipt_id=' . $order_info['checkbox_return_receipt_id'], true);

        $data['status'] = $status;

        $data['checkbox_receipt_id'] = '';
        $data['checkbox_return_receipt_id'] = '';

        if ($order_info['checkbox_receipt_id']) {
            $receipt_info = $this->model_extension_payment_checkbox->getReceipt($order_info['checkbox_receipt_id']);
            if (isset($receipt_info['fiscal_code'])) {
                $data['checkbox_receipt_id'] = $receipt_info['fiscal_code'];
            }
        }

        if ($order_info['checkbox_return_receipt_id']) {
            $receipt_info = $this->model_extension_payment_checkbox->getReceipt($order_info['checkbox_return_receipt_id']);
            if (isset($receipt_info['fiscal_code'])) {
                $data['checkbox_return_receipt_id'] = $receipt_info['fiscal_code'];
            }
        }

        $this->data = $data;
        $this->template = 'module/checkbox_order_info_ajax.tpl';
        $this->response->setOutput($this->render());

    }

    public function getZReport()
    {

        $this->load->model('extension/payment/checkbox');

        $shifts = $this->model_extension_payment_checkbox->getShifts();

        $z_report_id = '';

        if (!empty($shifts['results'])) {
            foreach ($shifts['results'] as $shift) {

                if ($shift->z_report && $shift->z_report->is_z_report) {
                    $z_report_id = $shift->z_report->id;
                    break;
                }
            }
        }

        if ($z_report_id) {

            $html = $this->model_extension_payment_checkbox->getReportText($z_report_id);

            echo str_replace(PHP_EOL, '<br>', $html);

        } else {
            echo 'Звітів не знайдено';
        }


    }

    public function createCashierShift()
    {

        $this->load->model('extension/payment/checkbox');

        $shift = $this->model_extension_payment_checkbox->connect();

        if (isset($shift['message'])) {
            $this->json['error'] = '<pre style="color: blue">' . print_r($shift['message'], 1) . '</pre>';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($this->json));
    }

    public function closeCashierShift()
    {

        $this->load->model('extension/payment/checkbox');

        $shift = $this->model_extension_payment_checkbox->disconnect();

        if (isset($shift['message'])) {
            $this->json['error'] = '<pre style="color: blue">' . print_r($shift['message'], 1) . '</pre>';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($this->json));
    }

    public function createServiceReceipt()
    {
        if (isset($this->request->get['cash'])) {
            $cash = $this->request->get['cash'];
        } else {
            $cash = 0;
        }

        $cash_val = (int)((float)$this->request->get['cash'] * 100);

        $this->load->model('extension/payment/checkbox');

        $shift = $this->model_extension_payment_checkbox->createServiceReceipt($cash_val);

        if (isset($shift['message'])) {
            $this->json['error'] = '<pre style="color: blue">' . print_r($shift['message'], 1) . '</pre>';
        }

        $this->response->addHeader('Content-Type: application/json');
        $this->response->setOutput(json_encode($this->json));
    }

    public function orderListContainer()
    {
        sleep(2);

        if (isset($this->request->get['token'])) {
            $token = $this->request->get['token'];
        } else {
            $token = 0;
        }

        list($shift_id, $is_connected, $status, $balance) = $this->getShiftStatus();

        $data['shift_id'] = $shift_id;
        $data['is_connected'] = $is_connected;
        $data['status'] = $status;
        $data['balance'] = $balance;

        $data['link_z_report'] = $this->url->link('module/checkbox/getZReport', 'token=' . $token, true);

        $this->data = $data;
        $this->template = 'module/checkbox_order_list_ajax.tpl';
        $this->response->setOutput($this->render());
    }

    private function getShiftStatus()
    {

        $status = 'CLOSED';
        $shift_id = '';
        $date = '';
        $is_connected = false;
        $balance = '';

        $this->load->model('extension/payment/checkbox');

        $shift = $this->model_extension_payment_checkbox->getCurrentCashierShift();

        if ($shift) {
            $shift_id = isset($shift['id']) ? $shift['id'] : '';
            $is_connected = isset($shift['status']) && ($shift['status'] == 'OPENED');
            $status = isset($shift['status']) ? $shift['status'] : $status;

            if (!empty($shift['opened_at'])) {
                $date = date('d.m.Y H:i', strtotime($shift['opened_at']));
            }
            if (!empty($shift['closed_at'])) {
                $date = date('d.m.Y H:i', strtotime($shift['closed_at']));
            }

            if(isset($shift['balance'])){
                $balance = number_format(substr($shift['balance']->balance, 0, -2), 0, '', ' ') . '.' . substr($shift['balance']->balance, -2);
            }

        }

        if (in_array($status, ['CLOSED', 'CLOSING'])) {
            $status = '<span style="color: red">' . $status . '</span>';
        }

        if (in_array($status, ['OPEN', 'OPENING', 'CREATED'])) {
            $status = '<span style="color: blue">' . $status . '</span>';
        }
        if (in_array($status, ['OPENED'])) {
            $status = '<span style="color: green">' . $status . '</span>';
        }

        $status = $status . '<br>' . $date;


        return array($shift_id, $is_connected, $status, $balance);
    }

    protected function validate()
    {

        if (!$this->user->hasPermission('modify', 'module/checkbox')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        return !$this->error;
    }

    public function install()
    {
        $query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` LIMIT 1");
        if (!isset($query->row['checkbox_receipt_id'])) {
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `checkbox_receipt_id` VARCHAR(64) NOT NULL");
            $this->db->query("ALTER TABLE `" . DB_PREFIX . "order` ADD  `checkbox_return_receipt_id` VARCHAR(64) NOT NULL");
        }
    }
}
