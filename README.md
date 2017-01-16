# droide-recaptcha V 0.0.1
Plugin Recaptcha V2.

## How to use

* Set class instance DroideRecaptcha with your site_key and secret_key
* Generate keys in [link recapcha](https://www.google.com/recaptcha/admin#list)

```php
<?php
include_once ('droide-recaptcha.v2.php');
$recaptcha = new DroideRecaptcha('site_key', 'secret_key');

```
* Apply in html
````php
 <form class="" action="" method="post">
<?=$recaptcha->html() ?>
</form>
````

* After the submit validate Captcha

````php
<?php
include_once ('droide-recaptcha.v2.php');
$recaptcha = new DroideRecaptcha('site_key', 'secret_key');
$response = 0;
if(isset($_POST['g-recaptcha-response']))
{
  $response = $recaptcha->response($_POST['g-recaptcha-response']);
}

echo ($response->succcess)?'Ok':'Error';

````

*** Important The google uses the post g-recaptcha-response so always check the $_POST['g-recaptcha-response'] ***
