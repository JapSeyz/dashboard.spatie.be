<?php

namespace App\Components\GoogleCalendar;

use App\Calendar;
use App\Components\GoogleCalendar\Events\EventsFetched;
use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use Spatie\GoogleCalendar\Event;

class FetchGoogleCalendarEvents extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'dashboard:calendar';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Google Calendar events.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $events = collect(Calendar::get())
            ->map(function (Event $event) {
                return [
                    'name' => $event->name,
                    'date' => Carbon::createFromFormat('Y-m-d H:i:s', $event->getSortDate())->format(DateTime::ATOM),
                ];
            })
            ->unique('name')
            ->toArray();

        event(new EventsFetched($events));
    }
}
