<?php namespace CompleteSolar\HelioConfig\Utilities;
use CompleteSolar\HelioConfig\Utilities\Config;

class ConfigTest extends \PHPUnit_Framework_TestCase {
    protected function setUp() {

    }

    public function testGetProperty() {
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $value = $config->getProperty(['db','host']);
        $this->assertEquals('HOST', $value);
    }

    public function testGetPropertyWithEmptyValue() {
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $value = $config->getProperty(['db','empty']);
        $this->assertEquals('', $value);
    }

    public function testGetPropertyWithNoValue() {
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $value = $config->getProperty(['db','no_value']);
        $this->assertEquals('', $value);
    }

    public function testGetPropertyWithNullValue() {
        $foundError = false;
        try {
            new Config(__DIR__.'/../config/test.invalid.ini', true);
        } catch(\PHPUnit_Framework_Error_Warning $e){
            $foundError = true;
        }
        $this->assertTrue($foundError);
    }

    public function testSetProperty() {
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $config->setProperty(['db','host'], "CLIENT");
        $value = $config->getProperty(['db','host']);
        $this->assertEquals('CLIENT', $value);
    }

    public function testWriteConfig(){
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $config->setProperty(['db','host'], "CLIENT");
        $filename = __DIR__.'/../config/'.time().'.example.ini';
        $config->write_ini_file($filename);
        $config1 = new Config($filename);
        $value = $config1->getProperty(['db','host']);
        $this->assertEquals('CLIENT', $value);
        unlink($filename);
    }

    public function testWriteEmptyToConfig(){
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $config->setProperty(['db','host'], '');
        $filename = __DIR__.'/../config/'.time().'.example.ini';
        $config->write_ini_file($filename);
        $config1 = new Config($filename);
        $value = $config1->getProperty(['db','host']);
        $this->assertEquals('', $value);
        unlink($filename);
    }

    public function testWriteNullToConfig(){
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $config->setProperty(['db','host'], null);
        $filename = __DIR__.'/../config/'.time().'.example.ini';
        $config->write_ini_file($filename);
        $config1 = new Config($filename);
        $value = $config1->getProperty(['db','host']);
        $this->assertEquals('', $value);
        unlink($filename);
    }

    public function testWriteNullKeyToConfig(){
        $config = new Config(__DIR__.'/../config/test.example.ini', true);
        $config->setProperty(['db','null'], null);
        $filename = __DIR__.'/../config/'.time().'.example.ini';
        $config->write_ini_file($filename);
        $foundError = false;
        try {
            new Config($filename);
        } catch(\PHPUnit_Framework_Error_Warning $e){
            $foundError = true;
        }
        $this->assertTrue($foundError);
        unlink($filename);
    }

}