<?php
declare(strict_types=1);

/**
 * This file is part of me-cms-instagram.
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright   Copyright (c) Mirko Pagliai
 * @link        https://github.com/mirko-pagliai/me-cms-instagram
 * @license     https://opensource.org/licenses/mit-license.php MIT License
 */

namespace MeCmsInstagram\Test\TestCase\Command\Install;

use MeCms\TestSuite\TestCase;
use MeCmsInstagram\Command\Install\CopyConfigCommand;
use MeTools\TestSuite\ConsoleIntegrationTestTrait;

/**
 * CopyConfigCommandTest class
 */
class CopyConfigCommandTest extends TestCase
{
    use ConsoleIntegrationTestTrait;

    /**
     * If `true`, a mock instance of the shell will be created
     * @var bool
     */
    protected $autoInitializeClass = true;

    /**
     * Test for `execute()` method
     * @test
     */
    public function testExecute()
    {
        $this->exec('me_cms_instagram.copy_config -v');
        $this->assertExitWithSuccess();

        foreach (CopyConfigCommand::CONFIG_FILES as $file) {
            $this->assertOutputContains('File or directory `' . rtr(CONFIG . pluginSplit($file)[1] . '.php') . '` already exists');
        }
    }
}
