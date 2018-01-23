/**
 * @link https://github.com/sashsvamir/yii2-recaptcha-widget
 * @copyright Copyright (c) 2018 sashsvamir
 * @license http://opensource.org/licenses/MIT MIT
 */

(() => {

	class Storage {
		constructor() {
			this.apiLoaded = false;
			this.idList = {};
		}

		addRecaptcha(el) {
			const elementId = el.getAttribute('data-captcha-id');

			// destroy grecaptcha (if exist) and remove from ids list
			if (this.idList[elementId] !== undefined) {
				grecaptcha.reset(this.idList[elementId]);
				delete this.idList[elementId];
			}

			// render captcha and add to ids list
			const recaptchaId = this.renderRecaptcha(el);
			this.idList[elementId] = recaptchaId;
		}

		renderRecaptcha(el) {
			// render and return recaptcha id
			return grecaptcha.render(el, {
				'sitekey' : el.getAttribute('data-sitekey'),
				'callback': eval(el.getAttribute('data-callback')),
				'expired-callback': eval(el.getAttribute('data-expired-callback')),
				'theme': el.getAttribute('data-theme'),
				'type': el.getAttribute('data-type'),
				'size': el.getAttribute('data-size'),
				'tabindex': el.getAttribute('data-tabindex'),
			});
		}
	}


	// calling several times:
	// - on first loading recaptcha api js script
	// - later for every recaptcha field (also on ajax loading recaptcha)
	const recaptchaOnloadCallback = function(el) {

		console.log(recaptchasStorage);

		// prepare captcha elements array
		let els = [];

		if (arguments.length === 0) {
			// this was called by google (no arguments)
			els = document.querySelectorAll('.g-recaptcha');
			recaptchasStorage.apiLoaded = true;
		} else if (!recaptchasStorage.apiLoaded) {
			// if api script has been loading
			return false;
		} else {
			// this was called by field (with argument as captcha element)
			els.push(arguments[0]);
		}

		// render captchas and save id to global object
		for (let i = 0; i < els.length; i++) {
			recaptchasStorage.addRecaptcha(els[i]);
		}

		//console.log(recaptchasStorage.idList);
	}



	// add classed to global scope
	if (typeof window.recaptchasStorage === 'undefined') {
		window.recaptchasStorage = new Storage();
	}
	if (typeof window.recaptchaOnloadCallback === 'undefined') {
		window.recaptchaOnloadCallback = recaptchaOnloadCallback;
	}


})();
