<?php

class DES
{ 
    protected $method;
    protected $key;
    protected $output;
    protected $iv; //вектор шифрования и дешифрования
    protected $options;
 
         // тип вывода
    const OUTPUT_NULL = '';
    const OUTPUT_BASE64 = 'base64';
    const OUTPUT_HEX = 'hex';
 

    public function __construct($key, $method = 'DES-ECB', $output = '', $iv = '', $options = OPENSSL_RAW_DATA | OPENSSL_NO_PADDING)
    {
        $this->key = $key;
        $this->method = $method;
        $this->output = $output;
        $this->iv = $iv;
        $this->options = $options;
    }
 
    // Шифрование
     
    public function encrypt($str)
    {
        $str = $this->pkcsPadding($str, 8);
        $sign = openssl_encrypt($str, $this->method, $this->key, $this->options, $this->iv);
 
        if ($this->output == self::OUTPUT_BASE64) {
            $sign = base64_encode($sign);
        } else if ($this->output == self::OUTPUT_HEX) {
            $sign = bin2hex($sign);
        }
 
        return $sign;
    }
 
    // Расшифровать

    public function decrypt($encrypted)
    {
        if ($this->output == self::OUTPUT_BASE64) {
            $encrypted = base64_decode($encrypted);
        } else if ($this->output == self::OUTPUT_HEX) {
            $encrypted = hex2bin($encrypted);
        }
 
        $sign = @openssl_decrypt($encrypted, $this->method, $this->key, $this->options, $this->iv);
        $sign = $this->unPkcsPadding($sign);
        $sign = rtrim($sign);
        return $sign;
    }
 
    //Заполнить

    private function pkcsPadding($str, $blocksize)
    {
        $pad = $blocksize - (strlen($str) % $blocksize);
        return $str . str_repeat(chr($pad), $pad);
    }
 
    // Перейти к заполнению
    private function unPkcsPadding($str)
    {
        $pad = ord($str{strlen($str) - 1});
        if ($pad > strlen($str)) {
            return false;
        }
        return substr($str, 0, -1 * $pad);
    }
 
}
 
$key = 'key123456';
$iv = 'iv123456';
 
 // Шифрование и дешифрование DES CBC
$des = new DES($key, 'DES-CBC', DES::OUTPUT_BASE64, $iv);
echo $base64Sign = $des->encrypt('Hello DES CBC');
echo "\n";
echo $des->decrypt($base64Sign);
echo "\n";
 
 // Шифрование и дешифрование DES ECB
$des = new DES($key, 'DES-ECB', DES::OUTPUT_HEX);
echo $base64Sign = $des->encrypt('Hello DES ECB');
echo "\n";
echo $des->decrypt($base64Sign);
 