<?php

/**
 * @file tests/metadata/FormValidatorInSetTest.inc.php
 *
 * Copyright (c) 2000-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class FormValidatorInSetTest
 * @ingroup tests_classes_form_validation
 * @see FormValidatorInSet
 *
 * @brief Test class for FormValidatorInSet.
 */

import('tests.PKPTestCase');
import('form.Form');

class FormValidatorInSetTest extends PKPTestCase {
	/**
	 * @covers FormValidatorInSet
	 * @covers FormValidator
	 */
	public function testIsValid() {
		$form = new Form('some template');

		// Instantiate test validator
		$acceptedValues = array('val1', 'val2');
		$validator = new FormValidatorInSet($form, 'testData', FORM_VALIDATOR_REQUIRED_VALUE, 'some.message.key', $acceptedValues);

		$form->setData('testData', 'val1');
		self::assertTrue($validator->isValid());

		$form->setData('testData', 'anything else');
		self::assertFalse($validator->isValid());
	}
}
?>