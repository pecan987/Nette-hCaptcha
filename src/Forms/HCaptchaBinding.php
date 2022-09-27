<?php declare(strict_types = 1);

namespace Contributte\HCaptcha\Forms;

use Contributte\HCaptcha\HCaptchaProvider;
use Nette\Forms\Container;

final class HCaptchaBinding
{

	public static function bind(HCaptchaProvider $provider, string $name = 'addHCaptcha'): void
	{
		// Bind to form container
		Container::extensionMethod($name, function (Container $container, string $name = 'hcaptcha', string $label = 'HCaptcha', bool $required = true, ?string $message = null) use ($provider): HCaptchaField {
			$field = new HCaptchaField($provider, $label, $message);
			$field->setRequired($required);
			$container[$name] = $field;

			return $field;
		});
	}

}
