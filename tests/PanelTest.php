<?php
/**
 * Created by PhpStorm.
 * User: zjw
 * Date: 2017/8/22
 * Time: 下午4:09
 */

namespace zacksleo\yii2\debug\tests;


use yii\debug\Module;
use yii\debug\Panel;
use zacksleo\yii2\debug\panels\ApiPanel;

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