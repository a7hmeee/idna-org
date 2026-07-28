<div>
    <form wire:submit="submit" class="space-y-4">
        @csrf

        @if ($statusMessage)
            <div class="rounded-lg bg-municipal-50 border border-municipal-200 text-municipal-700 px-4 py-3 text-sm" role="alert">
                {{ $statusMessage }}
            </div>
        @endif

        @if ($errorMessage)
            <div class="rounded-lg bg-red-50 border border-red-200 text-red-700 px-4 py-3 text-sm" role="alert">
                {{ $errorMessage }}
            </div>
        @endif

        <p class="text-sm text-text-secondary">
            @lang('auth.forgot_password_description')
        </p>

        <div>
            <label for="email" class="block text-sm font-medium text-text-primary mb-1">
                @lang('auth.email')
            </label>
            <input
                wire:model="email"
                id="email"
                type="email"
                autocomplete="email"
                autofocus
                class="block w-full rounded-lg border border-border-default bg-surface-primary px-4 py-2.5 text-sm text-text-primary placeholder-text-tertiary focus:border-municipal-500 focus:ring-2 focus:ring-municipal-200 transition-colors @error('email') border-red-300 @enderror"
                placeholder="admin@idhna.ps"
            />
            @error('email')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <button
            type="submit"
            class="w-full rounded-lg bg-municipal-600 hover:bg-municipal-700 focus:ring-2 focus:ring-municipal-500 focus:ring-offset-2 text-white font-medium py-2.5 px-4 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            wire:loading.attr="disabled"
        >
            <span wire:loading.remove>@lang('auth.send_reset_link')</span>
            <span wire:loading>@lang('auth.sending')</span>
        </button>

        <div class="text-center">
            <a href="{{ route('login') }}" class="text-sm text-municipal-600 hover:text-municipal-700 font-medium transition-colors" wire:navigate>
                @lang('auth.back_to_login')
            </a>
        </div>
    </form>
</div>
