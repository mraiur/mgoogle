<?php
namespace MGoogle {
    class Client{
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
        public function connect($config, $AuthCode = null) {
            $client = new \Google_Client();
            $client->setApplicationName($config['APP_NAME']);
            $client->setScopes($config['APP_SCOPES']);
            $client->setRedirectUri($config['REDIRECT_URL']);
            $client->setAuthConfigFile($config['CLIENT_SECRET_PATH']);
            $client->setAccessType('online');

            $credentialsPath = $config['CREDENTIALS_PATH'];

            if (file_exists($credentialsPath)) {
                $accessToken = file_get_contents($credentialsPath);
            }
            else
            {
                if( $AuthCode === null){
                    $authURL = $client->createAuthUrl();
                    header('Location: '.$authURL);
                    die();
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
                printf("Credentials saved to %s\n", $credentialsPath);
            }

            $client->setAccessToken($accessToken);

            // Refresh the token if it's expired.
            if ($client->isAccessTokenExpired()) {
                $client->refreshToken($client->getRefreshToken());
                file_put_contents($credentialsPath, $client->getAccessToken());
            }
            return $client;
        }
    }
}