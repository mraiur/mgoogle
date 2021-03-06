<?php
namespace MGoogle
{
//    use MGoogle;

    use MGoogle\Calendar\Event;

    /**
     * Class Calendar
     * @package MGoogle
     */
    class Calendar implements ApiInterface{
        /**
         * @var \Google_Service_Calendar
         */
        private $service;

        /**
         * @var string
         */
        private $calendar = 'primary';

        /**
         * @param MGoogle $MGoogle
         */
        public function __construct(ClientInterface $Client)
        {
            $this->service = new \Google_Service_Calendar( $Client->apiClient() );
        }

        /**
         * @param $calendarId
         */
        public function setCalendar($calendarId){
            $this->calendar = $calendarId;
        }

        /**
         * @param $data
         * @return \Google_Service_Calendar_Event
         */
        public function saveEvent($data)
        {
            $event = new Event($data);
            if(isset($data['id']))
            {
                $event->setEvent( $this->service->events->get($this->calendar, $data['id']) );
                return $this->service->events->update($this->calendar, $event->getId(), $event->getEntry() );
            } else {
                return $this->service->events->insert($this->calendar, $event->getEntry() );
            }
        }

        public function updateEvent($data){
            $event = new Event($data);

            if($event)
            {
                $update = $this->service->events->update($this->calendar, $event->getId(), $event);
            }
            return false;
        }

        /**
         * @return array
         */
        public function getCalendars(){
            $calendarList = $this->service->calendarList->listCalendarList();
            $list = [];
            while(true) {
                foreach ($calendarList->getItems() as $calendarListEntry) {
                    $list[] = [
                        'id' => $calendarListEntry->getId(),
                        'name' => $calendarListEntry->getSummary()
                    ];
                }
                $pageToken = $calendarList->getNextPageToken();
                if ($pageToken) {
                    $optParams = array('pageToken' => $pageToken);
                    $calendarList = $this->service->calendarList->listCalendarList($optParams);
                } else {
                    break;
                }
            }
            return $list;
        }

        /**
         * @param $name
         * @return mixed
         */
        public function getCalendarID($name){
            $list = $this->getCalendars();
            foreach( $list as $row){
                if( $row['name'] == $name){
                    return $row['id'];
                }
            }
        }

        /**
         * @param $config
         * @return array|string
         */
        public function getEvents($config){
            $optParams = array(
                'maxResults' => 10,
                'orderBy' => 'startTime',
                'singleEvents' => TRUE,
                'timeMin' => date('c'),
            );
            $results = $this->service->events->listEvents($this->calendar, $optParams);

            $events = [];

            if (count($results->getItems()) == 0) {
                return "No upcoming events found.";
            } else {
                foreach ($results->getItems() as $event) {
                    $start = $event->start->dateTime;
                    if (empty($start)) {
                        $start = $event->start->date;
                    }
                    $events[] = array(
                        'name' => $event->getSummary(),
                        'start-time' => $start
                    );
                }

                return $events;
            }
        }
    }
}