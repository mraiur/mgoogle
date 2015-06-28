<?php
namespace MGoogle\Client {
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
        public function createClient($config)
        {
            $client = new Google_Client();
            $client->setApplicationName( $config['APP_NAME'] );
            $client->setScopes($config['APP_SCOPES']);
            $client->setAuthConfigFile( $config['CLIENT_SECRET_PATH'] );
            $client->setAccessType('offline');

            // Load previously authorized credentials from a file.
            $credentialsPath = expandHomeDirectory( $config['CREDENTIALS_PATH']);
            if (file_exists($credentialsPath)) {
                $accessToken = file_get_contents($credentialsPath);
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $client->authenticate($authCode);

                // Store the credentials to disk.
                if(!file_exists(dirname($credentialsPath))) {
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