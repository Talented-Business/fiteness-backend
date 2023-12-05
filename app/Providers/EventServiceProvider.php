<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Subscription;
use App\Observers\SubscriptionObserver;
use App\Models\Post;
use App\Observers\PostObserver;
use App\Models\Comment;
use App\Observers\CommentObserver;
use App\Benchmark;
use App\Observers\BenchmarkObserver;
use App\Observers\EventObserver;
use App\Models\Evento;
use App\Observers\EventoObserver;
use App\Company;
use App\Observers\CompanyObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \SocialiteProviders\Manager\SocialiteWasCalled::class => [
            // ... other providers
            'SocialiteProviders\\Apple\\AppleExtendSocialite@handle',
            'SocialiteProviders\\Google\\GoogleExtendSocialite@handle',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        Subscription::observe(SubscriptionObserver::class);
        Post::observe(PostObserver::class);
        Comment::observe(CommentObserver::class);
        Benchmark::observe(BenchmarkObserver::class);
        \App\Event::observe(EventObserver::class);
        Company::observe(CompanyObserver::class);
        Evento::observe(EventoObserver::class);
        \App\Models\WorkoutComment::observe(\App\Observers\WorkoutCommentObserver::class);
        \App\Models\AdminAction::observe(\App\Observers\AdminActionObserver::class);
        //
    }
}
