<?php
/**
 * Created by PhpStorm.
 * User: zjw
 * Date: 2017/8/21
 * Time: 上午10:50
 */

namespace zacksleo\yii2\debug\tests;


use yii\base\InlineAction;

use yii\base\InvalidConfigException;
use yii\debug\Panel;
use yii;
use yii\helpers\Url;
use yii\log\Logger;
use zacksleo\yii2\debug\panels\ApiPanel;

class TestController extends \yii\base\Controller
{
}

class ApiPanelTest extends TestCase
{
    public $id;

    public function testSave()
    {
        Yii::$app->requestedAction = new InlineAction('test', new TestController('testController', \Yii::$app), 'test');
        $panel = $this->getPanel('api-debug');
        $reflection = new \ReflectionClass($panel);
        Yii::$app->requestedParams = ['panel'=>'api','headers'=>['cookie'=>'111']];
        $method = $reflection->getMethod('save');
        $this->assertTrue(200 == $method->invoke($panel)['statusCode']);
    }

    public function testGetName()
    {
        Yii::$app->requestedAction = new InlineAction('test', new TestController('testController', \Yii::$app), 'test');
        $panel = $this->getPanel('api-debug');
        $reflection = new \ReflectionClass($panel);
        $temp = new ApiPanel();
        $method = $reflection->getMethod('getName');
        $this->assertTrue($method->invoke($panel) == $temp->getName());
    }


    public function testGetSummary()
    {
        \Yii::$app->requestedAction = new InlineAction('test', new TestController('testController', \Yii::$app), 'test');
        $panel = $this->getPanel('api-debug');
        $reflection = new \ReflectionClass($panel);
        $method = $reflection->getMethod('getSummary');
        $method->invoke($panel);
        $match = preg_match('/default\/view&panel=api-debug$/', urldecode($panel->getUrl()));
        $this->assertTrue($match > 0);
   }

    public function getPanel($identifier)
    {
        $config = null;
        if (isset($this->panels[$identifier]))
            $config = $this->panels[$identifier];
        elseif (isset($this->_corePanels[$identifier]))
            $config = $this->_corePanels[$identifier];
        if (!$config)
            throw new InvalidConfigException("'$identifier' is not a valid panel identifier");
        if (is_array($config)) {
            $config['module'] = $this;
            $config['id'] = $identifier;
            return Yii::createObject($config);
        }
        return $config;
    }

    public $panels = [
        'api-debug',
    ];

    private $_corePanels = [
        'api-debug' => ['class' => 'zacksleo\yii2\debug\panels\ApiPanel'],
    ];
}