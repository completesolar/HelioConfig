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
}