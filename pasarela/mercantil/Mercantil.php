<?php

/**
 * Pasarela Llave Mercantil
 *
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class Mercantil
{
    protected $apiKey;
    protected $environment;
    protected $xIbmClientId;
    protected $integratorId;
    protected $merchantId;
    protected $terminalId;

    public function __construct()
    {
        $this->apiKey = $_ENV["APIBU_API_KEY"];
        $this->environment = $_ENV["APIBU_ENVIROMENT"];
        $this->xIbmClientId = $_ENV["APIBU_XIBM_CLIENT"];
        $this->integratorId = $_ENV["APIBU_INTEGRATOR_ID"];
        $this->merchantId = $_ENV["APIBU_MERCHANT_ID"];
        $this->terminalId = $_ENV["APIBU_TERMINAL_ID"];

        $this->headers = [
            "Content-Type: application/json",
            "x-ibm-client-id: ".$this->xIbmClientId,
            "accept: application/json",
            "Environment: ".$this->environment,
            "ApiKey: ".$this->apiKey
        ];
    }
    
    public function getAuth($data = []) 
    {
        $success = true;
        $parameters = [
            "merchant_identify" => [
                "integratorId" => $this->integratorId,
                "merchantId" => $this->merchantId,
                "terminalId" => $this->terminalId
            ],
            "client_identify" => [
                "ipaddress" => "127.0.0.1"
            ],
            "transaction_authInfo" => [
                "trx_type" => "solaut",
                "payment_method" => "tdd",
                "card_number" => $data["card_number"],
                "customer_id" => $data["customer_id"]
            ]
        ];

        $success = false;
        $url = "https://apimbu.mercantilbanco.com:9443/mercantil-banco/desarrollo/v1/payment/getauth";
        $response = $this->executePost($url, $parameters);
        var_dump($response);die;

        return $success;
    }

    public function createPayment() 
    {
        $success = true;
        return $success;
    }

    public function executePost($url, array $parameters)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_ENCODING, ""); 
        curl_setopt($ch, CURLOPT_MAXREDIRS, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 45); //timeout in seconds
        curl_setopt($ch, CURLOPT_PROXY, "");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);        
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($parameters));
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);

        $content = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);

        $response = new static($content, $statusCode);

        return $response;
    }
}