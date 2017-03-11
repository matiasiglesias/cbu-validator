Módulo Validador de CBU para ZF2
================================

[![Build Status](https://travis-ci.org/matiasiglesias/cbu-validator.svg?branch=master)](https://travis-ci.org/matiasiglesias/cbu-validator)


#Introducción

Módulo Validador de Clave Bancaria Uniforme
utilizada en Argentina.

Más información en [Wikipedia](https://es.wikipedia.org/wiki/Clave_Bancaria_Uniforme)



##Instalacion

Instala el modulo con composer agregando el siguiente require "require" en el archivo `composer.json`

```json
{
	"require": {
		"matiasiglesias/cbu-validator": "1.*"
	}
}
```

luego, ejecuta

```bash
$ php composer.phar update
```

y habilita el módulo en `application.config.php`

```php
array(
	'modules' => array(
		'Application',
		'CBUValidator',
		// ...
	),
);
```



## Uso
Agrega el validador

```php
    <?php

        $inputFilter->add($factory->createInput(array(
            'name'     => 'cbu',
            'required' => true,
            'filters'  => array(
                array('name' => 'Digits'), //Filtra los guiones
            ),
            'validators' => array(
                array(
                    'name' => 'CBUValidator\Validator\CBU',
                    'options' => array(
                        'filterNumeric' => true, //Filtra cualquier caracter no numérico del CBU (ej. '-')
                    ),
                ),
            )
        )));

    ?>
```

## Configuracion
Estas son las opciones del validador:

* **filterNumeric** Boolean. Filtra cualquier caracter no numérico del CBU (ej. '-'). Valor por defecto *true*.


## Contacto
1. Via email [matiasiglesias@matiasiglesias.com.ar](mailto:matiasiglesias@matiasiglesias.com.ar).
2. Via Twitter[@matiashiglesias](https://twitter.com/matiashiglesias)

## Licencia

CBUValidator is licensed under the MIT license.  
See the included LICENSE file.
Copyright (c) 2013-2017 Matias Iglesias

http://www.matiasiglesias.com.ar/  
All rights reserved.