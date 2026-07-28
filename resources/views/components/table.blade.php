@props([
    'headers' => [],
    'striped' => false,
    'hover' => true,
    'compact' => false,
])

<div {{ $attributes->class(['bg-surface rounded-2xl border border-border overflow-hidden']) }}>
    @if (isset($header))
        <div class="px-6 py-4 border-b border-border flex items-center justify-between">
            {{ $header }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full">
            @if (is_array($headers) && count($headers))
                <thead>
                    <tr class="border-b border-border bg-background/50">
                        @foreach ($headers as $column)
                            <th scope="col" class="text-start {{ $compact ? 'px-4 py-3' : 'px-6 py-4' }} text-xs font-bold text-text-tertiary uppercase tracking-wider">
                                {{ $column }}
                            </th>
                        @endforeach
                    </tr>
                </thead>
            @elseif (isset($customHeaders))
                <thead>
                    <tr class="border-b border-border bg-background/50">
                        {{ $customHeaders }}
                    </tr>
                </thead>
            @endif
            <tbody>
                {{ $slot }}
            </tbody>
        </table>
    </div>

    @if (isset($footer))
        <div class="{{ $compact ? 'px-4 py-3' : 'px-6 py-4' }} border-t border-border bg-background/30">
            {{ $footer }}
        </div>
    @endif
</div>


