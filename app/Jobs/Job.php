<?php

namespace App\Jobs;

use App\Events\FailedExceptionEvent;
use App\Events\FailedJobEvent;
use App\Events\SuccessJobEvent;
use App\Models\Param;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Redis;

class Job implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $args = [
        'backoff' => 0,
        'tries' => 100,
        'guessNumber' => 50,
        'range' => [
            'start' => 0,
            'end' => 100
        ]
    ];
    protected int $transaction;
    protected int $randNumber;
    public $tries = 100;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($args)
    {
        if (sizeof($args) > 0) {
            $this->args = array_merge($this->args, $args);
        }
        $this->tries = $this->args['tries'];
        $this->transaction = time();
        Redis::hMset($this->transaction . '_params',[
            'params' => json_encode($this->args),
            'startDateTime' => date("Y-m-d H:i:s")
        ]);
        $params = '';
        array_walk_recursive($this->args, function($value, $key) use (&$params) {
            $params .= $key . ' = ' . $value . ' ';
        });
        Redis::rPush($this->transaction, 'Params: ' . $params);
        Redis::rPush($this->transaction, 'Start date: ' . date("Y-m-d H:i:s"));
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->randNumber = mt_rand(
            $this->args['range']['start'],
            $this->args['range']['end']
        );
        if ($this->randNumber != $this->args['guessNumber']) {
            event(new FailedJobEvent(
                $this->randNumber,
                $this->args['guessNumber'],
                $this->transaction,
            ));
            $message = [
                'message' => 'Failed. ' . $this->randNumber . ' is not ' . $this->args['guessNumber'],
                'transaction' => $this->transaction
            ];
            throw new Exception(json_encode($message, true));
        } else {
            event(new SuccessJobEvent(
                $this->randNumber,
                $this->args['guessNumber'],
                $this->transaction
            ));
        }
    }

    public function failed(Exception $exception)
    {
        event(new FailedExceptionEvent($exception));
    }

    public function backoff()
    {
        return $this->args['backoff'];
    }
}
