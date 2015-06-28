<?php
use MGoogle\Permissions;

namespace MGoogle{
    /**
     * Class MGoogle
     * @package MGoogle
     */
    class MGoogle{

        /**
         * @var
         */
        private static $config;
        /**
         * @var
         */
        private static $authCode;

        /**
         * @param $config
         * @param $authCode
         * @throws \Exception
         */
        private function prepareAPIConfig($config, $authCode){
            if(!isset($config['PERMISSIONS'])){
                throw new \Exception('Provide requested PERMISSIONS.');
            }
            $permissions = explode(',', $config['PERMISSIONS']);

            $permissionsReflection = new \ReflectionClass('MGoogle\Permissions');

            $roles = [];
            foreach( $permissions as $permission ){
                $roles[] = $permissionsReflection->getConstant($permission);
            }

            $apiConfig = [
                'APP_NAME' => $config['APP_NAME'],
                'CREDENTIALS_PATH' => $config['CREDENTIALS_PATH'],
                'CLIENT_SECRET_PATH' => $config['CLIENT_SECRET_PATH'],
                'REDIRECT_URL' => $config['REDIRECT_URL'],
                'APP_SCOPES' => implode(' ', $roles)
            ];
            self::$config = $apiConfig;
            self::$authCode = $authCode;
        }

        /**
         * @param $config
         * @param null $authCode
         * @return MGoogle
         * @throws \Exception
         */
        public static function Connect($config, $authCode = null)
        {
            $class = new MGoogle();
            $class->prepareAPIConfig($config, $authCode);

            return $class;
        }

        /**
         * @return Google_Client
         */
        public function getClient(){
            $client = new Client();
            return $client->connect(self::$config, self::$authCode);
        }

        /**
         * @return bool
         */
        public function isConnected()
        {
            $client = $this->getClient();
            if( $client instanceof \Google_Client  ){
                return true;
            }
            return false;
        }
    }
}