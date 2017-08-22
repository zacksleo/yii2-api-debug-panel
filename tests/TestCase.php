<?php
/**
 * Created by PhpStorm.
 * User: zjw
 * Date: 2017/8/21
 * Time: 上午10:28
 */

namespace zacksleo\yii2\debug\tests;


use PHPUnit_Framework_TestCase;
use yii\helpers\ArrayHelper;

class TestCase extends PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->destroyApplication();
    }

    /**
     * Populates Yii::$app with a new application
     * The application will be destroyed on tearDown() automatically.
     * @param array $config The application configuration, if needed
     * @param string $appClass name of the application class to create
     */
    protected function mockApplication($config = [], $appClass = '\yii\console\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
        ], $config));
    }

    protected function mockWebApplication($config = [], $appClass = '\yii\web\Application')
    {
        new $appClass(ArrayHelper::merge([
            'id' => 'testapp',
            'basePath' => __DIR__,
            'vendorPath' => $this->getVendorPath(),
            'aliases' => [
                '@bower' => '@vendor/bower-asset',
                '@npm' => '@vendor/npm-asset',
            ],
            'modules' => [
                'test' => [
                    'class' => 'yii\debug\Module',
                    'allowedIPs' => ['127.0.0.1', '::1'],
                ]
            ]
        ], $config));
    }

    protected function getVendorPath()
    {
        $vendor = dirname(__DIR__) . '/vendor';
        return $vendor;
    }

    /**
     * Destroys application in Yii::$app by setting it to null.
     */
    protected function destroyApplication()
    {
        if (\Yii::$app && \Yii::$app->has('session', true)) {
            \Yii::$app->session->close();
        }
        \Yii::$app = null;
    }
}