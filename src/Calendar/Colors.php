<?php
namespace MGoogle\Calendar {
    /**
     * Class Colors
     * @package MGoogle\Calendar
     */
    class Colors {
        /**
         * @var string 'grey'
         */
        private static $defaultColor =  'grey';

        /**
         * Named array for color and coresponding google colorId's
         * @var array
         */
        private static $colors = [
            'light-blue' => 1, // #A4BDFC
            'light-green' => 2, // #7AE7BF
            'pink' => 3, // #DBADFF
            'peach' => 4, // FF887C
            'yellow' => 5, //FBD75B
            'orange' => 6, // FFB878
            'cyan' => 7, // 46D6DB
            'grey' => 8, //E1E1E1
            'blue' => 9, // 5484ED
            'green' => 10, // 51B749
            'red' => 11 //DC2127
        ];

        /**
         * @param $name
         * @return mixed
         */
        public static function getColor( $name ){
            if( isset(self::$colors[$name]) ){
                return self::$colors[$name];
            }
            return self::$colors[self::$defaultColor];
        }
    }
}