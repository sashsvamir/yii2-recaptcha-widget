<?php
/**
 * @link https://github.com/sashsvamir/yii2-recaptcha-widget
 * @copyright Copyright (c) 2018 sashsvamir
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace sashsvamir\yii2\recaptcha;

use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\Html;
use yii\helpers\Inflector;


/**
 * ReCaptcha widget by sashsvamir
 * thanks to himiklab
 * @inheritdoc
 */
class ReCaptcha extends \himiklab\yii2\recaptcha\ReCaptcha
{
	const JS_API_URL = 'https://www.google.com/recaptcha/api.js';

	/**
	 * @inheritdoc
	 */
	public function init()
	{
		parent::init();

		if (empty($this->siteKey)) {
			/** @var ReCaptcha $reCaptcha */
			$reCaptcha = Yii::$app->reCaptcha;
			if (!empty($reCaptcha->siteKey)) {
				$this->siteKey = $reCaptcha->siteKey;
			} else {
				throw new InvalidConfigException('Required `siteKey` param isn\'t set.');
			}
		}
	}

	/**
	 * @inheritdoc
	 */
	public function run()
	{
		$view = $this->view;

		$view->registerJsFile(
			self::JS_API_URL . $this->getSuffix(),
			['position' => $view::POS_END, 'async' => true, 'defer' => true]
		);

		// prepare explicitly render captcha
		$this->onloadCallbackPrepare();

		$this->customFieldPrepare();

		echo Html::tag('div', '', $this->getDivOptions());
	}

	/**
	 * Generate recaptcha element attributes
	 * @return array
	 */
	protected function getDivOptions()
	{
		$divOptions = [
			'class' => 'g-recaptcha',
			'data-sitekey' => $this->siteKey,
			'data-captcha-id' => $this->options['id'],
			// recaptcha params
			'data-callback' => $this->jsCallback ?: null,
			'data-expired-callback' => $this->jsExpiredCallback ?: null,
			'data-theme' => $this->theme ?: null,
			'data-type' => $this->type ?: null,
			'data-size' => $this->size ?: null,
			'data-tabindex' => $this->tabindex ?: null,
		];

		if (isset($this->widgetOptions['class'])) {
			$divOptions['class'] = "{$divOptions['class']} {$this->widgetOptions['class']}";
		}

		return $divOptions = $divOptions + $this->widgetOptions;
	}

	/**
	 * Generate url params when explicit render captcha
	 * @return null|string
	 */
	protected function getSuffix()
	{
		return '?=' . http_build_query([
			'hl' => $this->getLanguageSuffix(),
			'render' => 'explicit',
			'onload' => 'recaptchaOnloadCallback',
		]);
	}

	/**
	 * Prepare explicit render captcha
	 * @throws InvalidConfigException
	 */
	protected function onloadCallbackPrepare()
	{
		$view = $this->view;

		// register js with recaptchaCallback and recaptchasStorage
		$view->registerAssetBundle(ReCaptchaAsset::className());

		// call recaptchaCallback (usally exetutes when is ajax)
		$view->registerJs(/** @lang JavaScript */"
			'use strict';
			// register field as recaptcha
			recaptchaOnloadCallback( document.querySelector('.g-recaptcha[data-captcha-id={$this->options['id']}]') );
		", $view::POS_READY);
	}

	/**
	 * @inheritdoc
	 */
	protected function customFieldPrepare()
	{
		$captchaId = Inflector::id2camel($this->options['id']);

		$view = $this->view;
		if ($this->hasModel()) {
			$inputName = Html::getInputName($this->model, $this->attribute);
			$inputId = Html::getInputId($this->model, $this->attribute);
		} else {
			$inputName = $this->name;
			$inputId = 'recaptcha-' . $this->name;
		}

		$jsCallbackName = $captchaId . 'Callback';
		if (empty($this->jsCallback)) {
			$jsCode = "var {$jsCallbackName} = function(response){ jQuery('#{$inputId}').val(response); };";
		} else {
			$jsCode = "var {$jsCallbackName} = function(response){ jQuery('#{$inputId}').val(response); {$this->jsCallback}(response); };";
		}
		$this->jsCallback = $jsCallbackName;

		$jsCallbackExpName = $captchaId . 'ExpiredCallback';
		if (empty($this->jsExpiredCallback)) {
			$jsExpCode = "var {$jsCallbackExpName} = function(){ jQuery('#{$inputId}').val(''); };";
		} else {
			$jsExpCode = "var {$jsCallbackExpName} = function(){ jQuery('#{$inputId}').val(''); {$this->jsExpiredCallback}(); };";
		}
		$this->jsExpiredCallback = $jsCallbackExpName;

		$view->registerJs($jsCode, $view::POS_BEGIN);
		$view->registerJs($jsExpCode, $view::POS_BEGIN);

		echo Html::input('hidden', $inputName, null, ['id' => $inputId]);
	}

}
