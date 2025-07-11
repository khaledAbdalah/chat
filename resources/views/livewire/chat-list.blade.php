<div>
    <flux:input wire:model.live='search' variant="filled" placeholder="Search..." icon="magnifying-glass" />

    {{-- chat list --}}
    <div class='mt-6 space-y-2'>
        @foreach ($contacts as $userId => $messages)
            @php
                $message = $messages->first();
                $chatUser = $message->sender_id === auth()->id() ? $message->receiver : $message->sender;
                $lastMessage = $messages->sortByDesc('updated_at')->first();
            @endphp
            <a href="{{ route('chat.show', $chatUser->id) }}" wire:navigate wire:key='{{ $userId }}'
                class="flex items-center gap-2  relative shrink-0 hover:bg-zinc-100 dark:hover:bg-zinc-700 px-2 py-2 cursor-pointer rounded-md transition-all duration-200 ">
                <div
                    class="relative inline-flex items-center justify-center p-2 w-10 h-10  bg-zinc-300 rounded-full dark:bg-zinc-600">
                    <span
                        class="top-0 right-0 absolute z-20  w-3.5 h-3.5 bg-green-500 border-[3px] border-white dark:border-zinc-800 rounded-full"></span>
                    <span
                        class="font-medium text-sm text-zinc-600 dark:text-zinc-300">{{ $chatUser->initials() }}</span>
                </div>

                <div class="font-medium dark:text-white w-full">
                    <div class="line-clamp-1">{{ $chatUser->name }}</div>
                    <div class=" flex items-center justify-between text-sm text-zinc-500 dark:text-zinc-400">
                        <div class="text-sm text-zinc-500 dark:text-zinc-400 line-clamp-1">{{ $lastMessage->message }}
                        </div>
                        <span class="text-xs text-zinc-400 dark:text-zinc-500">
                            {{ $lastMessage->created_at->diffForHumans() }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
