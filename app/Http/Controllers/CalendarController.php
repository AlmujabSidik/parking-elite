<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalendarRequest;
use App\Services\CalendarService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class CalendarController extends Controller
{
    private $calendarService;

    public function __construct(CalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    public function index(): View
    {
        $events = $this->calendarService->getAllEvents();
        return view('calendar', compact('events'));
    }

    public function store(CalendarRequest $request): JsonResponse
    {
        try {
            $result = $this->calendarService->createBooking($request->validated());
            return response()->json($result, $result['status'] === 'success' ? 200 : 400);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
