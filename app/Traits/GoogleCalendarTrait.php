<?php

namespace App\Traits;

use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;

trait GoogleCalendarTrait
{

    private $calendarService;
    private $timezone = 'UTC';

    public function initGoogleCalendar(): void
    {
        $client = new Google_Client();
        $client->setAuthConfig(config('services.google.service_account_json_path'));
        $client->setScopes([Google_Service_Calendar::CALENDAR]);
        $this->calendarService = new Google_Service_Calendar($client);
    }

    public function addToGoogleCalendar($event): void
    {
        $this->initGoogleCalendar();
        $calendarId = config('services.google.calendar_id');
        $eventDescription = $this->generateEventDescription($event);

        if ($event->arrival_datetime) {
            $this->createGoogleEvent($calendarId, $event, $eventDescription, $event->arrival_datetime, "11");
        }

        if ($event->departure_meeting_time) {
            $this->createGoogleEvent($calendarId, $event, $eventDescription, $event->departure_meeting_time, "10");
        }
    }

    private function createGoogleEvent($calendarId, $event, $description, $dateTime, $colorId): void
    {
        $eventSummary = "{$event->vehicle_number} {$event->vehicle_model} {$event->vehicle_color}";
        if ($dateTime == $event->arrival_datetime) {
            $eventSummary .= " {$event->arrival_mode}";
        }

        $googleEvent = new Google_Service_Calendar_Event([
            'summary' => $eventSummary,
            'description' => $description,
            'start' => [
                'dateTime' => $dateTime->copy()->subHours(2)->format('c'),
                'timeZone' => $this->timezone,
            ],
            'end' => [
                'dateTime' => $dateTime->copy()->subHours(2)->format('c'),
                'timeZone' => $this->timezone,
            ],
            'colorId' => $colorId,
        ]);

        $this->calendarService->events->insert($calendarId, $googleEvent);
    }

    private function generateEventDescription($event): string
    {
        $description = "<strong>Contract Client:</strong> {$event->name}\n" .
            "<strong>Contract Client Email:</strong> {$event->email}\n" .
            "<strong>Name of person arriving:</strong> {$event->visitor_name}\n" .
            "<strong>Mobile of person arriving:</strong> {$event->visitor_mobile}\n" .
            "<strong>Email of person arriving:</strong> {$event->visitor_email}\n" .
            "<strong>Vehicle Number:</strong> {$event->vehicle_number}\n" .
            "<strong>Vehicle Colour:</strong> {$event->vehicle_color}\n" .
            "<strong>Vehicle Model:</strong> {$event->vehicle_model}\n" .
            "<strong>Has arrival flight:</strong> " . ($event->has_arrival_booking ? 'Yes' : 'No') . "\n";

        if (!empty($event->arrival_mode)) {
            $description .= "<strong>Flight Number:</strong> {$event->arrival_mode}\n";
        }

        if ($event->arrival_datetime) {
            $description .= "<strong>(Arriving Spain) Flight Number Date/Time:</strong> " . $this->formatDateTime($event->arrival_datetime) . "\n";
        }

        $description .= "<strong>Do you arrive with Hold Luggage:</strong> " . ($event->has_hold_luggage ? 'Yes' : 'No') . "\n";

        if ($event->departure_meeting_time) {
            $description .= "<strong>(Departing Spain NOT TAKE OFF TIME)Meeting time at Departures:</strong> " . $this->formatDateTime($event->departure_meeting_time) . "\n";
        }

        $description .= "<strong>Additional info:</strong> {$event->additional_info}";

        return $description;
    }
}
