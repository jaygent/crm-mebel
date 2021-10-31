<?php

namespace application\lib;


class Chatapi
{
    protected $token = '';
    protected $url = 'https://eu22.chat-api.com//';
    protected $instance_url = 'https://eu22.chat-api.com//';
    protected $instance_key = '';
    protected $_mem = [];

    /**
     * ChatApi constructor.
     * @param $token
     * @param string $url
     */


    /**
     * Construct query URL
     * @param $method
     * @param array $args
     * @return string
     */
    public function createUrl($method, $args = [])
    {
        $args['token'] = $this->token;
        return $this->url.'/'.$method.'?'.http_build_query($args);
    }

    /**
     * Send chat-api query
     * @param string $method
     * @param null|array $args
     * @param string $qmethod
     * @return bool|string
     */
    public function query($method, $args = null, $qmethod = 'GET')
    {
        $url = $this->createUrl($method);

        if($qmethod == "POST" && isset($args) && is_array($args)) {
            $json = json_encode($args);

            $options = stream_context_create(['http' => [
                'method' => $qmethod,
                'header' => 'Content-type: application/json',
                'content' => $json
            ]]);
        } elseif($qmethod == "GET" && isset($args) && is_array($args)) {
            $url = $this->createUrl($method, $args);

            $options = stream_context_create(['http' => [
                'method' => $qmethod,
                'header' => 'Content-type: application/json',
            ]]);
        }

        return file_get_contents($url, false, isset($options) ? $options : null);
    }

    /**
     * Get status (logged in / error / loading) for current instance
     * @return string
     */
    public function getStatus()
    {
        $js = json_decode($this->query('status',['full'=>'full']), 1);
        if ($js['accountStatus'] == 'authenticated') {
            return '<p style="color: green;">'.$js['statusData']['msg'].'</p>';
        } else {
            $js=$js['qrCode'];
            return "<p style='color: red;'>Требуется отсканировать QR Code И перезагрузить страницу.</p><p><img src='$js'></p>";
        }
    }
    /**
     * retry accaut if errr for current instance
     * @return string
     */
    public function retry()
    {
        $this->query('reboot');
    }

    /**
     * Send message to phone number
     * @param string $chat
     * @param string $text
     * @return boolean
     */
    public function sendPhoneMessage($chat, $text)
    {
        $js=json_decode($this->query('sendMessage', ['phone' => $chat, 'body' => $text],'POST'));
        return $js;
    }




    /**
     * Generate QR-code direct link
     * @return string
     */
    public function getQRCode()
    {
        return $this->createUrl('qr_code');
    }
    /**
     * /checkPhone whatsapp
     * @return string
     */
    public function checkPhone($phone)
    {
        $js=json_decode($this->query('checkPhone',['phone'=>$phone]),1);
        if($js['result']=='not exists'){
            return false;
        }else{
            return true;
        }
    }
    /**
     * Send file to chat
     * @param string $chat
     * @param string $body
     * @param string $filename
     * @return boolean
     */
    public function sendFile($chat, $body, $filename)
    {
        return json_decode($this->query('sendFile', ['phone' => $chat, 'filename' => $filename, 'body' => $body],'POST'));
    }

}
