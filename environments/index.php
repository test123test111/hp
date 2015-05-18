<?php
/**
 * The manifest of files that are local to specific environment.
 * This file returns a list of environments that the application
 * may be installed under. The returned data must be in the following
 * format:
 *
 * ```php
 * return [
 *     'environment name' => [
 *         'path' => 'directory storing the local files',
 *         'setWritable' => [
 *             // list of directories that should be set writable
 *         ],
 *         'setExecutable' => [
 *             // list of directories that should be set executable
 *         ],
 *         'setCookieValidationKey' => [
 *             // list of config files that need to be inserted with automatically generated cookie validation keys
 *         ],
 *         'createSymlink' => [
 *             // list of symlinks to be created. Keys are symlinks, and values are the targets.
 *         ],
 *     ],
 * ];
 * ```
 */
return [
    'Development' => [
        'path' => 'dev',
        'setWritable' => [
            'backend/runtime',
            'backend/tmp',
            'backend/web/assets',
            'amapi/runtime',
            'operate/runtime',
            'operate/web/assets',
            'oa/runtime',
            'oa/web/assets',
            'finance/runtime',
            'finance/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'operate/config/main-local.php',
            'oa/config/main-local.php',
        ],
    ],
    'Test' => [
        'path' => 'test',
        'setWritable' => [
            'backend/tmp',
            'backend/runtime',
            'backend/web/assets',
            'amapi/runtime',
            'operate/runtime',
            'operate/web/assets',
            'oa/runtime',
            'oa/web/assets',
            'finance/runtime',
            'finance/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'operate/config/main-local.php',
            'oa/config/main-local.php',
            'finance/config/main-local.php',
        ],
    ],
    'Production' => [
        'path' => 'prod',
        'setWritable' => [
            'backend/tmp',
            'backend/runtime',
            'backend/web/assets',
            'finance/runtime',
            'finance/web/assets',
            'amapi/runtime',
            'operate/runtime',
            'operate/web/assets',
            'backapi/runtime',
            'backapi/web/assets',
            'oa/runtime',
            'oa/web/assets',
        ],
        'setExecutable' => [
            'yii',
        ],
        'setCookieValidationKey' => [
            'backend/config/main-local.php',
            'finance/config/main-local.php',
            'operate/config/main-local.php',
            'oa/config/main-local.php',
        ],
    ],
];
