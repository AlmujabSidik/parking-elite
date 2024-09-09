<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
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
            if (!empty($validatedData['arrival_datetime'])) {
                $validatedData['arrival_datetime'] = Carbon::createFromFormat('Y-m-d\TH:i', $validatedData['arrival_datetime'], 'Europe/Madrid')->utc();
            }
            if (!empty($validatedData['departure_meeting_time'])) {
                $validatedData['departure_meeting_time'] = Carbon::createFromFormat('Y-m-d\TH:i', $validatedData['departure_meeting_time'], 'Europe/Madrid')->utc();
            }

            $event = Calendar::create($validatedData);
            $this->addToGoogleCalendar($event);

            Mail::to($event->email)->send(new BookingConfirmation($event));
            return response()->json([
                'status' => 'success',
                'message' => 'Booking created successfully and added to Google Calendar'
            ], 200);
        } catch (ValidationException $exception) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $exception->errors()
            ], 422);
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
        $timezone = 'Europe/Madrid';
        $description = "Contract Client: {$event->name}\nContract Client Email: {$event->email}\nName of Person Arriving: {$event->visitor_name}\nEmail of Person Arriving: {$event->visitor_email}\nMobile Number of Person Arriving: {$event->visitor_mobile}\nVehicle Number: {$event->vehicle_number}\nVehicle Color: {$event->vehicle_color}\nVehicle Model: {$event->vehicle_model}\nHas Arrival Flight: {$event->has_arrival_booking}\nFlight Number: {$event->arrival_mode}\nArriving Spain: {$event->arrival_datetime}\nHas Hold Luggage: {$event->has_hold_luggage}\nHas Returning Flight: {$event->has_departure_booking}\nReturning Flight Date: {$event->departure_meeting_time}\nInformation: {$event->additional_info}";

        if ($event->arrival_datetime) {
            $startEvent = new Google_Service_Calendar_Event([
                'summary' => $event->vehicle_number . " " . $event->vehicle_model . " " . $event->vehicle_color . " " . $event->arrival_mode,
                'description' => $description,
                'start' => [
                    'dateTime' => $event->arrival_datetime->format('c'),
                    'timeZone' => $timezone,
                ],
                'end' => [
                    'dateTime' => $event->arrival_datetime->addHour()->format('c'),
                    'timeZone' => $timezone,
                ],
                'colorId' => '11', // Merah
            ]);
            $this->calendarService->events->insert($calendarId, $startEvent);
        }

        // Event untuk end date dan time
        if ($event->departure_meeting_time) {
            $endEvent = new Google_Service_Calendar_Event([
                'summary' => $event->vehicle_number . " " . $event->vehicle_model . " " . $event->vehicle_color . " " . $event->arrival_mode,
                'description' => $description,
                'start' => [
                    'dateTime' => $event->departure_meeting_time->format('c'),
                    'timeZone' => $timezone,
                ],
                'end' => [
                    'dateTime' => $event->departure_meeting_time->addHour()->format('c'),
                    'timeZone' => $timezone
                ],
                'colorId' => '10', // Hijau
            ]);
            $this->calendarService->events->insert($calendarId, $endEvent);
        }
    }
}
