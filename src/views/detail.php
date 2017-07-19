<?php
/* @var $panel yii\debug\panels\RequestPanel */

use yii\bootstrap\Tabs;

echo "<h1>API调试</h1>";

echo Tabs::widget([
    'items' => [
        [
            'label' => '参数',
            'content' => $this->render('table', ['caption' => '请求 [' . $panel->data['SERVER']['REQUEST_METHOD'] . ']', 'values' => $panel->data['requestBody']])
                . $this->render('table', ['caption' => '文件', 'values' => $panel->data['FILES']]) .
                $this->render('table', ['caption' => '响应 [' . $panel->data['statusCode'] . ']', 'values' => $panel->data['RESPONSE']]),
            'active' => true,
        ],
        [
            'label' => '头部',
            'content' => $this->render('table', ['caption' => '请求头', 'values' => $panel->data['requestHeaders']])
                . $this->render('table', ['caption' => '响应头', 'values' => $panel->data['responseHeaders']])
        ],
    ],
]);
