<?php


namespace MGoogle{
    use MGoogle\MGoogle;

    class Calendar {
        private $client;

        public function __construct($config){
            $this->client = new MGoogle($config);
        }

        public function saveEvent(){

        }
    }
}