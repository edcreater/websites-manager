<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-console',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log', 'check'],
    'controllerNamespace' => 'console\controllers',
    'modules' => [],
    'components' => [
        'log' => [
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'check' => [
            'class' => 'common\components\check\Check',
        ],
        'resque' => [
            'class' => 'edcreater\yii2resque\Yii2Resque',
        ],
    ],
    'params' => $params,
];
