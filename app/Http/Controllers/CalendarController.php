<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEventRequest;
use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class CalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $events = [];
        $event = Event::where('id', 1)->get()->toArray();

        if (! empty($event)) {
            $event = $event[0];
            $events = $this->formatEvents($event);

            $days = explode(',', $event['days']);
            $event['start_date'] = Carbon::createFromFormat('Y-m-d', $event['start_date'])->format('m/d/Y');
            $event['end_date'] = Carbon::createFromFormat('Y-m-d', $event['end_date'])->format('m/d/Y');
        }

        return view('calendar', [
            'event' => $event ?? [],
            'events' => $events ?? [],
            'days' => $days ?? []
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEventRequest $request)
    {
        $success = 0;
        $error = '';

        try {
            $event = Event::updateOrCreate(
                ['id' => 1],
                [
                    'event_name' => $request->validated()['event'],
                    'days' => implode(',', array_keys($request->validated()['days'])),
                    'start_date' => Carbon::createFromFormat('m/d/Y', $request->validated()['startDate'])->format('Y-m-d'),
                    'end_date' => Carbon::createFromFormat('m/d/Y', $request->validated()['endDate'])->format('Y-m-d')
                ]
            );

            $success = 1;
        } catch (\Exception $ex) {
            $error = $ex->getMessage();
        }

        return response()->json([
            'success' => $success,
            'error' => $error,
            'data' => [
                'event' => $event,
                'events' => $this->formatEvents($event->toArray())
            ]
        ]);
    }

    public function formatEvents($event) {
        $events = [];
        $days = explode(',', $event['days']);
        $dateRange = CarbonPeriod::create($event['start_date'], $event['end_date']);

        foreach ($dateRange as $date) {
            if (in_array(strtolower($date->format('l')), $days)) {
                $events[] = (object) [
                    'title' => $event['event_name'],
                    'start' => $date->format('Y-m-d'),
                    'end' => $date->format('Y-m-d')
                ];
            }
        }

        return $events;
    }
}
