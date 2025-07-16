<div class="flex flex-col h-screen">
    <style>
        #typing-indicator {
            opacity: 0;
            transform: translateY(10px);
            transition: all 0.3s ease-in-out;
        }

        #typing-indicator.show {
            opacity: 1;
            transform: translateY(0);
        }

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
    <div id="chat-container" class="flex-1 overflow-y-auto no-scrollbar">

        {{-- chat header --}}
        <flux:header sticky
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

        <div x-data="{ modal: false }" class="flex flex-col  px-6 lg:px-8 py-6 relative">
            <div wire:ignore>
                <livewire:chat-index-component action="forward" />
            </div>
            {{-- forward action --}}
            @foreach ($chatMessages as $chat)
                @if ($chat->sender_id == $user->id)
                    {{-- Sender messages --}}
                    <div wire:key="{{ $chat->id }}" x-data='{ isHovered: false, isMenuClicked: false }'
                        @mouseenter="isHovered = true" @mouseleave="if(!isMenuClicked) isHovered = false"
                        class=" flex items-start justify-end gap-2.5 mt-6 relative ">
                        <div class="relative">
                            <div
                                class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 pb-2 bg-blue-500 text-white rounded-s-xl rounded-ee-xl dark:bg-blue-600">
                                @if ($chat->image)
                                    <div class="w-full p-2 ">
                                        <img class="block w-full object-cover"
                                            src="{{ asset('storage/' . $chat->image) }}" alt="">
                                    </div>
                                @endif
                                <p class="text-sm font-normal">{{ $chat->message }}</p>
                                <span class="hidden" x-ref="text{{ $loop->iteration }}">{{ $chat->message }}</span>
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
                                            <li><a @click="modal = !modal, $wire.dispatch('message-forward', {messageId: {{ $chat->id }}})"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg cursor-pointer">Forward</a>
                                            </li>
                                            <li><a @click="navigator.clipboard.writeText($refs.text{{ $loop->iteration }}.innerText)"
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg cursor-pointer">Copy</a>
                                            </li>
                                            <li><a wire:confirm wire:click='delete({{ $chat->id }})'
                                                    class="block px-4 py-2 hover:bg-zinc-100 dark:hover:bg-zinc-600 dark:hover:text-white rounded-lg cursor-pointer">Delete</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- Receiver messages --}}
                    <div wire:key="{{ $chat->id }}" x-data="{ isHovered: false, isMenuClicked: false }" @mouseenter="isHovered = true"
                        @mouseleave="if(!isMenuClicked) isHovered = false" class=" flex items-start gap-2.5 mt-6">
                        <div class="relative">
                            {{-- message --}}
                            <div
                                class="flex flex-col w-full max-w-[320px] leading-1.5 p-4 pb-2 bg-zinc-200 rounded-e-xl rounded-es-xl dark:bg-zinc-700">
                                @if ($chat->image)
                                    <div class="w-full p-2">
                                        <img class="block w-full object-cover"
                                            src="{{ asset('storage/' . $chat->image) }}" alt="">
                                    </div>
                                @endif
                                <p class="text-sm text-zinc-900 dark:text-white">{{ $chat->message }}</p>
                                <p class="hidden" x-ref="text{{ $loop->iteration }}">{{ $chat->message }}</p>
                                <div class="flex justify-end mt-2 text-[10px] text-zinc-500 dark:text-zinc-400">
                                    <span>{{ $chat->created_at->format('h:i A') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
            <div id="typing-indicator" class="flex items-start justify-end gap-2.5 mt-6 relative">
                <div class="flex gap-1 w-fit p-4 pb-2 bg-zinc-200 rounded-s-xl rounded-ee-xl dark:bg-zinc-700">
                    <span class="w-2 h-2 bg-zinc-500 dark:bg-zinc-400 rounded-full animate-bounce-dot dot-1"></span>
                    <span class="w-2 h-2 bg-zinc-500 dark:bg-zinc-400 rounded-full animate-bounce-dot dot-2"></span>
                    <span class="w-2 h-2 bg-zinc-500 dark:bg-zinc-400 rounded-full animate-bounce-dot dot-3"></span>
                </div>
            </div>


        </div>
    </div>
    {{-- chat input --}}
    <div class="sticky bottom-0 left-0 right-0 z-10 px-4 py-2">
        @if ($image)
            <div class="bg-white w-full h-25 shadow-2xl -mb-5 p-4">
                <div class="w-15 h-15 rounded border-2 border-amber-600 relative">
                    <flux:icon.x-mark wire:click="removeImage"
                        class="w-4 h-4 text-white bg-red-500 cursor-pointer rounded-full absolute -top-[5px] -right-[5px]" />
                    <img class="block w-full object-cover" src="{{ $image->temporaryUrl() }}" alt="">
                </div>
            </div>
        @endif
        <form wire:submit='sendMessage'>
            <div class="flex items-center px-3 py-2 rounded-full bg-white shadow-2xl dark:bg-zinc-700 relative">

                <div>
                    <label for="image"
                        class="inline-flex justify-center p-2 text-zinc-900 rounded-full cursor-pointer hover:bg-zinc-100 dark:text-white dark:hover:bg-zinc-600 transition-all duration-200">
                        <flux:icon.photo />
                    </label>
                    <input wire:model='image' type="file" name="image" id="image" class="hidden">
                </div>
                <!-- Emoji Button -->
                <div wire:ignore x-data="emojiMartPicker()" x-init="init()" class="relative">
                    <button @click="togglePicker()" type="button"
                        class="inline-flex justify-center p-3 text-zinc-900 rounded-full cursor-pointer hover:bg-zinc-100 dark:text-white dark:hover:bg-zinc-600 transition-all duration-200">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                stroke-width="2"
                                d="M13.408 7.5h.01m-6.876 0h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0ZM4.6 11a5.5 5.5 0 0 0 10.81 0H4.6Z" />
                        </svg>
                    </button>

                    <!-- Emoji Picker Container -->
                    <div x-show="isOpen" x-transition x-cloak @click.outside="closePicker()"
                        @keydown.escape="closePicker()"
                        class="absolute bottom-full mb-6 bg-white dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-600 rounded-lg shadow-lg z-[999]">
                        <div x-ref="emojiContainer" class="w-80 h-full"></div>
                    </div>
                </div>

                <input type="text" id="chat" wire:model="msg" wire:keydown='userTyping' autocomplete="off"
                    suggestions="off" autofocus='true' x-ref="messageInput"
                    class="block mx-4 p-2.5 w-full text-sm text-zinc-900 dark:text-white bg-inherit border-none outline-none focus:border-none focus:outline-none focus:ring-0 dark:placeholder-zinc-400   "
                    placeholder="Your message..." />

                <button type="submit"
                    class="inline-flex cursor-pointer justify-center p-3 text-white bg-blue-600 rounded-full dark:bg-blue-500">
                    <svg class="w-4 h-4 rotate-90 rtl:-rotate-90" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 18 20">
                        <path
                            d="m17.914 18.594-8-18a1 1 0 0 0-1.828 0l-8 18a1 1 0 0 0 1.157 1.376L8 18.281V9a1 1 0 0 1 2 0v9.281l6.758 1.689a1 1 0 0 0 1.156-1.376Z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
    @push('scripts')
        <script type="module">
            // observer
            let chatObserver = null;
            const chatContainer = document.getElementById('chat-container');
            const notificationSound = new Audio('/sounds/message-notification.mp3');
            const typingIndicator = document.getElementById('typing-indicator');

            function initChatObserver() {


                if (chatObserver) {
                    chatObserver.disconnect();
                }

                chatObserver = new MutationObserver((mutations) => {
                    mutations.forEach((mutation) => {
                        if (mutation.type === 'childList' && mutation.addedNodes.length > 0) {
                            // check if the node is typing indicator
                            const addedNode = mutation.addedNodes[0];
                            if (addedNode.nodeType === Node.ELEMENT_NODE &&
                                !addedNode.id.includes('typing-indicator')) {
                                scrollToBottom();
                            }
                        }
                    });
                });

                chatObserver.observe(chatContainer, {
                    childList: true,
                    subtree: true
                });
            }

            // handle user typing event
            window.Echo.private(`typing.{{ $user->id }}.{{ $receiver->id }}`).listen('UserTyping', (event) => {

                typingIndicator.classList.add('show');

                scrollToBottom();

                setTimeout(() => {
                    typingIndicator.classList.remove('show');
                }, 4000)

            })

            window.Echo.private(`message.{{ $user->id }}.{{ $receiver->id }}`).listen('.message.sent', (event) => {
                typingIndicator.classList.remove('show');
                notificationSound.play();
                scrollToBottom();
            });

            window.Echo.private(`unread-messages-count.{{ $user->id }}`).listen('UnreadMessagesCount', (event) => {
                notificationSound.play();
            })

            function scrollToBottom() {

                chatContainer.scrollTo({
                    top: chatContainer.scrollHeight,
                });

            }

            document.addEventListener('DOMContentLoaded', () => {
                scrollToBottom();
                initChatObserver();

            });
        </script>
    @endpush
</div>
