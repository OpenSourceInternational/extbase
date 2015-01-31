<?php
namespace TYPO3\CMS\Extbase\Tests\Unit\Validation\Validator;

/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */

/**
 * Test case
 */
class ConjunctionValidatorTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function addingValidatorsToAJunctionValidatorWorks() {
		$proxyClassName = $this->buildAccessibleProxy(\TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator::class);
		$conjunctionValidator = new $proxyClassName(array());
		$mockValidator = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class);
		$conjunctionValidator->addValidator($mockValidator);
		$this->assertTrue($conjunctionValidator->_get('validators')->contains($mockValidator));
	}

	/**
	 * @test
	 * @author Andreas Förthner <andreas.foerthner@netlogix.de>
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function allValidatorsInTheConjunctionAreCalledEvenIfOneReturnsError() {
		$validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator(array());
		$validatorObject = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$validatorObject->expects($this->once())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
		$errors = new \TYPO3\CMS\Extbase\Error\Result();
		$errors->addError(new \TYPO3\CMS\Extbase\Error\Error('Error', 123));
		$secondValidatorObject = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$secondValidatorObject->expects($this->once())->method('validate')->will($this->returnValue($errors));
		$thirdValidatorObject = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$thirdValidatorObject->expects($this->once())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
		$validatorConjunction->addValidator($validatorObject);
		$validatorConjunction->addValidator($secondValidatorObject);
		$validatorConjunction->addValidator($thirdValidatorObject);
		$validatorConjunction->validate('some subject');
	}

	/**
	 * @test
	 * @author Andreas Förthner <andreas.foerthner@netlogix.de>
	 */
	public function validatorConjunctionReturnsNoErrorsIfAllJunctionedValidatorsReturnNoErrors() {
		$validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator(array());
		$validatorObject = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$validatorObject->expects($this->any())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
		$secondValidatorObject = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$secondValidatorObject->expects($this->any())->method('validate')->will($this->returnValue(new \TYPO3\CMS\Extbase\Error\Result()));
		$validatorConjunction->addValidator($validatorObject);
		$validatorConjunction->addValidator($secondValidatorObject);
		$this->assertFalse($validatorConjunction->validate('some subject')->hasErrors());
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 * @author Christopher Hlubek <hlubek@networkteam.com>
	 */
	public function validatorConjunctionReturnsErrorsIfOneValidatorReturnsErrors() {
		$validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator(array());
		$validatorObject = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$errors = new \TYPO3\CMS\Extbase\Error\Result();
		$errors->addError(new \TYPO3\CMS\Extbase\Error\Error('Error', 123));
		$validatorObject->expects($this->any())->method('validate')->will($this->returnValue($errors));
		$validatorConjunction->addValidator($validatorObject);
		$this->assertTrue($validatorConjunction->validate('some subject')->hasErrors());
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function removingAValidatorOfTheValidatorConjunctionWorks() {
		/** @var \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator|\PHPUnit_Framework_MockObject_MockObject|\TYPO3\CMS\Core\Tests\AccessibleObjectInterface */
		$validatorConjunction = $this->getAccessibleMock(\TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator::class, array('dummy'), array(array()), '', TRUE);
		$validator1 = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$validator2 = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$validatorConjunction->addValidator($validator1);
		$validatorConjunction->addValidator($validator2);
		$validatorConjunction->removeValidator($validator1);
		$this->assertFalse($validatorConjunction->_get('validators')->contains($validator1));
		$this->assertTrue($validatorConjunction->_get('validators')->contains($validator2));
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 * @expectedException \TYPO3\CMS\Extbase\Validation\Exception\NoSuchValidatorException
	 */
	public function removingANotExistingValidatorIndexThrowsException() {
		$validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator(array());
		$validator = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$validatorConjunction->removeValidator($validator);
	}

	/**
	 * @test
	 * @author Robert Lemke <robert@typo3.org>
	 */
	public function countReturnesTheNumberOfValidatorsContainedInTheConjunction() {
		$validatorConjunction = new \TYPO3\CMS\Extbase\Validation\Validator\ConjunctionValidator(array());
		$validator1 = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$validator2 = $this->getMock(\TYPO3\CMS\Extbase\Validation\Validator\ValidatorInterface::class, array('validate', 'getOptions'));
		$this->assertSame(0, count($validatorConjunction));
		$validatorConjunction->addValidator($validator1);
		$validatorConjunction->addValidator($validator2);
		$this->assertSame(2, count($validatorConjunction));
	}

}
