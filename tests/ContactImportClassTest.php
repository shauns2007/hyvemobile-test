<?php

class ContactImportClassTest extends \PHPUnit\Framework\TestCase
{

    public function testParseFromCSVReturnsCorrectStructure()
    {   
    	$contactImportClass = new \HyveMobileTest\Support\ContactImport();
    	$import = $contactImportClass->parseFromCSV(__DIR__.'/MOCK_DATA.csv')[0];
    	$this->assertInternalType('array',(array) $import);
    	$this->assertArrayHasKey('title',(array) $import);
    	$this->assertArrayHasKey('first_name',(array) $import);
    	$this->assertArrayHasKey('last_name',(array) $import);
    	$this->assertArrayHasKey('email',(array) $import);
    	$this->assertArrayHasKey('tz',(array) $import);
    	$this->assertArrayHasKey('date',(array) $import);
    	$this->assertArrayHasKey('time',(array) $import);
    	$this->assertArrayHasKey('note',(array) $import);
    }
}
