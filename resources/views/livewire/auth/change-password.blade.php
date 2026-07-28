<div>
    <div class="max-w-lg mx-auto">
        <div class="bg-surface-primary rounded-xl shadow-sm border border-border-light p-6">
            <h2 class="text-lg font-semibold text-text-primary mb-1">@lang('auth.change_password')</h2>
            <p class="text-sm text-text-tertiary mb-6">@lang('auth.change_password_description')</p>

            <form wire:submit="submit" class="space-y-4">
                @csrf

                @if ($errorMessage)
                    <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm" role="alert">
                        {{ $errorMessage }}
                    </div>
                @endif

                <div>
                    <label for="current_password" class="block text-sm font-medium text-text-primary mb-1">
                        @lang('auth.current_password')
                    </label>
                    <input
                        wire:model="current_password"
                        id="current_password"
                        type="password"
                        autocomplete="current-password"
                        class="block w-full rounded-lg border border-border-default bg-surface-primary px-4 py-2.5 text-sm text-text-primary placeholder-text-tertiary focus:border-municipal-500 focus:ring-2 focus:ring-municipal-200 transition-colors @error('current_password') border-red-300 @enderror"
                    />
                    @error('current_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password" class="block text-sm font-medium text-text-primary mb-1">
                        @lang('auth.new_password')
                    </label>
                    <input
                        wire:model="new_password"
                        id="new_password"
                        type="password"
                        autocomplete="new-password"
                        class="block w-full rounded-lg border border-border-default bg-surface-primary px-4 py-2.5 text-sm text-text-primary placeholder-text-tertiary focus:border-municipal-500 focus:ring-2 focus:ring-municipal-200 transition-colors @error('new_password') border-red-300 @enderror"
                    />
                    @error('new_password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="new_password_confirmation" class="block text-sm font-medium text-text-primary mb-1">
                        @lang('auth.confirm_new_password')
                    </label>
                    <input
                        wire:model="new_password_confirmation"
                        id="new_password_confirmation"
                        type="password"
                        autocomplete="new-password"
                        class="block w-full rounded-lg border border-border-default bg-surface-primary px-4 py-2.5 text-sm text-text-primary placeholder-text-tertiary focus:border-municipal-500 focus:ring-2 focus:ring-municipal-200 transition-colors @error('new_password_confirmation') border-red-300 @enderror"
                    />
                    @error('new_password_confirmation')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-2">
                    <button
                        type="submit"
                        class="rounded-lg bg-municipal-600 hover:bg-municipal-700 focus:ring-2 focus:ring-municipal-500 focus:ring-offset-2 text-white font-medium py-2.5 px-6 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>@lang('auth.save_changes')</span>
                        <span wire:loading>@lang('auth.saving')</span>
                    </button>

                    <a
                        href="{{ route('dashboard') }}"
                        class="rounded-lg border border-border-default bg-surface-primary hover:bg-surface-secondary text-text-primary font-medium py-2.5 px-6 transition-colors"
                        wire:navigate
                    >
                        @lang('auth.cancel')
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
