<?php
namespace MGoogle\Calendar {
    /**
     * Class Event
     * @package MGoogle\Calendar
     */
    class Event{
        /**
         * @var
         */
        private $data;
        /**
         * @var array
         */
        private $eventData = [];
        /**
         * @var
         */
        private $entry;

        /**
         * @param $data
         */
        public function __construct($data){
            $this->data = $data;


            $this->validate();
        }

        /**
         * Convert start and end params from timestamp to expected format
         * Set expected summary from name
         * Convert color name to colorId
         */
        private function validate(){
            if( isset($this->data['name'])){
                $this->eventData['summary'] = $this->data['name'];
            }
            if( isset($this->data['time']) ){
                $this->eventData['start'] = array(
                    'dateTime' => date('c', $this->data['time'][0]),
                    'timeZone' => 'UTC'
                );
                $this->eventData['end'] = array(
                    'dateTime' => date('c', $this->data['time'][1]),
                    'timeZone' => 'UTC'
                );
            }
            if( isset($this->data['color'])){
                $this->eventData['colorId'] = Colors::getColor( $this->data['color'] );
            }
        }

        /**
         * @return \Google_Service_Calendar_Event
         */
        public function getEntry(){

            $this->entry = new \Google_Service_Calendar_Event($this->eventData);
           return $this->entry;
        }
    }
}