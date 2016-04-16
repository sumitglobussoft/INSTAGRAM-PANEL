<?php


namespace App\Http\Controllers\API;


class TimeZoneConvertion
{
    function converToTimeZone($time = "", $fromTz = '', $toTz = '')
    {
        // timezone by php friendly values
        $date = new \DateTime($time, new \DateTimeZone($fromTz));
        $date->setTimezone(new \DateTimeZone($toTz));
        $o = new \ReflectionObject($date);
        $p = $o->getProperty('date');
        $date = $p->getValue($date);
        return strtotime($date);
    }
    function convertLocalTimeToUnixTime($time = "", $fromTz = '', $toTz = '')
    {
        // timezone by php friendly values
        $date = new \DateTime($time, new \DateTimeZone($fromTz));
        $date->setTimezone(new \DateTimeZone($toTz));
        $o = new \ReflectionObject($date);
        $p = $o->getProperty('date');
        $date = $p->getValue($date);
        return strtotime($date);
    }
    function convertUnixTimeToLocalTime($time = "", $fromTz = '', $toTz = '')
    {
        // timezone by php friendly values
        $time = date('Y-m-d h:i:s A T', $time);
        $date = new \DateTime($time, new \DateTimeZone($fromTz));
        $date->setTimezone(new \DateTimeZone($toTz));
        $o = new \ReflectionObject($date);
        $p = $o->getProperty('date');
        $date = $p->getValue($date);
        return strtotime($date);
    }
}