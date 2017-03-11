<?php
/**
 * Copyright (c) 2013-2017 Matias Iglesias <matiasiglesias@matiasiglesias.com.ar>.
 *
 *
 * Redistribution and use in source and binary forms are permitted
 * provided that the above copyright notice and this paragraph are
 * duplicated in all such forms and that any documentation, advertising
 * materials, and other materials related to such distribution and use
 * acknowledge that the software was developed by Matias Iglesias. The
 * name of the Matias Iglesias may not be used to endorse or promote
 * products derived from this software without specific prior written
 * permission.
 *
 * THIS SOFTWARE IS PROVIDED "AS IS" AND WITHOUT ANY EXPRESS OR IMPLIED
 * WARRANTIES, INCLUDING, WITHOUT LIMITATION, THE IMPLIED WARRANTIES OF
 * MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace CBUValidator\Validator;

use Traversable;
use Zend\Stdlib\ArrayUtils;
use Zend\Validator\AbstractValidator;

/**
 * Class CBU
 * @package CBUValidator\Validator
 * @author Matias Iglesias <matiasiglesias@meridiem.com.ar>
 */
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
    protected $options = [
        'filterNumeric' => true,
    ];

    /**
     * Returns the filterNumeric option
     *
     * @return bool
     */
    public function getFilterNumeric()
    {
        return $this->options['filterNumeric'];
    }

    /**
     * Sets the filterNumeric option
     *
     * @param  bool $filter
     * @return CBU Provides a fluent interface
     */
    public function setFilterNumeric($filter)
    {
        $this->options['filterNumeric'] = $filter;
        return $this;
    }

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

    /**
     * @param mixed $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->setValue($value);

        if ($this->getFilterNumeric()) {
            $value = preg_replace("/[\-|\ |\.]/", "", $value);
        }

        if (!is_numeric($value)) {
            $this->error(self::MSG_NUMERIC);
            return false;
        }

        if (strlen($value) != 22) {
            $this->error(self::MSG_INVALID_LENGTH);
            return false;
        }

        /** @var array $arr */
        $arr = str_split($value);

        if ($arr[7] <> self::getDigitoVerificador($arr, 0, 6)) {
            $this->error(self::MSG_INVALID);
            return false;
        }

        if ($arr[21] <> self::getDigitoVerificador($arr, 8, 20)) {
            $this->error(self::MSG_INVALID);
            return false;
        }

        return true;
    }

    /**
     * Devuelve el dígito verificador para los dígitos de las posiciones "pos_inicial" a "pos_final"
     * de la cadena "$numero" usando clave 10 con ponderador 9713
     *
     * @param array $numero arreglo de digitos
     * @param integer $pos_inicial
     * @param integer $pos_final
     * @return integer digito verificador de la cadena $numero
     */
    private static function getDigitoVerificador($numero, $pos_inicial, $pos_final)
    {
        $ponderador = array(3,1,7,9);
        $suma = 0;
        $j = 0;
        for ($i = $pos_final; $i >= $pos_inicial; $i--) {
            $suma = $suma + ($numero[$i] * $ponderador[$j % 4]);
            $j++;
        }
        return (10 - $suma % 10) % 10;
    }
}
