<?php

/**
 * Aes encryption
 * @author Máximo Sojo <maxsojo13@gmail.com>
 */
class AES
{  
    protected $key;
    protected $data;
    protected $method;

    /**
     * Available OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING
     *
     * @var type $options
     */
    protected $options = 0;

    /**
     * 
     * @param type $data
     * @param type $key
     * @param type $blockSize
     * @param type $mode
     */
    function __construct($key = null, $blockSize = 128, $mode = 'ECB')
    {
        $this->setKey($key);
        $this->setMethode($blockSize, $mode);
    }
    
    /**
     * 
     * @param type $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }
    
    /**
     * 
     * @param type $key
     */
    public function setKey($key)
    {
        $this->key = $key;
    }

    /**
     * CBC 128 192 256 
      CBC-HMAC-SHA1 128 256
      CBC-HMAC-SHA256 128 256
      CFB 128 192 256
      CFB1 128 192 256
      CFB8 128 192 256
      CTR 128 192 256
      ECB 128 192 256
      OFB 128 192 256
      XTS 128 256
     * @param type $blockSize
     * @param type $mode
     */
    public function setMethode($blockSize, $mode = 'CBC')
    {
        if($blockSize==192 && in_array('', array('CBC-HMAC-SHA1','CBC-HMAC-SHA256','XTS'))){
            $this->method=null;
             throw new Exception('Invalid block size and mode combination!');
        }
        $this->method = 'AES-' . $blockSize . '-' . $mode;
    }

    /**
     * 
     * @return boolean
     */
    public function validateParams()
    {
        if ($this->data != null &&
                $this->method != null ) {
            return true;
        } else {
            return FALSE;
        }
    }

    /**
     * @return type
     * @throws Exception
     */
    public function encrypt($data = null)
    {
        if ($this->validateParams()) {
            if (!$data) {
                $data = $this->data;
            }
            $key = openssl_digest($this->key,"sha256",true);
            $key = substr($key,0,16);
            return base64_encode(openssl_encrypt($data, $this->method, $key, OPENSSL_RAW_DATA));
        } else {
            throw new Exception('Invlid params!');
        }
    }
    /**
     * 
     * @return type
     * @throws Exception
     */
    public function decrypt($data = null)
    {
        if ($this->validateParams()) {
            if (!$data) {
                $data = $this->data;
            }
            $key = openssl_digest($this->key,"sha256",true);
            $key = substr($key,0,16);

            $ret = openssl_decrypt(base64_decode($data), $this->method, $key, OPENSSL_RAW_DATA);
          
            return $ret; 
        } else {
            throw new Exception('Invlid params!');
        }
    }
}