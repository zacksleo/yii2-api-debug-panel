<?php
/**
 * Created by PhpStorm.
 * User: zjw
 * Date: 2017/8/22
 * Time: 下午4:09
 */

namespace zacksleo\yii2\debug\tests;

use yii\base\Action;
use yii\base\InlineAction;
use yii\debug\Module;
use zacksleo\yii2\debug\panels\ApiPanel;

class TestController extends \yii\web\Controller
{
}

class PanelTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->mockWebApplication();
    }

    public function testGetName()
    {
        $panel = $this->getPanel();
        $name = $panel->getName();
        $bool = !empty($name);
        $this->assertTrue($bool);
    }

    public function testSave()
    {
        \Yii::$app->requestedAction = new Action('test', new TestController('testController', \Yii::$app));
        $panel = $this->getPanel();
        $reflection = new \ReflectionClass($panel);
        $method = $reflection->getMethod('save');
        $method->setAccessible(true);
        $method->invoke($panel);

        $_SERVER['CONTENT_TYPE'] = "application/json";
        \Yii::$app->requestedAction = new InlineAction('test', new TestController('testController', \Yii::$app), 'save');
        $panel = $this->getPanel();
        $res = $panel->save();
        $this->assertTrue(200 == $res['statusCode']);
    }

    public function testGetSummary()
    {
        $panel = $this->getPanel();
        $res = $panel->getSummary();
        $this->assertTrue(!empty($res));
    }

    public function testDetail()
    {
        $panel = $this->getPanel();
        $res = $panel->getDetail();
        $this->assertTrue(!empty($res));
    }

    private function getPanel()
    {
        return new ApiPanel(['module' => new Module('debug')]);
    }
}
