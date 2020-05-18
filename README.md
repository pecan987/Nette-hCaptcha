# hCaptcha

hCaptcha implementation for [Nette Framework](https://github.com/nette/forms) forms.

Forked from [contributte/reCAPTCHA](https://github.com/contributte/reCAPTCHA)

-----

## Pre-installation

Add your site to the site list in the [hCaptcha dashboard](https://dashboard.hcaptcha.com/sites).

## hCaptcha in action
![hCaptcha in action](https://i.imgur.com/mAmpQPX.gif)

## Installation

The latest version is most suitable for **Nette 3.0** and **PHP >=7.1**.

```sh
composer require vottuscode/hcaptcha
```

## Configuration

```yaml
extensions:
    recaptcha: Contributte\ReCaptcha\DI\ReCaptchaExtension

recaptcha:
    secretKey: "your_hcaptcha_secret_key"
    siteKey: your_hcaptcha_site_key
```
(Be sure to include quotes for the secretKey)

## Usage
(It is the same as hCaptcha, there is no change whatsoever in classes to prevent BC breaks
)
```php
use Nette\Application\UI\Form;

protected function createComponentForm()
{
    $form = new Form();

    $form->addReCaptcha('hcaptcha', $label = 'Captcha')
        ->setMessage('Are you a bot?');

    $form->addReCaptcha('hcaptcha', $label = 'Captcha', $required = FALSE)
        ->setMessage('Are you a bot?');

    $form->addReCaptcha('hcaptcha', $label = 'Captcha', $required = TRUE, $message = 'Are you a bot?');

    $form->onSuccess[] = function($form) {
        Debugger::barDump($form->getValues());
    };
}
```

## Rendering

```latte
<form n:name="myForm">
	<div class="form-group">
		<div n:name="recaptcha"></div>
	</div>
</form>
```

Be sure to place this script before the closing tag of the `body` element (`</body>`).

```html
<!-- re-Captcha -->
<script src="https://hcaptcha.com/1/api.js" async defer></script>
```
