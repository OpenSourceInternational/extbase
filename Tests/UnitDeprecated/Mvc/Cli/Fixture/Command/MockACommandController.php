<?php
namespace TYPO3\CMS\Extbase\Tests\UnitDeprecated\Mvc\Cli\Fixture\Command;

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
 * A mock CLI Command
 */
class MockACommandController extends \TYPO3\CMS\Extbase\Mvc\Cli\Command
{
    public function fooCommand()
    {
    }

    /**
     * @param mixed $someArgument
     */
    public function barCommand($someArgument)
    {
    }
}
