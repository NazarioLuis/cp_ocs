<?php

/**
 * @file tests/metadata/FormValidatorEmailTest.inc.php
 *
 * Copyright (c) 2000-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class FormValidatorEmailTest
 * @ingroup tests_classes_form_validation
 * @see FormValidatorEmail
 *
 * @brief Test class for FormValidatorEmail.
 */

import('tests.PKPTestCase');
import('form.Form');

class FormValidatorEmailTest extends PKPTestCase {
	/**
	 * @covers FormValidatorEmail
	 * @covers FormValidator
	 */
	public function testIsValid() {
		$form = new Form('some template');

		$form->setData('testData', 'some.address@gmail.com');
		$validator = new FormValidatorEmail($form, 'testData', FORM_VALIDATOR_REQUIRED_VALUE, 'some.message.key');
		self::assertTrue($validator->isValid());
		self::assertEquals(array('testData' => array('required', 'email')), $form->cssValidation);

		$form->setData('testData', 'anything else');
		$validator = new FormValidatorEmail($form, 'testData', FORM_VALIDATOR_REQUIRED_VALUE, 'some.message.key');
		self::assertFalse($validator->isValid());
	}
}
?>