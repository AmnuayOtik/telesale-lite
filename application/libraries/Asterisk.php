<?php

class Asterisk{

    private $ami_host = "192.168.98.150";    
    private $port = 5038;
    private $username = "otikadmin2025";    
    private $password = "P@ssw0rd##";
    private $context = "from-internal";
    private $socket;

    public function __construct() {
        echo "Asterisk";
    }


}