<?php


namespace MGoogle{
    use MGoogle\Client;

    class Calendar {
        private $client;
        public function __construct(){
            $client = new Client();
            $this->client = $client->createClient();
        }

        public function saveEvent(){

        }
    }
}