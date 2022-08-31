<?php

namespace App\Services;

use App\Http\Resources\LogsResource;
use App\Jobs\Job;
use App\Models\Log;
use App\Models\Param;

class QueueControllerService implements QueueControllerServiceInterface
{

    public function start($request)
    {
        $args = [];
        $result = '';
        if ($request->has('backoff')) {
            $args['backoff'] = $request->backoff;
        }
        if ($request->has('tries')) {
            $args['tries'] = $request->tries;
        }
        if ($request->has('guess_number')) {
            $args['guessNumber'] = $request->guess_number;
        }
        if ($request->has('range')) {
            $args['range'] = $request->range;
        }
        Job::dispatch($args);
        if (!empty($args)) {
            $result = ' Args:';
            array_walk_recursive($args, function ($item, $key) use (&$result) {
                $result .= ' ' . $key . ' = ' . $item;
            });
        }

        return response('Started, transaction = ' . time() . $result ?? '', 200);
    }
}
