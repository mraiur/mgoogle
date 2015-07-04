<?php
namespace MGoogle {
    /**
     * Interface ClientInterface
     * @package MGoogle
     */
    interface ClientInterface{
        /**
         * Prepare Client API
         * @param $config array
         * @param null $arg optional argument
         */
        public function __construct($config, $arg = null);

        /**
         * Check if client is connected
         * @return mixed
         */
        public function isConnected();

        /**
         * @return mixed
         */
        public function setup();
        /**
         * Do connection method. Setup google client.
         * @return mixed
         */
        public function connect();


        /**
         * Request connection ( Refresh token etc.. )
         * @return mixed
         */
        public function requestConnect();

        /**
         * Get google api client
         * @return mixed
         */
        public function apiClient();

    }
}