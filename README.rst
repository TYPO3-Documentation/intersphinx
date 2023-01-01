===========
intersphinx
===========

Intersphinx extension for the doctrine rst parser

Usage
=====

Create a new `T3Docs\Intersphinx\Intersphinx` object with the static
method `Intersphinx::getIntersphinxFromUrlArray`, pass the
`Doctrine\RST\Configuration` and an `array<String,String>` to it. Intersphinx
listens to an event when references could not be resolved in the rendering.
Therefore the `Builder` does not need to know about Intersphinx. However the
`Kernel` used in the `Builder` and `Interspinx` have to use the same
`Configuration` instance.

..  code-block:: php

    <?php
    require 'vendor/autoload.php';

    use Doctrine\RST\Builder;
    use Doctrine\RST\Configuration;
    use Doctrine\RST\Kernel;
    use T3Docs\Intersphinx\Intersphinx;

    $configuration = new Configuration();
    $intersphinx = Intersphinx::getIntersphinxFromUrlArray(
        $configuration,
        [
            't3coreapi' => 'https://docs.typo3.org/m/typo3/reference-coreapi/main/en-us/',
            't3tsconfig' => 'https://docs.typo3.org/m/typo3/reference-tsconfig/main/en-us/',
            't3tsref' => 'https://docs.typo3.org/m/typo3/reference-typoscript/main/en-us/',
            't3start' => 'https://docs.typo3.org/m/typo3/tutorial-getting-started/11.5/en-us/',
        ]
    );

    $kernel = new Kernel($configuration);
    $builder = new Builder($kernel);
    $builder->build('Documentation', 'output');

Now you can link to another manual in rst like this:

..  code-block:: rst

    Learn how to :ref:`Install TYPO3 with composer <t3start:composer>`!

The links of the current document get written into `objects.inv.json` in the
documentations main directory.

Contribution
============

Contribution is welcome, you can run tests as follows:

..  code-block:: bash

    composer update

    ;Fix Coding Guidelines
    vendor/bin/phpcbf

    ;PHP stan
    vendor/bin/phpstan

    ;PHP Unit
    vendor/bin/phpunit

    ;Rector
    vendor/bin/rector process lib
