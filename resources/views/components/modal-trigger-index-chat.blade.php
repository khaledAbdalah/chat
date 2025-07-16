<div x-data="{ modal: false }" class="w-full h-screen flex items-center justify-center relative">


    <!-- Modal toggle -->
    <div class="flex items-center mt-10">
        <button @click="modal = !modal"
            class="flex items-center space-x-1 text-white text-lg bg-blue-700 hover:bg-blue-800 focus:outline-none  font-medium rounded-full p-4 text-center dark:bg-blue-600 dark:hover:bg-blue-700 cursor-pointer"
            type="button">
            Start new Chat
            <flux:icon.plus />
        </button>
    </div>

    {{ $slot }}



</div>
