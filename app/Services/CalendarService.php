<?php

namespace App\Services;

use App\Mail\AdminBookingNotification;
use App\Mail\BookingConfirmation;
use App\Models\Calendar;
use App\Traits\GoogleCalendarTrait;
use App\Traits\DateTimeTrait;
use Illuminate\Support\Facades\Mail;

class CalendarService
{
    use GoogleCalendarTrait, DateTimeTrait;

    public function getAllEvents()
    {
        return Calendar::latest()->get();
    }

    public function createBooking(array $validatedData): array
    {
        if (!$this->isWithinBookingHours()) {
            return [
                'status' => 'error',
                'message' => $this->getBookingHoursErrorMessage()
            ];
        }

        $arrivalDateTime = $this->parseAndLocalizeDateTime($validatedData['arrival_datetime'] ?? null);
        $departureDateTime = $this->parseAndLocalizeDateTime($validatedData['departure_meeting_time'] ?? null);

        if (!$arrivalDateTime && !$departureDateTime) {
            return [
                'status' => 'error',
                'message' => 'At least one of arrival or departure time must be provided.'
            ];
        }

        if (!$this->isValidBookingTime($arrivalDateTime, $departureDateTime)) {
            return [
                'status' => 'error',
                'message' => 'Bookings must be made at least 24 hours in advance.'
            ];
        }

        $validatedData['arrival_datetime'] = $arrivalDateTime;
        $validatedData['departure_meeting_time'] = $departureDateTime;

        $event = Calendar::create($validatedData);
        $this->addToGoogleCalendar($event);

        $this->sendEmails($event);

        return [
            'status' => 'success',
            'message' => 'Booking created successfully and added to Google Calendar'
        ];
    }

    private function sendEmails($event): void
    {
        Mail::to($event->email)->send(new BookingConfirmation($event));
        Mail::to(config('mail.admin_email'))->send(new AdminBookingNotification($event));
    }
}
