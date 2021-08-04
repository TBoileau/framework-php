<?php

declare(strict_types=1);

use NunoMaduro\PhpInsights\Domain\Insights\CyclomaticComplexityIsHigh;
use NunoMaduro\PhpInsights\Domain\Sniffs\ForbiddenSetterSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Files\LineLengthSniff;
use PHP_CodeSniffer\Standards\Generic\Sniffs\Formatting\SpaceAfterNotSniff;
use SlevomatCodingStandard\Sniffs\Classes\ForbiddenPublicPropertySniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousAbstractClassNamingSniff;
use SlevomatCodingStandard\Sniffs\Classes\SuperfluousInterfaceNamingSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\AssignmentInConditionSniff;
use SlevomatCodingStandard\Sniffs\ControlStructures\DisallowYodaComparisonSniff;
use SlevomatCodingStandard\Sniffs\Functions\FunctionLengthSniff;
use SlevomatCodingStandard\Sniffs\Namespaces\AlphabeticallySortedUsesSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\DisallowMixedTypeHintSniff;
use SlevomatCodingStandard\Sniffs\TypeHints\ParameterTypeHintSniff;

return [
    /*
    |--------------------------------------------------------------------------
    | Default Preset
    |--------------------------------------------------------------------------
    |
    | This option controls the default preset that will be used by PHP Insights
    | to make your code reliable, simple, and clean. However, you can always
    | adjust the `Metrics` and `Insights` below in this configuration file.
    |
    | Supported: "default", "laravel", "symfony", "magento2", "drupal"
    |
     */

    'preset' => 'symfony',

    /*
    |--------------------------------------------------------------------------
    | IDE
    |--------------------------------------------------------------------------
    |
    | This options allow to add hyperlinks in your terminal to quickly open
    | files in your favorite IDE while browsing your PhpInsights report.
    |
    | Supported: "textmate", "macvim", "emacs", "sublime", "phpstorm",
    | "atom", "vscode".
    |
    | If you have another IDE that is not in this list but which provide an
    | url-handler, you could fill this config with a pattern like this:
    |
    | myide://open?url=file://%f&line=%l
    |
     */

    'ide' => 'phpstorm',

    /*
    |--------------------------------------------------------------------------
    | Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may adjust all the various `Insights` that will be used by PHP
    | Insights. You can either add, remove or configure `Insights`. Keep in
    | mind, that all added `Insights` must belong to a specific `Metric`.
    |
     */

    'exclude' => [
        'phpinsights.php',
        'public/index.php',
    ],

    'add' => [
    ],

    'remove' => [
        AlphabeticallySortedUsesSniff::class,
        DisallowYodaComparisonSniff::class,
        SpaceAfterNotSniff::class,
        ForbiddenSetterSniff::class,
        SuperfluousInterfaceNamingSniff::class,
        DisallowMixedTypeHintSniff::class,
        SuperfluousAbstractClassNamingSniff::class,
        AssignmentInConditionSniff::class,
    ],

    'config' => [
        ForbiddenPublicPropertySniff::class => [
            'exclude' => [
                'src/ORM/Mapping/Attribute/PrimaryKey.php',
                'src/ORM/Mapping/Attribute/Table.php',
                'src/ORM/Mapping/Attribute/Entity.php',
                'src/ORM/Mapping/Attribute/Column.php',
                'src/ORM/Mapping/Attribute/OneToMany.php',
                'src/ORM/Mapping/Attribute/ManyToOne.php',
                'src/ORM/Mapping/Attribute/ManyToMany.php',
            ],
        ],
        FunctionLengthSniff::class => [
            'maxLinesLength' => 20,
            'exclude' => [
                'src/DependencyInjection/Container.php',
                'src/Validator/Validator.php',
                'src/ORM/Mapping/Resolver.php',
            ],
        ],
        ParameterTypeHintSniff::class => [
            'exclude' => [
                'src/Validator/ConstraintViolationList.php',
            ],
        ],
        LineLengthSniff::class => [
            'lineLimit' => 120,
            'absoluteLineLimit' => 120,
            'ignoreComments' => true,
        ],
        CyclomaticComplexityIsHigh::class => [
            'maxComplexity' => 10,
            'exclude' => [
                'src/DependencyInjection/Container.php',
                'src/ORM/Mapping/Resolver.php',
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Requirements
    |--------------------------------------------------------------------------
    |
    | Here you may define a level you want to reach per `Insights` category.
    | When a score is lower than the minimum level defined, then an error
    | code will be returned. This is optional and individually defined.
    |
     */

    'requirements' => [
        'min-quality' => 90,
        'min-complexity' => 85,
        'min-architecture' => 90,
        'min-style' => 100,
        'disable-security-check' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Threads
    |--------------------------------------------------------------------------
    |
    | Here you may adjust how many threads (core) PHPInsights can use to perform
    | the analyse. This is optional, don't provide it and the tool will guess
    | the max core number available. This accept null value or integer > 0.
    |
     */

    'threads' => null,
];
