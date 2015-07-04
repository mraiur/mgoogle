<?php
namespace MGoogle {
    /**
     * Class Client
     * @package MGoogle
     */
    class ServerClient implements ClientInterface{

        private $config;
        private $googleClient;

        public function __construct($config, $arg = null){
            $this->config = $config;
        }

        private function initClient()
        {
            $private_key = file_get_contents( $this->config['SERVER_PRIVATE_KEY']);

            $credentials = new \Google_Auth_AssertionCredentials(
                $this->config['CLIENT_EMAIL'],
                $this->config['APP_SCOPES'],
                $private_key,
                $this->config['SERVER_PRIVATE_KEY_PASSWORD'],
                'http://oauth.net/grant_type/jwt/1.0/bearer', // Default grant type
                $this->config['EMAIL']
            );

            $googleClient = new \Google_Client();
            $googleClient->setAssertionCredentials($credentials);

            return $googleClient;
        }

        public function setup()
        {
            $this->googleClient = $this->initClient();
            return $this;
        }

        public function connect(){

            return $this;
        }

        public function requestConnect(){

            return $this;
        }

        public function isConnected()
        {
            // TODO implement a checking for is connected.
            return true;
        }

        public function apiClient()
        {
            return $this->googleClient;
        }
    }
}