<?php
/**
 * Copyright (c) 2016. Matias Iglesias <matiasiglesias@meridiem.com.ar>
 *
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy of this software
 *  and associated documentation files (the "Software"), to deal in the Software without restriction,
 *  including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense,
 *  and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so,
 *  subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all copies or substantial
 *  portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT
 *  LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN
 *  NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 *  WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 *  SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

namespace CBUValidator\Validator;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\AbstractValidator;

class CBU extends AbstractValidator
{
    const MSG_NUMERIC = 'msgNumeric';
    const MSG_INVALID = 'MsgInvalid';
    const MSG_INVALID_LENGTH = 'MsgInvalidLength';

    protected $messageTemplates = array(
        self::MSG_NUMERIC => "'%value%' no es numerico",
        self::MSG_INVALID => "'%value%' no es un CBU valido",
        self::MSG_INVALID_LENGTH => "El CBU debe tener 22 digitos",
    );

    /**
     * Options for the between validator
     *
     * @var array
     */
    protected $options = array();

    /**
     * Sets validator options
     * Accepts the following option keys:
     *
     * @param  array|Traversable $options
     */
    public function __construct(array $options = array())
    {
        if ($options instanceof Traversable) {
            $options = ArrayUtils::iteratorToArray($options);
        }

        parent::__construct($options);
    }

    public function isValid($value)
    {
        $this->setValue($value);

        if (!is_numeric($value)) {
            $this->error(self::MSG_NUMERIC);
            return false;
        }

        if (strlen($value) != 22) {
            $this->error(self::MSG_INVALID_LENGTH);
            return false;
        }

        $bloque_1 = substr($value, 0, 8);
        $bloque_2 = substr($value, 8, 14);

        //Verificacion del Primer Bloque
        $banco = substr($bloque_1, 0, 3);
        $b1 = (int) substr($banco, 0, 1);
        $b2 = (int) substr($banco, 1, 1);
        $b3 = (int) substr($banco, 2, 1);
        $digitoVerificador1 = (int) substr($bloque_1, 3, 1);
        $sucursal = substr($bloque_1, 4, 3);
        $s1 = (int) substr($sucursal, 0, 1);
        $s2 = (int) substr($sucursal, 1, 1);
        $s3 = (int) substr($sucursal, 2, 1);
        $digitoVerificador2 = (int) substr($bloque_1, 7, 1);

        $suma1 = $b1 * 7 + $b2 * 1 + $b3 * 3 + $digitoVerificador1 * 9 + $s1 * 7 + $s2 * 1 + $s3 * 3;
        $diferencia1 = 10 - (int) substr($suma1, -1);

        if ($diferencia1 != $digitoVerificador2) {
            $this->error(self::MSG_INVALID);
            return false;
        }

        //Verificacion del Segundo Bloque
        $cuenta = substr($bloque_2, 0, 13);
        $digitoVerificador = substr($bloque_2, 13, 1);
        $c1 = (int) substr($cuenta, 0, 1);
        $c2 = (int) substr($cuenta, 1, 1);
        $c3 = (int) substr($cuenta, 2, 1);
        $c4 = (int) substr($cuenta, 3, 1);
        $c5 = (int) substr($cuenta, 4, 1);
        $c6 = (int) substr($cuenta, 5, 1);
        $c7 = (int) substr($cuenta, 6, 1);
        $c8 = (int) substr($cuenta, 7, 1);
        $c9 = (int) substr($cuenta, 8, 1);
        $c10 = (int) substr($cuenta, 9, 1);
        $c11 = (int) substr($cuenta, 10, 1);
        $c12 = (int) substr($cuenta, 11, 1);
        $c13 = (int) substr($cuenta, 12, 1);

        $suma2 = $c1 * 3 + $c2 * 9 + $c3 * 7 + $c4 * 1 + $c5 * 3 + $c6 * 9 + $c7 * 7 + $c8 * 1 + $c9 * 3 + $c10 * 9
            + $c11 * 7 + $c12 * 1 + $c13 * 3;
        $diferencia2 = 10 - (int) substr($suma2, -1);
        if ($diferencia2 != $digitoVerificador) {
            $this->error(self::MSG_INVALID);
            return false;
        }

        return true;
    }
}