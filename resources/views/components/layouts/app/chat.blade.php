<!DOCTYPE html>
<html lang="en" class="h-full">

<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    @vite('resources/css/app.css')
    @fluxAppearance
    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none !important;
        }

        .no-scrollbar {
            -ms-overflow-style: none !important;
            scrollbar-width: none !important;
        }
    </style>
</head>

<body class="min-h-screen bg-white dark:bg-zinc-800">
    <flux:sidebar sticky stashable
        class="w-md bg-white dark:bg-zinc-900 border-r rtl:border-r-0 rtl:border-l border-zinc-200 dark:border-zinc-700">
        <div class="flex items-center justify-between pt-4 px-2">
            <h3 class="text-lg font-semibold">Chats</h3>
            <div class="w-fit flex items-center space-x-2">
                <a wire:navigate href="{{ route('dashboard') }}">
                    <flux:icon.home />
                </a>

                {{-- light and dark mode --}}
                <flux:dropdown x-data align="end">
                    <flux:button variant="subtle" square class="group" aria-label="Preferred color scheme">
                        <flux:icon.sun x-show="$flux.appearance === 'light'" variant="mini"
                            class="text-zinc-500 dark:text-white" />
                        <flux:icon.moon x-show="$flux.appearance === 'dark'" variant="mini"
                            class="text-zinc-500 dark:text-white" />
                        <flux:icon.moon x-show="$flux.appearance === 'system' && $flux.dark" variant="mini" />
                        <flux:icon.sun x-show="$flux.appearance === 'system' && ! $flux.dark" variant="mini" />
                    </flux:button>

                    <flux:menu>
                        <flux:menu.item icon="sun" x-on:click="$flux.appearance = 'light'">Light</flux:menu.item>
                        <flux:menu.item icon="moon" x-on:click="$flux.appearance = 'dark'">Dark</flux:menu.item>
                        <flux:menu.item icon="computer-desktop" x-on:click="$flux.appearance = 'system'">System
                        </flux:menu.item>
                    </flux:menu>
                </flux:dropdown>

                {{-- menu button --}}
                <button>
                    <flux:icon.ellipsis-vertical />
                </button>
            </div>
        </div>
        <livewire:chat-list />
    </flux:sidebar>

    <div class="[grid-area:main] relative bg-zinc-50 dark:bg-zinc-800" data-flux-main>
        {{ $slot }}
    </div>

    {{-- <flux:main>
        {{ $slot }}
    </flux:main> --}}
    @fluxScripts
    @vite('resources/js/app.js')
    @stack('scripts')
</body>

</html>
