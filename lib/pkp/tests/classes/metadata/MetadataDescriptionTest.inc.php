<?php

/**
 * @file tests/classes/metadata/MetadataDescriptionTest.inc.php
 *
 * Copyright (c) 2000-2012 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class MetadataDescriptionTest
 * @ingroup tests_classes_metadata
 * @see MetadataDescription
 *
 * @brief Test class for MetadataDescription.
 */

import('tests.PKPTestCase');
import('metadata.MetadataDescription');
import('tests.classes.metadata.TestSchema');

class MetadataDescriptionTest extends PKPTestCase {
	private $metadataDescription;
	private static
		$testStatements = array(
			array('not-translated-one', 'nto', null),

			array('not-translated-many', 'ntm1', null),
			array('not-translated-many', 'ntm2', null),

			array('translated-one', 'to_en', 'en_US'),
			array('translated-one', 'to_de', 'de_DE'),

			array('translated-many', 'tm1_en', 'en_US'),
			array('translated-many', 'tm1_de', 'de_DE'),
			array('translated-many', 'tm2_en', 'en_US'),
			array('translated-many', 'tm2_de', 'de_DE')
		),
		$testStatementsData = array (
			'not-translated-one' => 'nto',
			'not-translated-many' => array (
				0 => 'ntm1',
				1 => 'ntm2'
			),
			'translated-one' => array (
				'en_US' => 'to_en',
				'de_DE' => 'to_de'
			),
			'translated-many' => array (
				'en_US' => array (
					0 => 'tm1_en',
					1 => 'tm2_en'
				),
				'de_DE' => array (
					0 => 'tm1_de',
					1 => 'tm2_de'
				)
			)
  		);

	protected function setUp() {
		$metadataSchema = new TestSchema();
		$this->metadataDescription = new MetadataDescription($metadataSchema, ASSOC_TYPE_CITATION);
	}

	/**
	 * @covers MetadataDescription::addStatement
	 */
	public function testAddStatement() {
		foreach (self::$testStatements as $test) {
			$this->metadataDescription->addStatement($test[0], $test[1], $test[2]);
		}

		self::assertEquals(self::$testStatementsData, $this->metadataDescription->getAllData());
	}

	public function testSetStatements() {
		$testStatements = self::$testStatementsData;
		$this->metadataDescription->setStatements($testStatements);
		self::assertEquals($testStatements, $this->metadataDescription->getAllData());

		$testStatements = array (
			'not-translated-one' => 'nto-new',
			'not-translated-many' => array (
				0 => 'ntm1-new',
				1 => 'ntm2-new'
			)
  		);

  		// Trying to replace a property with METADATA_DESCRIPTION_REPLACE_NOTHING
  		// should provoke an error.
  		$previousData = self::$testStatementsData;
		self::assertFalse($this->metadataDescription->setStatements($testStatements, METADATA_DESCRIPTION_REPLACE_NOTHING));
  		self::assertEquals($previousData, $this->metadataDescription->getAllData());

		// Unset the offending property and try again - this should work
  		$previousData = self::$testStatementsData;
  		unset($previousData['not-translated-one']);
		$this->metadataDescription->setAllData($previousData);
		$expectedResult = self::$testStatementsData;
  		$expectedResult['not-translated-many'][] = 'ntm1-new';
  		$expectedResult['not-translated-many'][] = 'ntm2-new';
		unset($expectedResult['not-translated-one']);
  		$expectedResult['not-translated-one'] = 'nto-new';
  		self::assertTrue($this->metadataDescription->setStatements($testStatements, METADATA_DESCRIPTION_REPLACE_NOTHING));
  		self::assertEquals($expectedResult, $this->metadataDescription->getAllData());

  		// Using the default replacement level (METADATA_DESCRIPTION_REPLACE_PROPERTY)
  		$previousData = self::$testStatementsData;
  		$this->metadataDescription->setAllData($previousData);
  		unset($expectedResult['not-translated-many']);
  		$expectedResult['not-translated-many'] = array('ntm1-new', 'ntm2-new');
		self::assertTrue($this->metadataDescription->setStatements($testStatements));
  		self::assertEquals($expectedResult, $this->metadataDescription->getAllData());

  		// Now test METADATA_DESCRIPTION_REPLACE_ALL
  		self::assertTrue($this->metadataDescription->setStatements($testStatements, METADATA_DESCRIPTION_REPLACE_ALL));
  		self::assertEquals($testStatements, $this->metadataDescription->getAllData());

  		// Test that an error in the test statements maintains the previous state
  		// of the description.
  		// 1) Set some initial state (and make a non-referenced copy for later comparison)
  		$previousData = array('non-translated-one' => 'previous-value');
  		$previousDataCopy = $previousData;
  		$this->metadataDescription->setAllData($previousData);
  		// 2) Create invalid test statement
  		$testStatements['non-existent-property'] = 'some-value';
  		// 3) Make sure that the previous data will always be restored when
  		//    an error occurs.
  		self::assertFalse($this->metadataDescription->setStatements($testStatements));
  		self::assertEquals($previousDataCopy, $this->metadataDescription->getAllData());
  		self::assertFalse($this->metadataDescription->setStatements($testStatements, true));
  		self::assertEquals($previousDataCopy, $this->metadataDescription->getAllData());
	}
}
?>