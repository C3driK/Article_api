<?php

namespace App\Listeners;

use Exception;
use App\Events\ArticleCreated;
use App\Events\ArticleUpdated;
use App\Services\CanAnalyzeSentiment;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AnalyseArticleSentiment implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the listener may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 3;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(private CanAnalyzeSentiment $analyzer)
    {
    }

    /**
     * Handle the event.
     *
     * @param \App\Events\ArticleCreated|\App\Events\ArticleUpdated $event
     *
     * @return void
     */
    public function handle($event)
    {
        $sentiment = $this->analyzer->analyze($event->article->body);

        if (! $sentiment) {
            throw new Exception("Could not analyze the sentiment of the article [{$event->article->uuid}]");
        }

        $this->ensureSentimentIsValid($sentiment);

        $event->article->update(['sentiment' => $sentiment]);
    }

    private function ensureSentimentIsValid()
    {
        //
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     *
     * @return array
     */
    public function subscribe($events)
    {
        return [
            ArticleCreated::class => 'handle',
            ArticleUpdated::class => 'handle',
        ];
    }
}
