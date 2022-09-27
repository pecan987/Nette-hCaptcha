<?php declare(strict_types = 1);

namespace Contributte\HCaptcha\DI;

use Contributte\HCaptcha\Forms\InvisibleHCaptchaBinding;
use Contributte\HCaptcha\Forms\HCaptchaBinding;
use Contributte\HCaptcha\HCaptchaProvider;
use Nette\DI\CompilerExtension;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Expect;
use Nette\Schema\Schema;

final class HCaptchaExtension extends CompilerExtension
{

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'siteKey' => Expect::string()->required(),
			'secretKey' => Expect::string()->required(),
		]);
	}

	/**
	 * Register services
	 */
	public function loadConfiguration(): void
	{
		$config = (array) $this->getConfig();
		$builder = $this->getContainerBuilder();

		$builder->addDefinition($this->prefix('provider'))
			->setFactory(HCaptchaProvider::class, [$config['siteKey'], $config['secretKey']]);
	}

	/**
	 * Decorate initialize method
	 */
	public function afterCompile(ClassType $class): void
	{
		$method = $class->getMethod('initialize');
		$method->addBody(sprintf('%s::bind($this->getService(?));', HCaptchaBinding::class), [$this->prefix('provider')]);
	}

}
