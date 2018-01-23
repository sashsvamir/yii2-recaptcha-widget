# ReCaptcha Widget

Widget to render [google recaptcha](https://developers.google.com/recaptcha/) and validate this field.
This implementaion allow to use multiple widgets on one page, also you can load page by ajax several times.


![recaptcha](https://developers.google.com/recaptcha/images/light.png)



### Information

Now this widget extends [himiklab/yii2-recaptcha-widget](https://github.com/himiklab/yii2-recaptcha-widget) extension
(automatic loading as depends in composer.json)




Installation
------------------
Just add extension to composer require section:

`composer require sashsvamir/yii2-recaptcha-widget:"dev-master"`




Setup
------------------

Setup recaptcha config `common/config/main.php`:
```php
'components' => [
    // ...
    'reCaptcha' => [
        'name' => 'reCaptcha',
        'class' => 'sashsvamir\yii2\recaptcha\ReCaptcha',
        'siteKey' => '<your site public key>',
        'secret' => '<your site private key>',
    ],
],
```

To debug, use follow keys:
```php
'siteKey' => '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI',
'secret' => '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe',
```
see: https://developers.google.com/recaptcha/docs/faq





Using
------------------
Add ReCaptchaValidator in your model, for example:
```php
public $verifyCode;

public function rules()
{
  return [
    // ...
    ['verifyCode', ReCaptchaValidator::className(), 'uncheckedMessage' => 'Please confirm that you are not a bot.',
      // add follow lines to prevent checking recaptcha when from has errors 
      'when' => function ($model) {
        return !$model->hasErrors();
      }
    ],
  ];
}
```



### Render ReCaptcha field:


```php
$form->field($model, 'verifyCode')->widget(\sashsvamir\yii2\recaptcha\ReCaptcha::className())->label(false);
```


