<?php

namespace App\Jobs\Middleware;

use Enlightn\Enlightn\Analyzers\Concerns\DetectsRedis;
use Illuminate\Support\Facades\Redis;

class RateLimited
{
    use DetectsRedis;

    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        if ($this->appUsesRedis()) {
            Redis::throttle('key')
                ->block(0)
                ->allow(1)
                ->every(15)
                ->then(
                    function () use ($job, $next) {
                        // Lock obtained...
                        $next($job);
                    }, function () use ($job) {
                        // Could not obtain lock...
                        $job->release(5);
                    }
                );
        } else {
            $next($job);
        }
    }
}
