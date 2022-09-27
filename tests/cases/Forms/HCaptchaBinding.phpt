<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

/**
 * Test: HCaptchaBinding
 */

use Contributte\HCaptcha\Forms\HCaptchaBinding;
use Contributte\HCaptcha\Forms\HCaptchaField;
use Contributte\HCaptcha\HCaptchaProvider;
use Nette\Forms\Form;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

test(function () {
	$provider = new HCaptchaProvider('foo', 'bar');
	HCaptchaBinding::bind($provider);

	$form = new Form();
	$HCaptcha = $form->addHCaptcha('HCaptcha');

	Assert::type(HCaptchaField::class, $HCaptcha);
	Assert::true($form->offsetExists('HCaptcha'));
	Assert::true($HCaptcha->isRequired());
	Assert::same('HCaptcha', $HCaptcha->getLabel()->getText());
	Assert::same('foo', $HCaptcha->getControl()->{'data-sitekey'});
});

test(function () {
	$provider = new HCaptchaProvider('foo', 'bar');
	HCaptchaBinding::bind($provider);

	$form = new Form();
	$HCaptcha = $form->addHCaptcha('HCaptcha', 'My label');
	Assert::same('My label', $HCaptcha->getLabel()->getText());
});

test(function () {
	$provider = new HCaptchaProvider('foo', 'bar');
	HCaptchaBinding::bind($provider);

	$form = new Form();
	$HCaptcha = $form->addHCaptcha('HCaptcha', 'My label', false);
	Assert::false($HCaptcha->isRequired());
});


test(function () {
	$provider = new HCaptchaProvider('foo', 'bar');
	HCaptchaBinding::bind($provider);

	$form = new Form();
	$HCaptcha = $form->addHCaptcha('HCaptcha', 'My label', false, 'Are you bot-bot?');
	Assert::false($HCaptcha->isRequired());
	$rules = $HCaptcha->getRules()->getIterator();
	$rule = end($rules);

	Assert::equal('Are you bot-bot?', $rule->message);
});
