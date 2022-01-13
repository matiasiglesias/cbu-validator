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

    public function testCBUValido()
    {
        $this->assertTrue($this->validator->isValid("28503965 40094708965758"));
        $this->assertTrue($this->validator->isValid("28503965  40094708965758"));
        $this->assertTrue($this->validator->isValid("28503965-40094708965758"));
        $this->assertTrue($this->validator->isValid("28503965.40094708965758"));
        $this->assertTrue($this->validator->isValid("2850396540094708965758"));
    }

    public function testCBUInvalido()
    {
        $this->assertFalse($this->validator->isValid("28503965X40094708965758"));
    }

    public function testCBUPrimerDigitoVerificadorInvalido()
    {
        $this->assertFalse($this->validator->isValid("2850396440094708965758"));
    }

    public function testCBUSegundoDigitoVerificadorInvalido()
    {
        $this->assertFalse($this->validator->isValid("2850396540094708965759"));
    }

    public function testCBUCorto()
    {
        $this->assertFalse($this->validator->isValid("285039654009470896575"));
    }

    public function testCBULargo()
    {
        $this->assertFalse($this->validator->isValid("28503965400947089657581"));
    }

    public function testCBUConCeros()
    {
        $this->assertFalse($this->validator->isValid("000000000000000000000000"));
    }
}
