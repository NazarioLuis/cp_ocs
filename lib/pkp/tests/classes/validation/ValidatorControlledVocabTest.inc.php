<?php

/**
 * @file tests/classes/validation/ValidatorControlledVocabTest.inc.php
 *
 * Copyright (c) 2000-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class ValidatorControlledVocabTest
 * @ingroup tests_classes_validation
 * @see ValidatorControlledVocab
 *
 * @brief Test class for ValidatorControlledVocab.
 */

import('tests.PKPTestCase');
import('validation.ValidatorControlledVocab');
import('controlledVocab.ControlledVocab');

class ValidatorControlledVocabTest extends PKPTestCase {
	/**
	 * @covers ValidatorControlledVocab
	 */
	public function testValidatorControlledVocab() {
		// Mock a ControlledVocab object
		$mockControlledVocab = $this->getMock('ControlledVocab', array('enumerate'));
		$mockControlledVocab->setId(1);
		$mockControlledVocab->setAssocType(ASSOC_TYPE_CITATION);
		$mockControlledVocab->setAssocId(333);
		$mockControlledVocab->setSymbolic('testVocab');

	    // Set up the mock enumerate() method
		$mockControlledVocab->expects($this->any())
		                    ->method('enumerate')
		                    ->will($this->returnValue(array(1 => 'vocab1', 2 => 'vocab2')));

		// Mock the ControlledVocabDAO
		$mockControlledVocabDAO = $this->getMock('ControlledVocabDAO', array('getBySymbolic'));

	    // Set up the mock getBySymbolic() method
		$mockControlledVocabDAO->expects($this->any())
		                       ->method('getBySymbolic')
		                       ->with('testVocab', ASSOC_TYPE_CITATION, 333)
		                       ->will($this->returnValue($mockControlledVocab));

		DAORegistry::registerDAO('ControlledVocabDAO', $mockControlledVocabDAO);

		$validator = new ValidatorControlledVocab('testVocab', ASSOC_TYPE_CITATION, 333);
		self::assertTrue($validator->isValid('1'));
		self::assertTrue($validator->isValid('2'));
		self::assertFalse($validator->isValid('3'));
	}
}
?>