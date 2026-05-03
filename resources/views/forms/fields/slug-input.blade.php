<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
    class="filament-seo-slug-input-wrapper"
>
    @if($getSlugReadOnly())
        <div class="fi-input-wrp fts-slug-row">
            <span class="fts-slug-meta fts-slug-meta--flex1">
                <span>{{ $getLabelPrefix() }}</span>
                <span>{{ $getFullBaseUrl() }}</span>
                <span class="fts-slug-value fts-truncate" style="flex: 1; min-width: 0;">{{ $getState() }}</span>
            </span>

            @if($getSlugInputUrlVisitLinkVisible() && $getRecordUrl())
                <span class="fts-slug-visit" style="margin-left: auto; flex-shrink: 0;">
                    <x-filament::link
                        :href="$getRecordUrl()"
                        target="_blank"
                        size="sm"
                        icon="heroicon-m-arrow-top-right-on-square"
                        icon-position="after"
                    >
                        {{ $getVisitLinkLabel() }}
                    </x-filament::link>
                </span>
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
            <div class="fi-input-wrp fts-slug-row">
                <span class="fts-slug-meta" :style="editing ? 'flex: 0 1 auto;' : 'flex: 1; min-width: 0; overflow: hidden; white-space: nowrap;'">
                    <span>{{ $getLabelPrefix() }}</span>

                    <span x-text="!editing ? '{{ $getFullBaseUrl() }}' : '{{ $getBasePath() }}'"></span>

                    <a
                        href="#"
                        role="button"
                        title="{{ trans('filament-title-with-slug::package.permalink_action_edit') }}"
                        x-on:click.prevent="initModification()"
                        x-show="!editing"
                        class="fts-slug-edit-link"
                        :class="context !== 'create' && modified ? 'fts-slug-edit-link--modified' : ''"
                        style="flex: 1; min-width: 0; overflow: hidden; display: flex;"
                    >
                        <span style="overflow: hidden; text-overflow: ellipsis; white-space: nowrap; min-width: 0;">{{ $getState() }}</span>

                        <x-heroicon-m-pencil-square
                            stroke-width="2"
                            class="text-primary-600 dark:text-primary-400"
                            style="width: 1rem; height: 1rem; flex-shrink: 0;"
                        />

                        <span class="fts-sr-only">{{ trans('filament-title-with-slug::package.permalink_action_edit') }}</span>
                    </a>

                    @if($getSlugLabelPostfix())
                        <span x-show="!editing" style="margin-left: 0.125rem;">{{ $getSlugLabelPostfix() }}</span>
                    @endif

                    <span x-show="!editing && context !== 'create' && modified"> [{{ trans('filament-title-with-slug::package.permalink_status_changed') }}]</span>
                </span>

                <div class="fts-slug-input-wrapper" x-show="editing" style="display: none;">
                    <input
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
                        {!! $errors->has($getStatePath()) ? 'aria-invalid="true"' : null !!}
                        {{ $getExtraInputAttributeBag()->class(['fts-slug-input']) }}
                    />
                </div>

                <div class="fts-slug-actions" x-show="editing" style="display: none;">
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
                    <span class="fts-slug-visit" x-show="!editing" style="margin-left: auto; flex-shrink: 0;">
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
