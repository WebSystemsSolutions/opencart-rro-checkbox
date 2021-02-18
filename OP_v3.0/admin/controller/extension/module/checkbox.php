<?php

class ControllerExtensionModuleCheckbox extends Controller
{
    private $error = array();
    private $json = array();

    public function index()
    {
        $this->load->language('extension/module/checkbox');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('module_checkbox', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            $this->response->redirect($this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true));

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
            'text' => $this->language->get('text_home'),
            'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('text_extension'),
            'href' => $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true)
        );

        $data['breadcrumbs'][] = array(
            'text' => $this->language->get('heading_title'),
            'href' => $this->url->link('extension/module/checkbox', 'user_token=' . $this->session->data['user_token'], true)
        );

        $data['action'] = $this->url->link('extension/module/checkbox', 'user_token=' . $this->session->data['user_token'], true);

        $data['cancel'] = $this->url->link('extension/extension', 'user_token=' . $this->session->data['user_token'] . '&type=module', true);

        if (isset($this->request->post['module_checkbox_rro_login'])) {
            $data['module_checkbox_rro_login'] = $this->request->post['module_checkbox_rro_login'];
        } else {
            $data['module_checkbox_rro_login'] = $this->config->get('module_checkbox_rro_login');
        }

        if (isset($this->request->post['module_checkbox_rro_password'])) {
            $data['module_checkbox_rro_password'] = $this->request->post['module_checkbox_rro_password'];
        } else {
            $data['module_checkbox_rro_password'] = $this->config->get('module_checkbox_rro_password');
        }

        if (isset($this->request->post['module_checkbox_rro_cashbox_key'])) {
            $data['module_checkbox_rro_cashbox_key'] = $this->request->post['module_checkbox_rro_cashbox_key'];
        } else {
            $data['module_checkbox_rro_cashbox_key'] = $this->config->get('module_checkbox_rro_cashbox_key');
        }

        if (isset($this->request->post['module_checkbox_rro_is_dev'])) {
            $data['module_checkbox_rro_is_dev'] = $this->request->post['module_checkbox_rro_is_dev'];
        } else {
            $data['module_checkbox_rro_is_dev'] = $this->config->get('module_checkbox_rro_is_dev');
        }

        if (isset($this->request->post['module_checkbox_rro_receipt_header'])) {
            $data['module_checkbox_rro_receipt_header'] = $this->request->post['module_checkbox_rro_receipt_header'];
        } else {
            $data['module_checkbox_rro_receipt_header'] = $this->config->get('module_checkbox_rro_receipt_header');
        }

        if (isset($this->request->post['module_checkbox_rro_receipt_footer'])) {
            $data['module_checkbox_rro_receipt_footer'] = $this->request->post['module_checkbox_rro_receipt_footer'];
        } else {
            $data['module_checkbox_rro_receipt_footer'] = $this->config->get('module_checkbox_rro_receipt_footer');
        }

        if (isset($this->request->post['module_checkbox_status'])) {
            $data['module_checkbox_status'] = $this->request->post['module_checkbox_status'];
        } else {
            $data['module_checkbox_status'] = $this->config->get('module_checkbox_status');
        }

        $data['instruction'] = print_r('1) для роботи з касою запустіть Checkbox підпис або HSM (DepositSign)', 1);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->view('extension/module/checkbox', $data));

    }


    public function getViewInfo()
    {
        if (!$this->config->get('module_checkbox_status')) {
            return '';
        }

        if (isset($this->request->get['order_id'])) {
            $data['order_id'] = $this->request->get['order_id'];
        } else {
            $data['order_id'] = 0;
        }

        if (isset($this->request->get['user_token'])) {
            $data['user_token'] = $this->request->get['user_token'];
        } else {
            $data['user_token'] = 0;
        }

        return $this->load->view('extension/module/checkbox_order_info', $data);


    }

    public function getViewList()
    {

        if (!$this->config->get('module_checkbox_status')) {
            return '';
        }

        if (isset($this->request->get['user_token'])) {
            $data['user_token'] = $this->request->get['user_token'];
        } else {
            $data['user_token'] = 0;
        }

        return $this->load->view('extension/module/checkbox_order_list', $data);

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


        $params = [];

        $goods = [];

        $products = $this->model_sale_order->getOrderProducts($this->request->get['order_id']);

        $total = 0;

        foreach ($products as $product) {

            $price = (float)number_format((float)$product['price'], 2, '', '');

            $good = [
                'code'  => $product['product_id'] . '-' . $product['name'],
                'name'  => $product['name'],
                'price' => $price
            ];

            $total += $price;

            $item = [
                'good'     => $good,
                'quantity' => (int)$product['quantity'] * 1000
            ];

            if ($is_return) {
                $item['is_return'] = true;
            }

            $goods[] = $item;
        }

        $params['goods'] = $goods;
        $params['cashier_name'] = $cashier_name;
        $params['departament'] = $departament;
        $params['delivery'] = ['email' => $email];

        $params['payments'][] = [
            'type'  => ( in_array($payment_type, ['CASH', 'CASHLESS',  'CARD'])) ? $payment_type : 'CASH',
            'value' => $total
        ];

        if ($this->config->get('module_checkbox_rro_receipt_header')) {
            $params['header'] = $this->config->get('module_checkbox_rro_receipt_header');
        }

        if ($this->config->get('module_checkbox_rro_receipt_footer')) {
            $params['footer'] = $this->config->get('module_checkbox_rro_receipt_footer');
        }

        $receipt = $this->model_extension_payment_checkbox->create_receipt($params);

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

        if (isset($this->request->get['user_token'])) {
            $user_token = $this->request->get['user_token'];
        } else {
            $user_token = 0;
        }

        $data['user_token'] = $user_token;
        $data['order_id'] = $order_id;

        $this->load->model('extension/payment/checkbox');

        $order_info = $this->model_extension_payment_checkbox->getOrder($order_id);


        list($shift_id, $is_connected, $status) = $this->getShiftStatus();

        $data['link_html_receipt'] = $this->url->link('extension/module/checkbox/getReceiptHtml', 'user_token=' . $user_token . '&receipt_id=' . $order_info['checkbox_receipt_id'], true);
        $data['link_html_receipt_return'] = $this->url->link('extension/module/checkbox/getReceiptHtml', 'user_token=' . $user_token . '&receipt_id=' . $order_info['checkbox_return_receipt_id'], true);

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

        $this->response->setOutput($this->load->view('extension/module/checkbox_order_info_ajax', $data));

    }

    public function getZReport()
    {

        $this->load->model('extension/payment/checkbox');

        $shifts = $this->model_extension_payment_checkbox->getShifts();

        $z_report_id = '';

        if ($shifts['results']) {
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

        if (isset($this->request->get['user_token'])) {
            $user_token = $this->request->get['user_token'];
        } else {
            $user_token = 0;
        }

        list($shift_id, $is_connected, $status, $balance) = $this->getShiftStatus();

        $data['shift_id'] = $shift_id;
        $data['is_connected'] = $is_connected;
        $data['status'] = $status;
        $data['balance'] = $balance;

        $data['link_z_report'] = $this->url->link('extension/module/checkbox/getZReport', 'user_token=' . $user_token, true);

        $this->response->setOutput($this->load->view('extension/module/checkbox_order_list_ajax', $data));

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

            if ($shift['opened_at']) {
                $date = date('d.m.Y H:i', strtotime($shift['opened_at']));
            }
            if ($shift['closed_at']) {
                $date = date('d.m.Y H:i', strtotime($shift['closed_at']));
            }

            $balance = number_format(substr($shift['balance']->balance, 0, -2), 0, '', ' ') . '.' . substr($shift['balance']->balance, -2);

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


        return [$shift_id, $is_connected, $status, $balance];
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'extension/module/checkbox')) {
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
