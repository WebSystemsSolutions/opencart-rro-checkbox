<?php

class ModelExtensionPaymentCheckbox extends Model
{

    private $login;

    private $password;

    private $cashbox_key;

    private $is_dev;

    private $access_token = '';

    public function __construct($registry)
    {
        parent::__construct($registry);

        $this->login = $this->config->get('checkbox_rro_login');
        $this->password = $this->config->get('checkbox_rro_password');
        $this->cashbox_key = $this->config->get('checkbox_rro_cashbox_key');
        $this->is_dev = $this->config->get('checkbox_rro_is_dev');
        $this->getBearToken();
    }


    public function getBearToken()
    {
        $params = ['login' => $this->login, 'password' => $this->password];
        $response = $this->makePostRequest('/api/v1/cashier/signin', $params);

        $this->access_token = $response['access_token'] ? $response['access_token'] : '';
    }

    public function connect()
    {
        $cashbox_key = $this->cashbox_key;
        $header_params = ['cashbox_key' => $cashbox_key];
        return $this->makePostRequest('/api/v1/shifts', [], $header_params);
    }

    public function disconnect()
    {
        return $this->makePostRequest('/api/v1/shifts/close');
    }

    public function getShifts()
    {
        $url = '/api/v1/shifts?desc=true';
        return $this->makeGetRequest($url);
    }

    public function getCurrentCashierShift()
    {
        $url = '/api/v1/cashier/shift';
        return $this->makeGetRequest($url);
    }

    public function getCurrentCashboxInfo()
    {
        $url = '/api/v1/cash-registers/info';
        $header_params = ['cashbox_key' => $this->cashbox_key];
        return $this->makeGetRequest($url, [], $header_params);
    }

    public function checkConnection($shift_id)
    {
        $url = '/api/v1/shifts/' . $shift_id;
        return $this->makeGetRequest($url);
    }

    public function getZReports()
    {
        $url = '/api/v1/reports/?is_z_report=true';
        return $this->makeGetRequest($url);
    }

    public function getReportText($report_id)
    {
        $url = '/api/v1/reports/' . $report_id . '/text/';
        return $this->makeGetRequest($url, [], [], true);
    }

    public function getReceiptHtml($receipt_id)
    {
        $url = '/api/v1/receipts/' . $receipt_id . '/html/';
        return $this->makeGetRequest($url, [], [], true);
    }

    public function getReceipt($receipt_id)
    {
        $url = '/api/v1/receipts/' . $receipt_id;
        return $this->makeGetRequest($url);
    }

    public function createServiceReceipt($cash)
    {
        $params = [
            'payment' => [
                'type'  => 'CASH',
                'value' => $cash,
            ]
        ];
        return $this->makePostRequest('/api/v1/receipts/service', $params);
    }

    public function create_receipt($params)
    {
        return $this->makePostRequest('/api/v1/receipts/sell', $params);
    }


    private function makePostRequest($route, $params = [], $header_params = [])
    {
        $url_host = $this->is_dev ? 'https://dev-api.checkbox.in.ua' : 'https://api.checkbox.in.ua';
        $url = $url_host . $route;

        $header = ['Content-type' => 'application/json'];

        if ($this->access_token) {
            $header = array_merge($header, ['Authorization: Bearer ' . trim($this->access_token)]);
        }

        if (isset($header_params['cashbox_key'])) {
            $header = array_merge($header, ['X-License-Key: ' . $header_params['cashbox_key']]);
        }

        $header = array_merge($header, ['X-Client-Name: shhygolvv_khm']);
        $header = array_merge($header, ['X-Client-Version: 1']);

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => json_encode($params),
            CURLOPT_HTTPHEADER     => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        return isset($response) ? (array)json_decode($response) : [];
    }

    private function makeGetRequest($route, $params = [], $header_params = [], $echo = false)
    {
        $url_host = $this->is_dev ? 'https://dev-api.checkbox.in.ua' : 'https://api.checkbox.in.ua';
        $url = $url_host . $route;

        $header = ['Content-type' => 'application/json'];
        if ($this->access_token) {
            $header = array_merge($header, ['Authorization: Bearer ' . trim($this->access_token)]);
        }

        if (isset($header_params['cashbox_key'])) {
            $header = array_merge($header, ['X-License-Key: ' . $header_params['cashbox_key']]);
        }

        $header = array_merge($header, ['X-Client-Name: shhygolvv_khm']);
        $header = array_merge($header, ['X-Client-Version: 1']);

        if ($params) {
            $params = http_build_query($params);
        } else {
            $params = '';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => $header,
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        if ($echo) {
            return $response;
        } else {
            return isset($response) ? (array)json_decode($response) : [];
        }

    }


    public function getOrder($order_id)
    {
        $order_query = $this->db->query("SELECT *, (SELECT CONCAT(c.firstname, ' ', c.lastname) FROM " . DB_PREFIX . "customer c WHERE c.customer_id = o.customer_id) AS customer, (SELECT os.name FROM " . DB_PREFIX . "order_status os WHERE os.order_status_id = o.order_status_id AND os.language_id = '" . (int)$this->config->get('config_language_id') . "') AS order_status FROM `" . DB_PREFIX . "order` o WHERE o.order_id = '" . (int)$order_id . "'");

        return $order_query->row;
    }


}
