<x-filament-forms::field-wrapper
	:id="$getId()"
	:label="$getLabel()"
	:label-sr-only="$isLabelHidden()"
	:helper-text="$getHelperText()"
	:hint="$getHint()"
	:hint-icon="$getHintIcon()"
	:required="$isRequired()"
	:state-path="$getStatePath()"
	class="-mt-3 filament-seo-slug-input-wrapper"
>
	<div
		x-data="{
			context: '{{ $getContext() }}', // edit or create
			state: $wire.entangle('{{ $getStatePath() }}'), // current slug value
			statePersisted: '', // slug value received from db
			stateInitial: '', // slug value before modification
			editing: false,
			modified: false,
			initModification: function() {

				this.stateInitial = this.state;

				if(!this.statePersisted) {
					this.statePersisted = this.state;
				}

				this.editing = true;

				setTimeout(() => $refs.slugInput.focus(), 75);
				{{--$nextTick(() => $refs.slugInput.focus());--}}

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

		<div
			{{ $attributes->merge($getExtraAttributes())->class(['flex gap-4 items-center justify-between group text-sm filament-forms-text-input-component']) }}
		>

			@if($getReadOnly())

				<span class="flex">
					<span class="mr-1">{{ $getLabelPrefix() }}</span>
					<span class="text-gray-400">{{ $getFullBaseUrl() }}</span>
					<span class="text-gray-400 font-semibold">{{ $getState() }}</span>
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

			@else

				<span
					 class="
						@if(!$getState()) flex items-center gap-1 @endif
					"
				>

					<span>{{ $getLabelPrefix() }}</span>

					<span
						x-text="!editing ? '{{ $getFullBaseUrl() }}' : '{{ $getBasePath() }}'"
						class="text-gray-400"
					></span>


					<x-filament::link
						x-show="!editing"
						x-on:click.prevent="initModification()"
						icon="heroicon-m-pencil-square"
						icon-position="after"
						tooltip="{{ trans('filament-title-with-slug::package.permalink_action_edit') }}"
					>
						<span class="mr-1">{{ $getState() }}</span>
					</x-filament::link>



					@if($getSlugLabelPostfix())
						<span
							x-show="!editing"
							class="ml-0.5 text-gray-400"
						>{{ $getSlugLabelPostfix() }}</span>
					@endif

					<span x-show="!editing && context !== 'create' && modified"> [{{ trans('filament-title-with-slug::package.permalink_status_changed') }}]</span>

				</span>

				<div
					class="flex-1 mx-2"
					x-show="editing"
					style="display: none;"
				>

				<div class="fi-input-wrp">
                    <div class="fi-input-wrp-content-ctn">
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
							{{ $getExtraInputAttributeBag()->class([
								'fi-input text-sm font-semibold',
								'border-danger-600 ring-danger-600' => $errors->has($getStatePath())])
							}}
						/>
                    </div>
					</div>
				</div>

				<div
					x-show="editing"
					class="flex gap-2 items-center"
					style="display: none;"
				>

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

				<span
					x-show="context === 'edit'"
					class="flex items-center space-x-2"
				>

					@if($getSlugInputUrlVisitLinkVisible())
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
					@endif

			</span>

			@endif

		</div>

	</div>

</x-filament-forms::field-wrapper>
