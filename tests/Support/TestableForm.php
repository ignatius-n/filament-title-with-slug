<?php

namespace Blendbyte\FilamentTitleWithSlug\Tests\Support;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\MessageBag;
use Livewire\Component;

class TestableForm extends Component implements HasForms
{
    use InteractsWithForms;

    public static array $formSchema = [];

    public array $data = [];

    public $record;

    protected $listeners = ['$refresh'];

    public function mount(): void
    {
        if ($this->record) {
            $this->getSchema('form')->fill($this->record->attributesToArray());
        }
    }

    public function render()
    {
        return view('filament-title-with-slug::tests.support.testable-form');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components(static::$formSchema)
            ->statePath('data')
            ->model($this->record)
            ->operation($this->record ? 'edit' : 'create');
    }

    /**
     * Ensure the error bag is always a valid MessageBag for Livewire 4 + Filament 5 compatibility.
     */
    public function getErrorBag(): MessageBag
    {
        return parent::getErrorBag() ?? new MessageBag;
    }
}
