<?php

class ContactClassTest extends \PHPUnit\Framework\TestCase
{
    public function testCreatingNewContactReturnsCorrectName() {
        $contactClass = new \HyveMobileTest\Models\Contact('Mr', 'Shaun', 'Sparg', 'shaun@gmail.com', '', 'Africa/Johannesburg', '19-Apr-2018', '12:00:00', 'test');
        unlink($contactClass->contact_card);
        $this->assertEquals('Shaun Sparg', $contactClass->getContactName());
    }
}
