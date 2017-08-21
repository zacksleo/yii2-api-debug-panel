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
use zacksleo\yii2\debug\panels\ApiPanel;

class TestController extends \yii\base\Controller
{
}

class ApiPanelTest extends TestCase
{
    public $id;

    public function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
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
       // \Yii::$app->requestedAction = new InlineAction('test', new TestController('testController', \Yii::$app), 'test');
        $panel = $this->getPanel('api-debug');
        Yii::$app->request->bodyParams = ['panel'=>'api'];
        Yii::$app->runAction('test/default/index');exit;

        $reflection = new \ReflectionClass($panel);
        $method = $reflection->getMethod('getSummary');
        $match = preg_match('/default\/view&panel=api-debug$/', urldecode($panel->getUrl()));
        $this->assertTrue($match > 0);
        var_dump($method->invoke($panel,['line'=>10]));
        exit;
        $method->setAccessible(true);
        $this->assertEquals('tests\codeception\unit\TestController::test()', $method->invoke($panel));
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