<style>
    @keyframes bounce-dot {

        0%,
        80%,
        100% {
            transform: translateY(0);
        }

        40% {
            transform: translateY(-6px);
        }
    }

    .animate-bounce-dot {
        animation: bounce-dot 1s infinite ease-in-out;
    }

    .dot-1 {
        animation-delay: 0s;
    }

    .dot-2 {
        animation-delay: 0.2s;
    }

    .dot-3 {
        animation-delay: 0.4s;
    }
</style>

<div class="flex flex-col h-screen">
    <div id="chat-container" class="flex-1 overflow-y-auto no-scrollbar">

        {{-- chat header --}}
        <flux:header
            class="bg-zinc-50 dark:bg-zinc-900 border-b border-zinc-200 dark:border-zinc-700 flex items-center justify-between py-3">
            <div class="flex items-center gap-2 relative px-2 rounded-md">
                <div class="mr-2">
                    <a wire:navigate href="{{ route('chat.index') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                        </svg>
                    </a>
                </div>
                <div
                    class="relative inline-flex items-center justify-center w-9 h-9 overflow-hidden bg-zinc-300 rounded-full dark:bg-zinc-600">
                    <span class="font-medium text-zinc-600 dark:text-zinc-300">{{ $receiver->initials() }}</span>
                </div>
                <div class="font-medium dark:text-white">
                    <div>{{ $receiver->name }}</div>
                </div>
            </div>

            {{-- search in chat  --}}
            <div>
                <flux:icon.magnifying-glass />
            </div>

        </flux:header>

        {{-- chats messages --}}

        <div class="flex flex-col  px-6 lg:px-8 py-6">

            @foreach ($messages as $chat)
                @if ($chat->sender_id == $user->id)
                    {{-- Sender messages --}}
                    <div x-data="{ isHovered: false, isMenuClicked: false }" @mouseenter="isHovered = true"
                        @mouseleave="if(!isMenuClicked) isHovered = false"
                        class=" flex items-start justify-end gap-2.5 mt-6 relative ">
                        <div class="relative">
                            <div
                                class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 pb-2 bg-blue-500 text-white rounded-s-xl rounded-ee-xl dark:bg-blue-600">
                                <p class="text-sm font-normal">{{ $chat->message }}</p>
                                <div class="flex items-center justify-between mt-3 text-[10px]">
                                    {{-- <span>Delivered</span> --}}
                                    <span>{{ $chat->created_at->format('h:i A') }}</span>
                                </div>
                            </div>

                            <div class="absolute -left-10 top-1/2 -translate-y-1/2">
                                <div class="relative">
                                    <button x-show="isHovered || isMenuClicked" x-transition x-cloak
                                        @click="isMenuClicked = !isMenuClicked"
                                        @click.outside='isMenuClicked = false, isHovered = false'
                                        @mouseenter="showMenuByHover = true" @mouseleave="showMenuByHover = false"
                                        class="p-2">
                                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 4 15">
                                            <path
                                                d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                        </svg>
                                    </button>
                                    <div x-show='isMenuClicked' x-transition x-cloak
                                        class="absolute right-full top-1/2 -translate-y-1/2 mr-2 p-2 w-40 z-20 bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg shadow divide-y divide-zinc-100 dark:divide-zinc-600">
                                        <ul class=" text-sm text-zinc-700 dark:text-zinc-200">
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Reply</a>
                                            </li>
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Forward</a>
                                            </li>
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Copy</a>
                                            </li>
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Receiver messages --}}
                    <div x-data="{ isHovered: false, isMenuClicked: false }" @mouseenter="isHovered = true"
                        @mouseleave="if(!isMenuClicked) isHovered = false" class=" flex items-start gap-2.5 mt-6">
                        <div class="relative">
                            {{-- message --}}
                            <div
                                class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 pb-2 bg-zinc-200 rounded-e-xl rounded-es-xl dark:bg-zinc-700">
                                <p class="text-sm text-zinc-900 dark:text-white">{{ $chat->message }}</p>
                                <div class="flex justify-end mt-2 text-[10px] text-zinc-500 dark:text-zinc-400">
                                    <span>{{ $chat->created_at->format('h:i A') }}</span>
                                </div>
                            </div>

                            {{-- message options --}}
                            <div class=" absolute -right-10 top-1/2 -translate-y-1/2">
                                <div class="relative">
                                    <button x-show="isHovered || isMenuClicked" x-transition x-cloak
                                        @click="isMenuClicked = !isMenuClicked"
                                        @click.outside='isMenuClicked = false, isHovered = false'
                                        @mouseenter="showMenuByHover = true" @mouseleave="showMenuByHover = false"
                                        class="p-2 ">
                                        <svg class="w-4 h-4  " xmlns="http://www.w3.org/2000/svg" fill="currentColor"
                                            viewBox="0 0 4 15">
                                            <path
                                                d="M3.5 1.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 6.041a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 5.959a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z" />
                                        </svg>
                                    </button>
                                    <div x-show='isMenuClicked' x-transition x-cloak
                                        class="absolute left-full top-1/2 -translate-y-1/2 mr-2 p-2 w-40 z-20 bg-white dark:bg-zinc-700 border border-zinc-200 dark:border-zinc-600 rounded-lg shadow divide-y divide-zinc-100 dark:divide-zinc-600">
                                        <ul class=" text-sm text-zinc-700 dark:text-zinc-200">
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Reply</a>
                                            </li>
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Forward</a>
                                            </li>
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Copy</a>
                                            </li>
                                            <li><a href="#"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            <div id="typing-indicator" class="hidden items-start justify-end gap-2.5 mt-6 relative">
                <div class="flex gap-1 w-fit p-4 pb-2 bg-zinc-200 rounded-s-xl rounded-ee-xl dark:bg-zinc-700">
                    <span class="w-2 h-2 bg-zinc-800 rounded-full animate-bounce-dot dot-1"></span>
                    <span class="w-2 h-2 bg-zinc-800 rounded-full animate-bounce-dot dot-2"></span>
                    <span class="w-2 h-2 bg-zinc-800 rounded-full animate-bounce-dot dot-3"></span>
                </div>
            </div>


        </div>
    </div>

    {{-- chat input --}}
    <div class="sticky bottom-0 left-0 right-0 z-10 px-4 py-2">
        <form wire:submit='sendMessage'>
            <div class="flex items-center px-3 py-2 rounded-lg bg-zinc-50 dark:bg-zinc-700">
                <button type="button"
                    class="inline-flex justify-center p-2 text-zinc-500 rounded-lg hover:text-zinc-900 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-600">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                        <path fill="currentColor"
                            d="M13 5.5a.5.5 0 1 1-1 0 .5.5 0 0 1 1 0ZM7.565 7.423 4.5 14h11.518l-2.516-3.71L11 13 7.565 7.423Z" />
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M18 1H2a1 1 0 0 0-1 1v14a1 1 0 0 0 1 1h16a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z" />
                    </svg>
                    <span class="sr-only">Upload image</span>
                </button>
                <button type="button"
                    class="p-2 text-zinc-500 rounded-lg hover:text-zinc-900 hover:bg-zinc-100 dark:text-zinc-400 dark:hover:text-white dark:hover:bg-zinc-600">
                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z" />
                    </svg>
                    <span class="sr-only">Add emoji</span>
                </button>
                <input type="text" id="chat"  wire:model="message" wire:keydown='userTyping' required
                    autocomplete="off" suggestions="off" autofocus='true'
                    class="block mx-4 p-2.5 w-full text-sm text-zinc-900 bg-white rounded-lg border border-zinc-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-zinc-800 dark:border-zinc-600 dark:placeholder-zinc-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                    placeholder="Your message..." />
                <button type="submit"
                    class="inline-flex justify-center p-2 text-blue-600 rounded-full hover:bg-blue-100 dark:text-blue-500 dark:hover:bg-zinc-600">
                    <svg class="w-5 h-5 rotate-90 rtl:-rotate-90" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 18 20">
                        <path
                            d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z" />
                    </svg>
                    <span class="sr-only">Send message</span>
                </button>
            </div>
        </form>
    </div>

    <script type="module">
        // handle user typing event
        window.Echo.private(`typing.{{ $user->id }}`).listen('UserTyping', (event) => {
            console.log(event);
        })


        document.addEventListener('livewire:init', () => {
            scrollToBottom();

            Livewire.on('message-sent', () => {
                setTimeout(() => {
                    scrollToBottom();
                }, 100);
            });

            function scrollToBottom() {
                const container = document.getElementById('chat-container');

                // تأخير بسيط يضمن إن الرسائل تكون اتعرضت كلها
                container.scrollTo({
                    top: container.scrollHeight,
                });

            }
        });
    </script>


</div>
