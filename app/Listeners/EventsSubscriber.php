<?php

namespace App\Listeners;

use App\Events\AfterJobEvent;
use App\Events\FailedExceptionEvent;
use App\Events\FailedJobEvent;
use App\Events\JobEvent;
use App\Events\SuccessJobEvent;
use App\Models\Log;
use App\Traits\WriteTimeParams;
use Exception;
use Illuminate\Queue\Events\JobProcessing;

class EventsSubscriber
{
    use WriteTimeParams;

    public function __construct()
    {
        //
    }

    public function handleSuccessJob($event)
    {
        Log::create([
            'transaction' => $event->transaction,
            'guessNumber' => $event->guessNumber,
            'randNumber' => $event->randNumber,
            'status' => 'OK',
            'param_id' => $event->paramId
        ]);
        $this->writeTimeParams($event->paramId);
    }

    public function handleFailedJob($event)
    {
        Log::create([
            'transaction' => $event->transaction,
            'guessNumber' => $event->guessNumber,
            'randNumber' => $event->randNumber,
            'status' => 'Failed',
            'param_id' => $event->paramId
        ]);
    }


    public function handleFailedException($event)
    {
        try {
            $message = json_decode($event->exception->getMessage(), true);
            $this->writeTimeParams($message['idParam']);
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
    }

    public function subscribe($events)
    {
        $events->listen(
            SuccessJobEvent::class,
            [EventsSubscriber::class, 'handleSuccessJob']
        );

        $events->listen(
            FailedJobEvent::class,
            [EventsSubscriber::class, 'handleFailedJob']
        );

        $events->listen(
            FailedExceptionEvent::class,
            [EventsSubscriber::class, 'handleFailedException']
        );



//        return [
//            SuccessJobEvent::class => 'handleSuccessJob',
//            FailedJobEvent::class => 'handleFailedJob',
//            WriteTimeParamsEvent::class => 'handleWriteTimeParams',
//
//        ];
    }
}
