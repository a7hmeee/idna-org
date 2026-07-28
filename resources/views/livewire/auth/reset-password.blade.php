<div>
    <form wire:submit="submit" class="space-y-4">
        @csrf

        @if ($errorMessage)
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm" role="alert">
                {{ $errorMessage }}
            </div>
        @endif

        <div>
            <label for="password" class="block text-sm font-medium text-text-primary mb-1">
                @lang('auth.new_password')
            </label>
            <input
                wire:model="password"
                id="password"
                type="password"
                autocomplete="new-password"
                class="block w-full rounded-lg border border-border-default bg-surface-primary px-4 py-2.5 text-sm text-text-primary placeholder-text-tertiary focus:border-municipal-500 focus:ring-2 focus:ring-municipal-200 transition-colors @error('password') border-red-300 @enderror"
                placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;"
            />
            @error('password')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="password_confirmation" class="block text-sm font-medium text-text-primary mb-1">
                @lang('auth.confirm_password')
            </label>
            <input
                wire:model="password_confirmation"
                id="password_confirmation"
                type="password"
                autocomplete="new-password"
                class="block w-full rounded-lg border border-border-default bg-surface-primary px-4 py-2.5 text-sm text-text-primary placeholder-text-tertiary focus:border-municipal-500 focus:ring-2 focus:ring-municipal-200 transition-colors @error('password_confirmation') border-red-300 @enderror"
                placeholder="&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;&#8226;"
            />
            @error('password_confirmation')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-municipal-600 hover:bg-municipal-700 focus:ring-2 focus:ring-municipal-500 focus:ring-offset-2 text-white font-medium py-2.5 px-4 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>@lang('auth.reset_password')</span>
            <span wire:loading>@lang('auth.resetting')</span>
        </button>
    </form>
</div>
