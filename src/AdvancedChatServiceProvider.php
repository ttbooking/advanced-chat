<?php

declare(strict_types=1);

namespace TTBooking\AdvancedChat;

use Faker\Generator;
use Illuminate\Console\Command;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use TTBooking\AdvancedChat\Faker\Extension;
use TTBooking\ViteManager\Facades\Vite;

class AdvancedChatServiceProvider extends ServiceProvider
{
    /**
     * All of the singletons that should be registered.
     *
     * @var array<string, class-string>
     */
    public array $singletons = [
        'advanced-chat' => Chat::class,
    ];

    /**
     * The commands to be registered.
     *
     * @var list<class-string<Command>>
     */
    protected array $commands = [
        Console\AddCommand::class,
        Console\AddRoomCommand::class,
        Console\InfoCommand::class,
        Console\InitCommand::class,
        Console\KickCommand::class,
        Console\ListCommand::class,
        Console\PostCommand::class,
        Console\RemoveRoomCommand::class,
        Console\SeedCommand::class,
        Console\TagCommand::class,
        Console\UntagCommand::class,
        Console\ViewCommand::class,
    ];

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->registerRoutes();
        $this->registerAssets();
        $this->registerBladeDirectives();
        $this->registerResources();

        if ($this->app->runningInConsole()) {
            $this->offerPublishing();
            $this->registerMigrations();
        }
    }

    /**
     * Register the Advanced Chat routes.
     */
    protected function registerRoutes(): void
    {
        Route::domain($this->app['config']['advanced-chat.domain'])
            ->prefix($this->app['config']['advanced-chat.path'] ?? 'advanced-chat')
            ->name('advanced-chat.')
            ->namespace('TTBooking\\AdvancedChat\\Http\\Controllers')
            ->middleware($this->app['config']['advanced-chat.middleware'] ?? ['web', 'auth'])
            ->group(fn () => $this->loadRoutesFrom(__DIR__.'/../routes/web.php'));

        require __DIR__.'/../routes/channels.php';
    }

    /**
     * Register the Advanced Chat assets.
     */
    protected function registerAssets(): void
    {
        Vite::app('advanced-chat')
            ->useHotFile('vendor/advanced-chat/hot')
            ->useBuildDirectory('vendor/advanced-chat/build')
            ->withEntryPoints(['resources/js/app.ts']);
    }

    /**
     * Register the Advanced Chat Blade directives.
     */
    protected function registerBladeDirectives(): void
    {
        Blade::directive('chat', static function (string $expression) {
            return "<?php echo TTBooking\AdvancedChat\AdvancedChat::standalone($expression)->toHtml(); ?>";
        });

        Blade::directive('winchat', static function (string $expression) {
            return "<?php echo TTBooking\AdvancedChat\AdvancedChat::windowed($expression)->toHtml(); ?>";
        });
    }

    /**
     * Register the Advanced Chat resources.
     */
    protected function registerResources(): void
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'advanced-chat');
    }

    protected function offerPublishing(): void
    {
        $this->publishes([
            __DIR__.'/../config/advanced-chat.php' => $this->app->configPath('advanced-chat.php'),
        ], ['ttbooking-config', 'advanced-chat-config', 'advanced-chat', 'config']);

        $this->publishes([
            __DIR__.'/../database/migrations' => $this->app->databasePath('migrations'),
        ], ['ttbooking-migrations', 'advanced-chat-migrations', 'advanced-chat', 'migrations']);

        $this->publishes([
            __DIR__.'/../resources/views' => $this->app->resourcePath('views/vendor/advanced-chat'),
        ], ['ttbooking-views', 'advanced-chat-views', 'advanced-chat', 'views']);

        $this->publishes([
            __DIR__.'/../public' => public_path('vendor/advanced-chat'),
        ], ['ttbooking-assets', 'advanced-chat-assets', 'advanced-chat', 'assets']);
    }

    protected function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->configure();
        $this->registerServices();
        $this->registerCommands();
        $this->registerFakerProviders();
    }

    /**
     * Setup the configuration for Advanced Chat.
     */
    protected function configure(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/advanced-chat.php', 'advanced-chat');
    }

    /**
     * Register Advanced Chat's services in the container.
     */
    protected function registerServices(): void
    {
        $this->app->when(Chat::class)->needs(Authenticatable::class)->give(static function () {
            $credentials = [
                config('advanced-chat.user_cred_key') => config('advanced-chat.user_cred_seed'),
            ];

            return Auth::getProvider()->retrieveByCredentials($credentials) ?? new User;
        });

        $this->app->alias('advanced-chat', Contracts\Chat::class);
    }

    /**
     * Register the Advanced Chat Artisan commands.
     */
    protected function registerCommands(): void
    {
        foreach ($this->commands as $command) {
            $this->app->singleton($command);
        }

        $this->commands($this->commands);
    }

    protected function registerFakerProviders(): void
    {
        $this->callAfterResolving(Generator::class,
            fn ($faker, $app) => Extension::extend($faker, $app['config']['app.faker_locale'] ?? 'en_US')
        );
    }

    /**
     * Get the services provided by the provider.
     *
     * @return list<string>
     */
    public function provides(): array
    {
        return ['advanced-chat', Contracts\Chat::class, ...$this->commands];
    }
}
