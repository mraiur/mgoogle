<?php
namespace MGoogle {
    /**
     * Class Client
     * @package MGoogle
     */
    class Client{
        private function client($config){
            $client = new \Google_Client();
            $client->setApplicationName($config['APP_NAME']);
            $client->setScopes($config['APP_SCOPES']);
            $client->setRedirectUri($config['REDIRECT_URL']);
            $client->setAuthConfigFile($config['CLIENT_SECRET_PATH']);
            $client->setAccessType('online');
            return $client;
        }

        public function requestPermission($config){
            $client = $this->client($config);
            $authURL = $client->createAuthUrl();
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
        public function connected($config, $AuthCode = null) {
            $client = $this->client($config);

            $credentialsPath = $config['CREDENTIALS_PATH'];

            if (file_exists($credentialsPath)) {
                $accessToken = file_get_contents($credentialsPath);
            }
            else
            {
                if( $AuthCode === null )
                {
                    return false;
                }

                $authCode = trim($AuthCode);

                // Exchange authorization code for an access token.
                $accessToken = $client->authenticate($authCode);

                $credentialsPath = $config['CREDENTIALS_PATH'];

                // Store the credentials to disk.
                if (!file_exists(dirname($credentialsPath))) {
                    mkdir(dirname($credentialsPath), 0700, true);
                }
                file_put_contents($credentialsPath, $accessToken);
            }

            $client->setAccessToken($accessToken);

            // Refresh the token if it's expired.
            if (  $client->isAccessTokenExpired())
            {
                if(!isset($accessToken->refresh_token)) {
                    echo '<pre>'.print_r($accessToken, true).'</pre>';
                    die();
                    return false;
                }

                $client->refreshToken($client->getRefreshToken());
                file_put_contents($credentialsPath, $client->getAccessToken());
            }
            return $client;
        }
    }
}