<?php
/**
 * Plugin Recaptcha v2.0
 * @version 0.0.1
 * @property string $site_key Public key of your google account
 * @property string $secret_key Private key of your google account
 * @property string $api_front Link from api from recapcha to the front (use for view form)
 * @property string $api_back Link from api from recapcha to the backend (use to send form)
 * @author André Luiz Pereira <and4563@gmail.com>
 */
class DroideRecaptcha {

public $site_key = '';
public $secret_key = '';
public $api_front = 'https://www.google.com/recaptcha/api.js';
public $api_back = 'https://www.google.com/recaptcha/api/siteverify';
public $error = [];

/**
 * Get keys the google account
 * @author André Luiz Pereira <andre@next4.com.br>
 * @param string $site_key - Public key of your google account
 * @param string $secret_key - Private key of your google account
 * @return void
 */
public function __construct($site_key = '', $secret_key = '')
{
  if(!$this->secret_key)
  {
    $this->secret_key = $secret_key;
  }

  if(!$this->site_key)
  {
    $this->site_key = $site_key;
  }

  if(!$this->site_key || !$this->secret_key)
  {
    $this->error[] = '<p style="color:#ff0000;">Invalid site_key or secret_key. see <a href="www.google.com/recaptcha/admin#list" target="_blank">Manage your reCAPTCHA API keys</a></p>';
  }

}

/**
 * Return Html ReCaptcha v2 for website frontend
 * @author André Luiz Pereira <andre@next4.com.br>
 * @return string - format html and script api.
 */
public function html()
{

  if(!$this->error){
  $html = <<<HTML
  <script src="{$this->api_front}" async defer></script>
  <div class="g-recaptcha" data-sitekey="{$this->site_key}" data-type="image" data-theme="light" ></div>
HTML;
}else{
  $html = implode('<br />', $this->error);
}

 return $html;

}

/**
 * Check Captcha after submit and return response of api.
 * @author André Luiz Pereira <andre@next4.com.br>
 * @param string $response - $_POST['g-recaptcha-response'] if your request is $_POST. Your cms may use different ways of using the post, such as joomla (JFactory::getApplication()->input->post('g-recaptcha-response','')).
 * @return object - return response google. check success. $response->success = true/false
 */
public function response($response)
{

  if(!$this->error){
    $fields_string = [];
    $send = [
      'secret'=>urlencode($this->secret_key),
      'response'=>urlencode($response),
      'remoteip'=>urlencode($_SERVER['REMOTE_ADDR']),
    ];

    foreach($send as $key=>$value) {
      $fields_string[] =  $key.'='.$value;
    }

    $fields_string = implode('&', $fields_string);

    $curl = curl_init($this->api_back);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($send));
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($curl);

    return json_decode($result);

  }else{
    return implode('<br />', $this->error);
    exit;
  }


}




}
