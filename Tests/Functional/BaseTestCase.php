<?php
/***************************************************************
 * Copyright notice
 *
 * (c) 2012 Oliver Hader <oliver.hader@typo3.org>
 * (c) 2009-2012 Jochen Rau <jochen.rau@typoplanet.de>
 * All rights reserved
 *
 * This class is a backport of the corresponding class of FLOW3.
 * All credits go to the v5 team.
 *
 * This script is part of the TYPO3 project. The TYPO3 project is
 * free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * The GNU General Public License can be found at
 * http://www.gnu.org/copyleft/gpl.html.
 *
 * This script is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Base database testcase for the Extbase extension.
 *
 * This base test case creates a test database and can populate
 * rows defined in fixtures to it.
 *
 * This class is used in the FAL<->extbase connection tests like
 * Tx_Extbase_Tests_Functional_Domain_Model_FileContextTest. It is
 * currently marked as experimental!
 *
 * @api experimental! This class is experimental and subject to change!
 */
abstract class Tx_Extbase_Tests_Functional_BaseTestCase extends Tx_Phpunit_Database_TestCase {
	/**
	 * @var Tx_Extbase_Object_ObjectManagerInterface The object manager
	 */
	protected $objectManager;

	/**
	 * Injects an untainted clone of the object manager and all its referencing
	 * objects for every test.
	 *
	 * @return void
	 */
	public function runBare() {
		$objectManager = t3lib_div::makeInstance('Tx_Extbase_Object_ObjectManager');
		$this->objectManager = clone $objectManager;
		parent::runBare();
	}

	protected function setUp() {
		$this->createDatabase();
		$this->useTestDatabase();

		$this->importStdDb();
		$this->importExtensions(array('cms', 'extbase'));
	}

	protected function tearDown() {
		$this->dropDatabase();
	}
}
?>
