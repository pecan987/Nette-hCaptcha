<?php declare(strict_types = 1);

namespace Tests\Cases;

/**
 * Test: HCaptchaResponse
 */

use Contributte\HCaptcha\HCaptchaResponse;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

test(function () {
	$response = new HCaptchaResponse(true);
	Assert::true($response->isSuccess());
});

test(function () {
	$response = new HCaptchaResponse(true);
	Assert::equal('1', (string) $response);
});

test(function () {
	$error = 'Some error';
	$response = new HCaptchaResponse(false, $error);
	Assert::false($response->isSuccess());
	Assert::equal($error, $response->getError());
});
