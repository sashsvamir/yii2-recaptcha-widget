<?php
/**
 * @link https://github.com/sashsvamir/yii2-recaptcha-widget
 * @copyright Copyright (c) 2018 sashsvamir
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace sashsvamir\yii2\recaptcha;

/**
 * Ignoring recaptcha in tests (now is working in functional tests)
 * in unit tests will be always pass captcha
 * in acceptance tests always will be error because unchecking recaptcha
 * @inheritdoc
 */
class ReCaptchaValidator extends \himiklab\yii2\recaptcha\ReCaptchaValidator
{
	/**
	 * @inheritdoc
	 */
	protected function validateValue($value)
	{
		// pass tests captcha
		if (YII_ENV_TEST) {
			return null;
		}
		return parent::validateValue($value);
	}

}
