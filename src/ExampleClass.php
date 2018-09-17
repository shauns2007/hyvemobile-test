<?php

namespace HyveMobileTest;

require 'D:\wamp\www\hyve\boot.php';

class ExampleClass {
    public function __construct() {
        printf('Congratulations, you have successfully instantiated "%s".', __CLASS__);
    }

    public function helloWorldEcho() : string {
        return 'Hello, world!';
    }
}