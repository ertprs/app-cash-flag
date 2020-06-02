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
    
    public function __construct(array $parameters)
    {
        $this->apiKey = $parameters['api_key'];
        $this->environment = $parameters['environment'];
        $this->xIbmClientId = $parameters['x_ibm_client_id'];
        $this->integratorId = $parameters['integrator_id'];
        $this->merchantId = $parameters['merchant_id'];
        $this->terminalId = $parameters['terminal_id'];

        $this->headers = [
            "Content-Type: application/json",
            "x-ibm-client-id: ".$this->xIbmClientId,
            "accept: application/json",
            "Environment: ".$this->environment,
            "ApiKey: ".$this->apiKey
        ];
    }
    
    public function getAuth(Request $request, $entity) 
    {
        $success = true;
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

        return $this->response;
    }
}