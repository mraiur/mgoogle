<?php
namespace MGoogle{
    use MGoogle\Permissions;
    /**
     * Class MGoogle
     * @package MGoogle
     */
    class MGoogle{

        /**
         * @var
         */
        private $config;
        /**
         * @var
         */
        private $authCode;

        /**
         * @var
         */
        private $apiConfig;


        /**
         * @param $config
         * @param null $authCode
         */
        private function __construct($config, $authCode = null)
        {
            $this->config = $config;
            $this->authCode = $authCode;

            $this->prepareAPIConfig();
        }

        /**
         * @throws \Exception
         */
        private function prepareAPIConfig()
        {
            if(!isset($this->config['PERMISSIONS'])){
                throw new \Exception('Provide requested PERMISSIONS.');
            }
            $permissions = explode(',', $this->config['PERMISSIONS']);

            $permissionsReflection = new \ReflectionClass('MGoogle\Permissions');

            $roles = [];
            foreach( $permissions as $permission )
            {
                $roles[] = $permissionsReflection->getConstant($permission);
            }

            if( isset($this->config['CLIENT_TYPE']) && $this->config['CLIENT_TYPE'] === 'server')
            {
                $apiConfig = [
                    'SERVER_PRIVATE_KEY' => isset($this->config['SERVER_PRIVATE_KEY'])?$this->config['SERVER_PRIVATE_KEY']:null,
                    'SERVER_PRIVATE_KEY_PASSWORD' => isset($this->config['SERVER_PRIVATE_KEY_PASSWORD'])?$this->config['SERVER_PRIVATE_KEY_PASSWORD']:'notasecret',
                    'CLIENT_ID' => isset($this->config['CLIENT_ID'])?$this->config['CLIENT_ID']:null,
                    'CLIENT_EMAIL' => isset($this->config['CLIENT_EMAIL'])?$this->config['CLIENT_EMAIL']:null,
                    'EMAIL' => isset($this->config['EMAIL'])?$this->config['EMAIL']:null,
                    'GRANT_TYPE' => isset($this->config['GRANT_TYPE'])?$this->config['GRANT_TYPE']:'http://oauth.net/grant_type/jwt/1.0/bearer',
                    'CLIENT_TYPE' => 'server'
                ];
            } else {
                $apiConfig = [
                    'APP_NAME' => $this->config['APP_NAME'],
                    'CREDENTIALS_PATH' => $this->config['CREDENTIALS_PATH'],
                    'CLIENT_SECRET_PATH' => $this->config['CLIENT_SECRET_PATH'],
                    'REDIRECT_URL' => $this->config['REDIRECT_URL'],
                    'CLIENT_TYPE' => 'client'
                ];
            }

            $apiConfig['APP_SCOPES'] = implode(' ', $roles);
            $this->apiConfig = $apiConfig;
        }



        /**
         * @param $config
         * @param null $authCode
         * @return MGoogle
         * @throws \Exception
         */
        public static function Init($config, $authCode = null)
        {
            $class = new MGoogle($config, $authCode);

            return $class;
        }


        /**
         * @return $this|Google_Client
         */
        private function getRequiredClient()
        {
            if( $this->config['CLIENT_TYPE'] == 'server')
            {
                $client = new ServerClient($this->apiConfig);
            }
            else
            {
                $client = new Client( $this->apiConfig, $this->authCode);
            }

            return $client->setup();
        }


        /**
         * @return $this|Google_Client|MGoogle
         */
        public function getClient()
        {
            return $this->getRequiredClient();
        }

        /**
         * @return mixed
         */
        public function Connect()
        {
            $client = $this->getRequiredClient();
            return $client->connect();
        }


        public function doConnect(){
            $client = $this->getRequiredClient();
            return $client->requestConnect();
        }

        /**
         * @return bool
         */
        public function isConnected()
        {
            $client = $this->getClient();
            if( $client instanceof ClientInterface && $client->isConnected() )
            {
                return true;
            }
            return false;
        }
    }
}