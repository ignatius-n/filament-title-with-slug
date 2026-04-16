<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    class="filament-seo-slug-input-wrapper"
>
    @if($getSlugReadOnly())
        <div class="fi-input-wrp flex items-center justify-between gap-4 px-3 py-1.5 leading-6 text-sm">
            <span class="flex items-center gap-1 flex-1 min-w-0 text-gray-500 dark:text-gray-400">
                <span class="shrink-0">{{ $getLabelPrefix() }}</span>
                <span class="shrink-0">{{ $getFullBaseUrl() }}</span>
                <span class="font-semibold text-gray-950 dark:text-white truncate">{{ $getState() }}</span>
            </span>

            @if($getSlugInputUrlVisitLinkVisible())
                <x-filament::link
                    :href="$getRecordUrl()"
                    target="_blank"
                    size="sm"
                    icon="heroicon-m-arrow-top-right-on-square"
                    icon-position="after"
                >
                    {{ $getVisitLinkLabel() }}
                </x-filament::link>
            @endif
        </div>
    @else
        <div
            x-data="{
                context: '{{ $getContext() }}',
                state: $wire.entangle('{{ $getStatePath() }}'),
                statePersisted: '',
                stateInitial: '',
                editing: false,
                modified: false,
                initModification: function() {
                    this.stateInitial = this.state;

                    if(!this.statePersisted) {
                        this.statePersisted = this.state;
                    }

                    this.editing = true;

                    setTimeout(() => $refs.slugInput.focus(), 75);
                },
                submitModification: function() {
                    if(!this.stateInitial) {
                        this.state = '';
                    }
                    else {
                        this.state = this.stateInitial;
                    }

                    $wire.set('{{ $getStatePath() }}', this.state)
                    this.detectModification();
                    this.editing = false;
               },
               cancelModification: function() {
                    this.stateInitial = this.state;
                    this.detectModification();
                    this.editing = false;
               },
               resetModification: function() {
                    this.stateInitial = this.statePersisted;
                    this.detectModification();
               },
               detectModification: function() {
                    this.modified = this.stateInitial !== this.statePersisted;
               },
            }"
            x-on:submit.document="modified = false"
        >
            <div class="fi-input-wrp flex items-center justify-between gap-4 px-3 py-1.5 leading-6 text-sm">
                <span class="flex items-center gap-1 text-gray-500 dark:text-gray-400">
                    <span>{{ $getLabelPrefix() }}</span>

                    <span
                        x-text="!editing ? '{{ $getFullBaseUrl() }}' : '{{ $getBasePath() }}'"
                    ></span>

                    <a
                        href="#"
                        role="button"
                        title="{{ trans('filament-title-with-slug::package.permalink_action_edit') }}"
                        x-on:click.prevent="initModification()"
                        x-show="!editing"
                        class="cursor-pointer font-semibold text-gray-950 dark:text-white inline-flex items-center justify-center hover:underline gap-1"
                        :class="context !== 'create' && modified ? 'text-gray-600 bg-gray-100 dark:text-gray-400 dark:bg-gray-700 px-1 rounded-md' : ''"
                    >
                        <span class="mr-1">{{ $getState() }}</span>

                        <x-heroicon-m-pencil-square
                            stroke-width="2"
                            class="h-4 w-4 text-primary-600 dark:text-primary-400"
                        />

                        <span class="sr-only">{{ trans('filament-title-with-slug::package.permalink_action_edit') }}</span>
                    </a>

                    @if($getSlugLabelPostfix())
                        <span x-show="!editing" class="ml-0.5">{{ $getSlugLabelPostfix() }}</span>
                    @endif

                    <span x-show="!editing && context !== 'create' && modified"> [{{ trans('filament-title-with-slug::package.permalink_status_changed') }}]</span>
                </span>

                <div class="flex-1 mx-2" x-show="editing" style="display: none;">
                    <x-filament::input.wrapper :valid="! $errors->has($getStatePath())">
                        <x-filament::input
                            type="text"
                            x-ref="slugInput"
                            x-model="stateInitial"
                            x-bind:disabled="!editing"
                            x-on:keydown.enter="submitModification()"
                            x-on:keydown.escape="cancelModification()"
                            {!! ($autocomplete = $getAutocomplete()) ? "autocomplete=\"{$autocomplete}\"" : null !!}
                            id="{{ $getId() }}"
                            {!! ($placeholder = $getPlaceholder()) ? "placeholder=\"{$placeholder}\"" : null !!}
                            {!! $isRequired() ? 'required' : null !!}
                            {{ $getExtraInputAttributeBag()->class(['fi-input text-sm font-semibold']) }}
                        />
                    </x-filament::input.wrapper>
                </div>

                <div x-show="editing" class="flex gap-2 items-center" style="display: none;">
                    <x-filament::button x-on:click.prevent="submitModification()">
                        {{ trans('filament-title-with-slug::package.permalink_action_ok') }}
                    </x-filament::button>

                    <x-filament::icon-button
                        icon="heroicon-o-arrow-path"
                        size="sm"
                        color="info"
                        x-show="context === 'edit' && modified"
                        x-on:click.prevent="resetModification()"
                        :label="trans('filament-title-with-slug::package.permalink_action_reset')"
                        :tooltip="trans('filament-title-with-slug::package.permalink_action_reset')"
                    />

                    <x-filament::icon-button
                        icon="heroicon-o-x-mark"
                        size="sm"
                        color="danger"
                        x-on:click.prevent="cancelModification()"
                        :label="trans('filament-title-with-slug::package.permalink_action_cancel')"
                        :tooltip="trans('filament-title-with-slug::package.permalink_action_cancel')"
                    />
                </div>

                @if($getSlugInputUrlVisitLinkVisible() && $getRecordUrl())
                    <span class="flex items-center space-x-2 shrink-0">
                        <template x-if="!editing">
                            <x-filament::link
                                :href="$getRecordUrl()"
                                target="_blank"
                                size="sm"
                                icon="heroicon-m-arrow-top-right-on-square"
                                icon-position="after"
                            >
                                {{ $getVisitLinkLabel() }}
                            </x-filament::link>
                        </template>
                    </span>
                @endif
            </div>
        </div>
    @endif
</x-dynamic-component>
