<?php
namespace MGoogle{
    /**
     * Class Permissions
     * @package MGoogle
     */
    class Permissions{
        /**
         * MGoogle alias to call Google Calendar in read/write mode
         */
        const calendar = \Google_Service_Calendar::CALENDAR;
        /**
         * MGoogle alias to call Google Calendar in readonly mode
         */
        const calendar_readonly = \Google_Service_Calendar::CALENDAR_READONLY;
    }
}
