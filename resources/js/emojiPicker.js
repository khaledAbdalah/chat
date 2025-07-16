
// resources/js/components/emojiPicker.js
export default function emojiMartPicker() {
    return {
        // reactive state
        isOpen: false,
        picker: null,
        recentEmojis: JSON.parse(localStorage.getItem('recentEmojis') || '[]'),

        /**
         * Initialize the component - called when x-data is loaded
         */
        init() {
            // Listen for Livewire updates to reset picker if needed
            this.$watch('isOpen', (value) => {
                if (value && this.picker && this.$refs.emojiContainer) {
                    // Re-append the picker if container is empty
                    if (this.$refs.emojiContainer.children.length === 0) {
                        this.$refs.emojiContainer.appendChild(this.picker);
                    }
                }
            });
        },

        /**
         * Lazily import Emoji‑Mart & data and mount the picker once.
         * This keeps your initial JS bundle small and speeds up
         * first‑page load.
         */
        async loadPicker() {
            if (this.picker && this.$refs.emojiContainer.children.length > 0) {
                return; // already loaded and mounted
            }

            // 🔀 dynamic import – loads only when needed
            const { Picker } = await import('emoji-mart');
            // Both v5 (default export) & v6 (named export) compatibility
            const pickerConstructor = Picker?.default ?? Picker;

            const { data } = await import('@emoji-mart/data');

            // Create new picker if it doesn't exist
            if (!this.picker) {
                this.picker = new pickerConstructor({
                    data,
                    onEmojiSelect: emoji => this.selectEmoji(emoji),
                    theme: document.documentElement.classList.contains('dark') ? 'dark' : 'light',
                    set: 'native',
                    emojiSize: 20,
                    perLine: 8,
                    maxFrequentRows: 2,
                    previewPosition: 'none',
                    searchPosition: 'sticky',
                    navPosition: 'top',
                    skinTonePosition: 'search',
                    locale: 'en'
                });
            }

            // Mount the picker if container is empty
            if (this.$refs.emojiContainer && this.$refs.emojiContainer.children.length === 0) {
                this.$refs.emojiContainer.appendChild(this.picker);
            }
        },

        /** Toggle pop‑up visibility. */
        async togglePicker() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                await this.loadPicker();
            }
        },

        /** Close picker pop‑up. */
        closePicker() {
            this.isOpen = false;
        },

        /** Handle chosen emoji. */
        selectEmoji(emoji) {
            const messageInput = this.$refs.messageInput ?? document.getElementById('chat');
            if (!messageInput) return;

            const { value, selectionStart } = messageInput;
            const newValue = value.slice(0, selectionStart) + emoji.native + value.slice(selectionStart);
            
            // Update the input value
            messageInput.value = newValue;
            
            // Set cursor position after the emoji
            const newPosition = selectionStart + emoji.native.length;
            messageInput.setSelectionRange(newPosition, newPosition);

            // Trigger Livewire model update
            messageInput.dispatchEvent(new Event('input', { bubbles: true }));
            
            // Focus back to input
            messageInput.focus();

            // Add to recent emojis
            this.addToRecent(emoji.native);

        },

        /** Maintain a small LRU list of recently used emojis in localStorage. */
        addToRecent(emoji) {
            this.recentEmojis = [emoji, ...this.recentEmojis.filter(e => e !== emoji)].slice(0, 24);
            localStorage.setItem('recentEmojis', JSON.stringify(this.recentEmojis));
        },

        /** Clean up when component is destroyed */
        destroy() {
            if (this.picker && this.picker.remove) {
                this.picker.remove();
            }
        }
    };
}