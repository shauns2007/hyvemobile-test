<?php

namespace HyveMobileTest\Models;

require '../boot.php';

class Contact
{
	public $title;
	public $first_name;
	public $last_name;
	public $email;
	public $domain_ip;
	public $tz;
	public $transaction_time;
	public $contact_card;
	public $note;

    public function __construct($title, $firstName, $lastName, $email, $tz, $date, $time, $note)
    {
        $this->title = $title;
        $this->first_name = $firstName;
        $this->last_name = $lastName;
        $this->email = $email;
        $this->tz = $tz;
        $this->transaction_time = date('Y-m-d H:i:s', strtotime($date . ' ' . $time));
        $this->local_transaction_time = $this->getLocalTimeOfTransaction();
        $this->contact_card = $this->createContactCard($firstName, $lastName, $email);
        $this->note = strip_tags($note); //Annoying tags wasn't necessary to do here as on insert it uses PDO parameter binding but for printing out results
    }

    /**
     * Testing Purposes only
     */
    public function getContactName()
    {
        return "$this->first_name $this->last_name";
    }

    /**
     * The date/time columns are assumed to be our server time so this just takes that and converts it to the timezone supplied
     */
    private function getLocalTimeOfTransaction()
    {
        $dt = new \DateTime($this->transaction_time, new \DateTimeZone($this->tz));
        $dt->setTimestamp(strtotime($this->transaction_time));
        return $dt->format('Y-m-d H:i:s');
    }

    private function createContactCard() : string
    {
    	$imageName = bin2hex(random_bytes(8)) . '.jpeg';
		$imageLocation = RESOURCEPATH.'images\\'.$imageName;

		$image = imagecreate(400, 100);
		$bg = imagecolorallocate($image, 255, 255, 255);
		$textColor = imagecolorallocate($image, 0, 0, 0);
		$text = $this->first_name . ' ' . $this->last_name . ' ' . $this->email;
		imagestring($image, 5, 5, 50, $text, $textColor);
		imagejpeg($image, $imageLocation);
		imagedestroy($image);
		return $imageLocation;
    }
}