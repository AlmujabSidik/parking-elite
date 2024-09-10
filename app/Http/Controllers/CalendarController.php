<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use App\Mail\AdminBookingNotification;
use App\Mail\BookingConfirmation;
use App\Models\Calendar;
use Exception;
use Illuminate\Http\Request;
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Calendar_Event;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class CalendarController extends Controller
{

    private $calendarService;
    private $timezone = 'UTC';

    public function __construct()
    {
        $client = new Google_Client();
        $client->setAuthConfig(config('services.google.service_account_json_path'));
        $client->setScopes([Google_Service_Calendar::CALENDAR]);
        $this->calendarService = new Google_Service_Calendar($client);
    }
    public function index()
    {

        $events = Calendar::latest()->get();
        return view('calendar', compact('events'));
    }

    public function store(CalendarRequest $calendar)
    {
        try {
            $validatedData = $calendar->validated();

            if (!empty($validatedData['has_arrival_booking']) && $validatedData['has_arrival_booking'] === 'Yes') {
                $validatedData['arrival_datetime'] = $this->parseAndLocalizeDateTime($validatedData['arrival_datetime']);
            } else {
                $validatedData['arrival_datetime'] = null;
            }

            if (!empty($validatedData['departure_meeting_time'])) {
                $validatedData['departure_meeting_time'] = $this->parseAndLocalizeDateTime($validatedData['departure_meeting_time']);
            }

            $event = Calendar::create($validatedData);
            $this->addToGoogleCalendar($event);

            Mail::to($event->email)->send(new BookingConfirmation($event));
            Mail::to(config('mail.admin_email'))->send(new AdminBookingNotification($event));

            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully and added to Google Calendar'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }

    private function addToGoogleCalendar($event)
    {
        $calendarId = config('services.google.calendar_id');
        $eventSummary = $this->generateEventSummary($event);
        $eventDescription = $this->generateEventDescription($event);



        if ($event->arrival_datetime) {
            $googleEvent = new Google_Service_Calendar_Event([
                'summary' => $eventSummary,
                'description' => $eventDescription,
                'start' => [
                    'dateTime' => $event->arrival_datetime->format('c'),
                    'timeZone' => $this->timezone,
                ],
                'end' => [
                    'dateTime' => $event->arrival_datetime->format('c'),
                    'timeZone' => $this->timezone,
                ],
                'colorId' => "11", // Red for arrival
            ]);

            $this->calendarService->events->insert($calendarId, $googleEvent);
        }

        if ($event->departure_meeting_time) {
            $departureEvent = new Google_Service_Calendar_Event([
                'summary' => $eventSummary,
                'description' => $eventDescription,
                'start' => [
                    'dateTime' => $event->departure_meeting_time->format('c'),
                    'timeZone' => $this->timezone,
                ],
                'end' => [
                    'dateTime' => $event->departure_meeting_time->format('c'),
                    'timeZone' => $this->timezone,
                ],
                'colorId' => "10", // You can adjust this as needed
            ]);

            $this->calendarService->events->insert($calendarId, $departureEvent);
        }
    }

    private function generateEventSummary($event)
    {
        return "{$event->vehicle_number} {$event->vehicle_model} {$event->vehicle_color} {$event->arrival_mode}";
    }

    private function generateEventDescription($event)
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

    private function formatDateTime($dateTime)
    {
        if (!$dateTime) return 'Not specified';
        return $dateTime->format('D jS M \'y H:i');
    }

    private function parseAndLocalizeDateTime($dateTimeString)
    {
        return Carbon::createFromFormat('Y-m-d\TH:i', $dateTimeString, 'UTC')
            ->setTimezone($this->timezone);
    }
}
