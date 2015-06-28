<?php
use MGoogle\Permissions;

namespace MGoogle{
    class MGoogle{

        private static $config;
        private static $authCode;

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

        public function Connect($config, $authCode = null)
        {
            $this->prepareAPIConfig($config, $authCode);

            $client = new Client();
            return $client->connect(self::$config, self::$authCode);
        }

        public function getClient(){

        }
    }
}