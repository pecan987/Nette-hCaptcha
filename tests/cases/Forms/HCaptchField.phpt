<?php declare(strict_types = 1);

namespace Tests\Cases\Forms;

/**
 * Test: HCaptchaField
 */

use Contributte\HCaptcha\Forms\HCaptchaField;
use Contributte\HCaptcha\HCaptchaProvider;
use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Form;
use Nette\Utils\Html;
use Tester\Assert;

require __DIR__ . '/../../bootstrap.php';

final class FormMock extends Form
{

	/**
	 * @return mixed
	 */
	public function getHttpData(?int $type = null, ?string $htmlName = null)
	{
		return $htmlName;
	}

}

test(function () {
	$field = new HCaptchaField(new HCaptchaProvider('foobar', 'secret'));
	Assert::equal(['g-HCaptcha' => true], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->addClass('foo');
	Assert::equal(['g-HCaptcha' => true, 'foo' => true], $field->getControlPrototype()->getClass());

	$field->getControlPrototype()->class('foobar');
	Assert::equal('foobar', $field->getControlPrototype()->getClass());
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$field = new HCaptchaField(new HCaptchaProvider('foobar', 'secret'));
	$form->addComponent($field, $fieldName);

	Assert::type(Html::class, $field->getControl());
	Assert::type(Html::class, $field->getLabel());
	Assert::equal(sprintf(BaseControl::$idMask, $form->getName() . '-' . $fieldName), $field->getHtmlId());
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$key = 'key';
	$field = new HCaptchaField(new HCaptchaProvider('key', 'secret'));
	$form->addComponent($field, $fieldName);

	Assert::equal($key, $field->getControl()->{'data-sitekey'});
});

test(function () {
	$form = new FormMock('form');

	$fieldName = 'captcha';
	$label = 'label';
	$field = new HCaptchaField(new HCaptchaProvider('key', 'secret'), $label);
	$form->addComponent($field, $fieldName);

	Assert::equal('', $field->getValue());
	Assert::same($label, $field->caption);

	$field->loadHttpData();
	Assert::equal(HCaptchaProvider::FORM_PARAMETER, $field->getValue());
});
