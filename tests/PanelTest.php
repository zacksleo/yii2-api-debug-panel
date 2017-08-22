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

    public function testGetTraceLine_DefaultLink()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10
        ];
        $panel = $this->getPanel();
        $this->assertEquals('<a href="ide://open?url=file://file.php&line=10">file.php:10</a>', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_DefaultLink_CustomText()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10,
            'text' => 'custom text'
        ];
        $panel = $this->getPanel();
        $this->assertEquals('<a href="ide://open?url=file://file.php&line=10">custom text</a>', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_TextOnly()
    {
        $panel = $this->getPanel();
        $panel->module->traceLine = false;
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10
        ];
        $this->assertEquals('file.php:10', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_CustomLinkByString()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10
        ];
        $panel = $this->getPanel();
        $panel->module->traceLine = '<a href="phpstorm://open?url=file://file.php&line=10">my custom phpstorm protocol</a>';
        $this->assertEquals('<a href="phpstorm://open?url=file://file.php&line=10">my custom phpstorm protocol</a>', $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_CustomLinkByCallback()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10,
        ];
        $panel = $this->getPanel();
        $expected = 'http://my.custom.link';
        $panel->module->traceLine = function () use ($expected) {
            return $expected;
        };
        $this->assertEquals($expected, $panel->getTraceLine($traceConfig));
    }

    public function testGetTraceLine_CustomLinkByCallback_CustomText()
    {
        $traceConfig = [
            'file' => 'file.php',
            'line' => 10,
            'text' => 'custom text'
        ];
        $panel = $this->getPanel();
        $panel->module->traceLine = function () {
            return '<a href="ide://open?url={file}&line={line}">{text}</a>';
        };
        $this->assertEquals('<a href="ide://open?url=file.php&line=10">custom text</a>', $panel->getTraceLine($traceConfig));
    }

    private function getPanel()
    {
        return new ApiPanel(['module' => \Yii::$app->getModule('test')]);
    }
}