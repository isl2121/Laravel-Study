<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Events\Dispatche;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
class EventServiceProvider extends ServiceProvider

{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
		\App\Events\ArticlesEvent::class => [
			\App\Listeners\ArticlesEventListener::class,	
		],
		\Illuminate\Auth\Events\Login::class=> [
			\App\Listeners\UsersEvenetListener::class	
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
        
        \Event::listen(
			'article.created',
			\App\Listeners\ArticlesEventiListner::class
		);
    }
}
