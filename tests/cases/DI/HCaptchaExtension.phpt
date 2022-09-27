<?php declare(strict_types = 1);

namespace Tests\Cases\DI;

/**
 * Test: HCaptchaExtension
 */

use Contributte\HCaptcha\DI\HCaptchaExtension;
use Contributte\HCaptcha\HCaptchaProvider;
use Nette\DI\Compiler;
use Nette\DI\ContainerLoader;
use Nette\DI\InvalidConfigurationException;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

test(function () {
	$loader = new ContainerLoader(TEMP_DIR);
	$class = $loader->load(function (Compiler $compiler) {
		$compiler->addExtension('captcha', new HCaptchaExtension());

		$compiler->addConfig([
			'captcha' => [
				'siteKey' => 'foobar',
				'secretKey' => 'foobar2',
			],
		]);
	}, 'SC' . time());

	$container = new $class();
	Assert::type(HCaptchaProvider::class, $container->getByType(HCaptchaProvider::class));
	Assert::equal('foobar', $container->getByType(HCaptchaProvider::class)->getSiteKey());
});

test(function () {
	Assert::exception(function () {
		$loader = new ContainerLoader(TEMP_DIR);
		$loader->load(function (Compiler $compiler) {
			$compiler->addExtension('captcha', new HCaptchaExtension());
			$compiler->addConfig([
				'captcha' => [
					'siteKey' => 'foobar',
				],
			]);
		}, 'SC2' . time());
	}, InvalidConfigurationException::class, 'The mandatory option \'captcha › secretKey\' is missing.');
});
