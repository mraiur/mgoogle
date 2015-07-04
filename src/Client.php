<?php
namespace MGoogle {
    /**
     * Class Client
     * @package MGoogle
     */
    class Client implements ClientInterface{

        private $config;
        private $authCode = null;
        private $googleClient;

        public function __construct($config, $auth = null){
            $this->config = $config;
            $this->authCode = trim($auth);
        }

        private function initClient()
        {
            $client = new \Google_Client();
            $client->setApplicationName($this->config['APP_NAME']);
            $client->setScopes($this->config['APP_SCOPES']);
            $client->setRedirectUri($this->config['REDIRECT_URL']);
            $client->setAuthConfigFile($this->config['CLIENT_SECRET_PATH']);
            $client->setAccessType('online');
            return $client;
        }

        public function requestConnect(){
            $this->googleClient = $this->initClient();
            $authURL = $this->googleClient->createAuthUrl();
            header('Location: '.$authURL);
            die();
        }

        /**
         * $config =>
         * "APP_NAME"
         * "APP_SCOPES"
         * "CLIENT_SECRET_PATH"
         * "CREDENTIALS_PATH"
         *
         * @param $config
         * @return Google_Client
         */
        public function setup()
        {
            $this->googleClient = $this->initClient();
            return $this;
        }

        public function connect(){
            $credentialsPath = $this->config['CREDENTIALS_PATH'];

            if (file_exists($credentialsPath)) {
                $accessToken = file_get_contents($credentialsPath);
            }
            else
            {
                if( $this->authCode === null )
                {
                    return false;
                }

                // Exchange authorization code for an access token.
                $accessToken = $this->googleClient->authenticate( $this->authCode);

                $credentialsPath = $this->config['CREDENTIALS_PATH'];

                // Store the credentials to disk.
                if (!file_exists(dirname($credentialsPath)))
                {
                    mkdir(dirname($credentialsPath), 0700, true);
                }
                file_put_contents($credentialsPath, $accessToken);
            }

            $this->googleClient->setAccessToken($accessToken);

            // Refresh the token if it's expired.
            if ( $this->googleClient->isAccessTokenExpired())
            {
                if(!isset($accessToken->refresh_token))
                {
                    return $this->requestConnect();
                }

                $this->googleClient->refreshToken($this->googleClient->getRefreshToken());
                file_put_contents($credentialsPath, $this->googleClient->getAccessToken());
            }
            return $this;
        }

        public function isConnected()
        {

            $this->googleClient = $this->initClient();

            $credentialsPath = $this->config['CREDENTIALS_PATH'];

            if (file_exists($credentialsPath)) {
                $accessToken = file_get_contents($credentialsPath);
            }
            else
            {
                return false;
            }

            $this->googleClient->setAccessToken($accessToken);

            if ( $this->googleClient->isAccessTokenExpired())
            {
                return false;
            }
            return true;
        }

        public function apiClient(){
            return $this->googleClient;
        }
    }
}