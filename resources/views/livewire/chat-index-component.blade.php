<!-- Main modal -->
<div x-show="modal" x-transition x-cloak @click.outside='modal = false'
    class="absolute top-20 left-1/2 -translate-x-1/2 z-[999] w-96">
    <div class="relative p-4 w-96">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm dark:bg-zinc-700">
            <!-- Modal header -->
            <div
                class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-zinc-600 border-zinc-200">
                <h3 class="text-xl font-semibold text-zinc-900 dark:text-white">
                    Search for users
                </h3>
                <button type="button" @click="modal = false"
                    class="text-zinc-400 bg-transparent hover:bg-zinc-200 hover:text-zinc-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-zinc-600 dark:hover:text-white">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                </button>
            </div>
            <!-- Modal body -->
            <div class="p-4 md:p-5 space-y-4">
                <flux:input wire:model.live="search" autocomplete="off" placeholder='Search...' />
                <div class="mt-2 p-1 rounded-lg shadow-md bg-white dark:bg-[#535253]">
                    @forelse ($users as $user)
                        <div wire:key='{{ $user->id }}' class="p-2 flex items-center justify-between">
                            <span class=" font-semibold">{{ $user->name }}</span>
                            <a class="w-10 h-10 flex items-center justify-center rounded-full bg-blue-500 "
                                @if($href) href="{{ route('chat.show', $user->id) }}" @elseif($action) wire:click="$parent.{{ $action }}({{ $user->id }})" @endif @click="modal = false">
                                <flux:icon.paper-airplane class="text-white" />
                            </a>
                        </div>
                    @empty
                        <div class="p-2">No Users Found</div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>
