<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

use Illuminate\Support\Facades\Event;
use App\Events\Gamification\UserActivityCompleted;
use App\Events\Gamification\XPAwarded;
use App\Events\Gamification\StreakIncremented;
use App\Services\Gamification\GamificationOrchestrator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(UserActivityCompleted::class, [GamificationOrchestrator::class, 'handleUserActivity']);
        Event::listen(XPAwarded::class, [GamificationOrchestrator::class, 'handleXpAwarded']);
        Event::listen(StreakIncremented::class, [GamificationOrchestrator::class, 'handleStreakIncremented']);

        \Illuminate\Database\Eloquent\Relations\Relation::enforceMorphMap([
            'vocabulary' => \App\Models\VocabularyWord::class ?? 'App\Models\VocabularyWord',
            'grammar' => \App\Models\Grammar::class ?? 'App\Models\Grammar',
            'reading' => \App\Models\Reading::class ?? 'App\Models\Reading',
            'quiz' => \App\Models\Quiz::class ?? 'App\Models\Quiz',
            'writing' => \App\Models\Writing::class ?? 'App\Models\Writing',
            'ielts' => \App\Models\Ielts::class ?? 'App\Models\Ielts',
            'iq' => \App\Models\Iq::class ?? 'App\Models\Iq',
            'logic' => \App\Models\Logic::class ?? 'App\Models\Logic',
        ]);

        RateLimiter::for('auth_endpoints', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });
        
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
    }
}
