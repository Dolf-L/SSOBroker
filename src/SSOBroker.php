<?php
namespace Dolf\SSO;

class SSOBroker
{
    /**
     * Url of broker
     * @var array
     */
    protected $brokerUrl;

    /**
     * API key
     * @var mixed
     */
    protected $apiKey;


    /**
     * SSOBroker constructor.
     * @param $brokerUrl
     * @param $apiKey
     */
    public function __construct($brokerUrl, $apiKey)
    {
        $this->brokerUrl   = $brokerUrl;
        $this->apiKey      = $apiKey;
    }

    /**
     * Check if signature of server
     *
     * @param $serverSignature
     * @param $multipass
     * @return bool
     */
    public function checkSignature($serverSignature, $multipass)
    {
        $multipass = urldecode($multipass);
        // Build an HMAC-SHA1 signature using the encoded string and your api key
        $signature = hash_hmac("sha1", $multipass, $this->apiKey, true);

        // Base64 encode the signature
        $signature = base64_encode($signature);

        // Finally, URL encode the multipass and signature
        $signature = urlencode($signature);

        if ($serverSignature === $signature) {
            return true;
        }

        return false;
    }

    /**
     * Decrypt data
     *
     * @param $request
     * @return mixed
     */
    public function decryptData($request)
    {
        $salted = $this->apiKey . $this->brokerUrl;
        $digest = hash('sha1', $salted, true);
        $key    = substr($digest, 0, 16);

        $urldecoded = urldecode($request);

        $base64Decode = base64_decode($urldecoded);

        $iv = substr($base64Decode, 0, 16);
        $encryptedData = substr($base64Decode, 16);

        $cypher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');

        mcrypt_generic_init($cypher, $key, $iv);
        $decrypted = mdecrypt_generic($cypher, $encryptedData);
        mcrypt_generic_deinit($cypher);
        mcrypt_module_close($cypher);

        $trimmed = rtrim($decrypted , "\x00..\x1F");

        $jsonDecoded =  json_decode($trimmed);

        return $jsonDecoded;
    }
}