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
use Illuminate\Support\Facades\Redis;

class EventsSubscriber
{
    use WriteTimeParams;

    public function __construct()
    {
        //
    }

    public function handleSuccessJob($event)
    {
        Redis::hMset('success', [
            'transaction' => $event->transaction,
            'guessNumber' => $event->guessNumber,
            'randNumber' => $event->randNumber,
            'status' => 'OK',
            'param_id' => $event->paramId
        ]);
        $this->writeTimeParams($event->transaction);
    }

    public function handleFailedJob($event)
    {
        Redis::hMset('failed', [
            'transaction' => $event->transaction,
            'guessNumber' => $event->guessNumber,
            'randNumber' => $event->randNumber,
            'status' => 'Failed',
            'param_id' => $event->transaction
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
