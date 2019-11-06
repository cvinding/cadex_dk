<?php
namespace HELPER;

/**
 * Class DateHelper
 * @package HELPER
 * @author Christian Vinding Rasmussen
 * DateHelper is used for helping with validating and formatting dates, and everything else that has to do with dates
 */
class DateHelper {

    /**
     * isValidDate() is used to check if a specific date is valid
     * @param string $date
     * @param string $format
     * @return bool
     */
    public static function isValidDate($date, $format = "d-m-Y") {

        // Create DateTime object from $date string
        $d = \DateTime::createFromFormat($format, $date);

        return $d && $d->format($format) === $date;
    }

    /**
     * formatDate() is used for formatting dates
     * @param $date
     * @param string $format
     * @return string
     */
    public static function formatDate($date, $format = "Y-m-d H:i:s") {

        try {

            $d = new \DateTime($date, new \DateTimeZone("UTC"));

            $d->setTimezone(new \DateTimeZone("Europe/Copenhagen"));

        } catch (\Exception $e) {
            exit($e);
        }

        return $d->format($format);
    }

    /**
     * dateInRange() is used for finding out if a specific date is in range of two other dates
     * @param $date
     * @param $startDate
     * @param $endDate
     * @return bool
     */
    public static function dateInRange($date, $startDate, $endDate) {

        try {

            $date = new \DateTime(self::formatDate($date,"d-m-Y"));
            $startDate = new \DateTime(self::formatDate($startDate,"d-m-Y"));
            $endDate = new \DateTime(self::formatDate($endDate,"d-m-Y"));

        } catch (\Exception $e) {
            exit($e);
        }

        return ($startDate <= $date && $date <= $endDate);
    }

}