<?php
require __DIR__ . '/vendor/autoload.php';

use Livewire\Mechanisms\DataStore;

$app = Orchestra\Testbench\Foundation\Application::create(
    basePath: __DIR__,
    resolvingCallback: function ($app) {
        $app->register(Blendbyte\FilamentTitleWithSlug\FilamentTitleWithSlugServiceProvider::class);
        $app->register(Filament\FilamentServiceProvider::class);
        $app->register(Livewire\LivewireServiceProvider::class);
        $app->register(Filament\Forms\FormsServiceProvider::class);
        $app->register(Filament\Support\SupportServiceProvider::class);
        $app->register(BladeUI\Icons\BladeIconsServiceProvider::class);
        $app->register(BladeUI\Heroicons\BladeHeroiconsServiceProvider::class);
    }
);
$app->boot();

$ds1 = $app->make(DataStore::class);
$ds2 = $app->make(DataStore::class);
echo "DataStore class: " . get_class($ds1) . "\n";
echo "Is singleton: " . ($ds1 === $ds2 ? 'YES' : 'NO') . "\n";

// Create a fake component
class TestComp extends Livewire\Component {
    use Filament\Forms\Concerns\InteractsWithForms;
    
    public function getFormSchema(): array { return []; }
    public function getFormStatePath(): ?string { return 'data'; }
    public function getFormModel() { return null; }
    
    public function render() { return '<div></div>'; }
}

$comp = new TestComp;

// Test store
$storeResult = null;
try {
    \Livewire\store($comp)->set('errorBag', new Illuminate\Support\MessageBag(['test' => ['error']]));
    $storeResult = \Livewire\store($comp)->get('errorBag');
    echo "Store set/get works: " . (is_null($storeResult) ? 'NO (null)' : 'YES (' . get_class($storeResult) . ')') . "\n";
} catch (\Throwable $e) {
    echo "Store error: " . $e->getMessage() . "\n";
}

// Test getErrorBag
try {
    $bag = $comp->getErrorBag();
    echo "getErrorBag result: " . (is_null($bag) ? 'NULL' : get_class($bag)) . "\n";
} catch (\Throwable $e) {
    echo "getErrorBag error: " . $e->getMessage() . "\n";
}
