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
        $description = "Contract Client: {$event->name}\n" .
            "Contract Client Email: {$event->email}\n" .
            "Name of person arriving: {$event->visitor_name}\n" .
            "Mobile of person arriving: {$event->visitor_mobile}\n" .
            "Email of person arriving: {$event->visitor_email}\n" .
            "Vehicle Number: {$event->vehicle_number}\n" .
            "Vehicle Colour: {$event->vehicle_color}\n" .
            "Vehicle Model: {$event->vehicle_model}\n" .
            "Has arrival flight: " . ($event->has_arrival_booking ? 'Yes' : 'No') . "\n";
        if (!empty($event->arrival_mode)) {
            $description .= "Flight Number: {$event->arrival_mode}\n";
        }

        if ($event->arrival_datetime) {
            $description .= "(Arriving Spain) Flight Number Date/Time: " . $this->formatDateTime($event->arrival_datetime) . "\n";
        }

        $description .= "Do you arrive with Hold Luggage: " . ($event->has_hold_luggage ? 'Yes' : 'No') . "\n";

        if ($event->departure_meeting_time) {
            $description .= "(Departing Spain NOT TAKE OFF TIME)Meeting time at Departures: " . $this->formatDateTime($event->departure_meeting_time) . "\n";
        }

        $description .=
            "Additional info: {$event->additional_info}";

        return $description;
    }

}
