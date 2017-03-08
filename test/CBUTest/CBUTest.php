<?php
/*
 * This file is part of the Diff package.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CBUValidator\Validator;

use PHPUnit_Framework_TestCase;
use CBUValidator\Validator\CBU;

class CBUTest extends PHPUnit_Framework_TestCase
{
    /** @var  CBU */
    protected $validator;

    public function setUp()
    {
        $this->validator = new CBU();
    }

    public function testCBUNoNumerico()
    {
        $this->assertFalse($this->validator->isValid("A"));
    }

    public function testCBUNull()
    {
        $this->assertFalse($this->validator->isValid(null));
    }
}
