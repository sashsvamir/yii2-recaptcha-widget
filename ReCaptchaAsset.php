<?php
/**
 * @link https://github.com/sashsvamir/yii2-recaptcha-widget
 * @copyright Copyright (c) 2018 sashsvamir
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace sashsvamir\yii2\recaptcha;

use Yii;
use yii\web\AssetBundle;


/**
 * ReCaptchaAsset
 * @inheritdoc
 */
class ReCaptchaAsset extends AssetBundle
{
	public $sourcePath = '@sashsvamir/yii2/recaptcha/dist';
	public $js = [
		[
			'recaptcha.min.js',
			'position' => \yii\web\View::POS_HEAD,
			'key' => 'recaptcha_script_unique_key',
		],
	];

	public $depends = [
	];

}
