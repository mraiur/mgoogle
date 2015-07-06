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


        private $id;

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

            if( isset($this->data['id'])){
                $this->id = $this->data['id'];
            }

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

        // TODO remove the duplication of argument checking.
        private function updateEntry(){

            if( isset($this->data['name']))
            {
                $this->entry->setSummary( $this->data['name']);
            }

            if( isset($this->data['color']))
            {
                $this->entry->setColorId( Colors::getColor( $this->data['color'] ) );
            }


        }

        public function getId()
        {
            return $this->id;
        }

        public function setEvent(\Google_Service_Calendar_Event $event){
            $this->entry = $event;
        }

        /**
         * @return \Google_Service_Calendar_Event
         */
        public function getEntry(){

            if( $this->entry )
            {
                $this->updateEntry();
            }
            else
            {
                $this->entry = new \Google_Service_Calendar_Event($this->eventData);
            }

           return $this->entry;
        }
    }
}