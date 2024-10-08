<?php

namespace App\Traits;

use Carbon\Carbon;

trait DateTimeTrait
{
    private $timezone = 'UTC';
    private $weekdayStartTime = '00:00';
    private $weekdayEndTime = '18:00';
    private $weekendStartTime = '09:00';
    private $weekendEndTime = '16:00';

    public function isWithinBookingHours()
    {
        $now = Carbon::now($this->timezone);
        $dayOfWeek = $now->dayOfWeek;

        if ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::FRIDAY) {
            $startTime = Carbon::createFromTimeString($this->weekdayStartTime, $this->timezone);
            $endTime = Carbon::createFromTimeString($this->weekdayEndTime, $this->timezone);
        } else {
            $startTime = Carbon::createFromTimeString($this->weekendStartTime, $this->timezone);
            $endTime = Carbon::createFromTimeString($this->weekendEndTime, $this->timezone);
        }

        return $now->between($startTime, $endTime);
    }

    public function getBookingHoursErrorMessage()
    {
        $now = Carbon::now($this->timezone);
        $dayOfWeek = $now->dayOfWeek;

        if ($dayOfWeek >= Carbon::MONDAY && $dayOfWeek <= Carbon::FRIDAY) {
            return "Booking can only be made between {$this->weekdayStartTime} and {$this->weekdayEndTime} on weekdays.";
        } else {
            return "Booking can only be made between {$this->weekendStartTime} and {$this->weekendEndTime} on weekends.";
        }
    }

    public function isValidBookingTime($arrivalDateTime, $departureDateTime)
    {
        $now = Carbon::now($this->timezone);
        $minimumBookingTime = $now->copy()->addHours(24);

        if ($arrivalDateTime && $arrivalDateTime <= $minimumBookingTime) {
            return false;
        }

        if ($departureDateTime && $departureDateTime <= $minimumBookingTime) {
            return false;
        }

        return true;
    }

    public function parseAndLocalizeDateTime($dateTimeString)
    {
        if (!$dateTimeString) return null;
        return Carbon::createFromFormat('Y-m-d\TH:i', $dateTimeString, 'UTC')
            ->setTimezone($this->timezone);
    }

    public function formatDateTime($dateTime)
    {
        if (!$dateTime) return 'Not specified';
        return $dateTime->format('d M Y H:i');
    }
}
