<div>
    <flux:input wire:model.live='search' variant="filled" placeholder="Search..." icon="magnifying-glass" />

    {{-- chat list --}}
    <div class='mt-6 space-y-2'>
        @foreach ($contacts as $contact)
            <a href="{{ route('chat.show', $contact->id) }}" wire:key='{{ $contact->id }}'
                class="flex items-center gap-2  relative shrink-0 hover:bg-zinc-100 dark:hover:bg-zinc-700 px-2 py-2 cursor-pointer rounded-md transition-all duration-200 ">
                <div
                    class="relative inline-flex items-center justify-center p-2 w-10 h-10  bg-zinc-300 rounded-full dark:bg-zinc-600">
                    <span
                        class="top-0 right-0 absolute z-20  w-3.5 h-3.5 bg-green-500 border-[3px] border-white dark:border-zinc-800 rounded-full"></span>
                    <span
                        class="font-medium text-sm text-zinc-600 dark:text-zinc-300">{{ $contact->initials() }}</span>
                </div>

                <div class="font-medium dark:text-white w-full">
                    <div class="flex items-center justify-between">

                        <div class="line-clamp-1">{{ $contact->name }}</div>
                        @if ($contact->unread_count)
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 ms-2 text-xs font-semibold text-blue-800 bg-blue-200 rounded-full">
                                {{ $contact->unread_count }}
                            </span>
                        @endif
                    </div>
                    <div class=" flex items-center justify-between text-sm text-zinc-500 dark:text-zinc-400">
                        <div class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-1">{{ $contact->lastMessage->message }}
                        </div>
                        <span class="text-[10px] text-zinc-400 dark:text-zinc-500">
                            {{ $contact->lastMessage->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
