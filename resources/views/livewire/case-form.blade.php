<div>
    @if ($isOpen)
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <div class="fixed inset-0 bg-black/50 backdrop-blur-sm" wire:click="closeModal"></div>

            <div class="flex min-h-full items-center justify-center p-4">
                <div class="relative bg-white w-full max-w-2xl border border-gray-200 shadow-xl" wire:click.stop>
                    <!-- Header -->
                    <div class="border-b border-gray-200 px-6 py-4 flex items-center justify-between">
                        <h2 class="text-xl font-medium">{{ $caseId ? 'Edit Note' : 'New Note' }}</h2>
                        <button wire:click="closeModal" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Form -->
                    <form wire:submit="save" class="p-6 space-y-5">
                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Category</label>
                            <select wire:model="form.category_id"
                                class="w-full px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black @error('form.category_id') ring-1 ring-red-600 @enderror">
                                <option value="">Select category...</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('form.category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Title -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Title <span
                                    class="text-red-600">*</span></label>
                            <input type="text" wire:model="form.title"
                                class="w-full px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black @error('form.title') ring-1 ring-red-600 @enderror"
                                placeholder="Enter note title...">
                            @error('form.title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Description <span
                                    class="text-red-600">*</span></label>
                            <textarea wire:model="form.description" rows="5"
                                class="w-full px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black @error('form.description') ring-1 ring-red-600 @enderror"
                                placeholder="Describe the problem..."></textarea>
                            @error('form.description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Solution -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Solution</label>
                            <textarea wire:model="form.solution" rows="5"
                                class="w-full px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black @error('form.solution') ring-1 ring-red-600 @enderror"
                                placeholder="How did you solve it?"></textarea>
                            @error('form.solution')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tags -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Tags</label>
                            <input type="text" wire:model="form.tags"
                                class="w-full px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black @error('form.tags') ring-1 ring-red-600 @enderror"
                                placeholder="laravel, php, bug (comma separated)">
                            @error('form.tags')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Image Upload -->
                        <div>
                            <label class="block text-sm font-medium mb-2">Image (Optional)</label>

                            @if ($existingImage && !$image)
                                <div class="mb-3">
                                    <img src="{{ Storage::url($existingImage) }}" alt="Current"
                                        class="h-40 border border-gray-200">
                                    <button type="button" wire:click="deleteImage"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Remove image
                                    </button>
                                </div>
                            @endif

                            @if ($image)
                                <div class="mb-3">
                                    <img src="{{ $image->temporaryUrl() }}" alt="Preview"
                                        class="h-40 border border-gray-200">
                                    <button type="button" wire:click="$set('image', null)"
                                        class="mt-2 text-sm text-red-600 hover:text-red-800">
                                        Remove
                                    </button>
                                </div>
                            @endif

                            @if (!$image)
                                <input type="file" wire:model="image" accept="image/*"
                                    class="w-full px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black @error('image') ring-1 ring-red-600 @enderror">
                            @endif

                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror

                            <div wire:loading wire:target="image" class="mt-2 text-sm text-gray-600">
                                Uploading...
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit"
                                class="flex-1 px-6 py-2.5 bg-black text-white text-sm font-medium hover:bg-gray-800 transition-colors duration-200 cursor-pointer"
                                wire:loading.attr="disabled">
                                <span wire:loading.remove
                                    wire:target="save">{{ $caseId ? 'Update Note' : 'Create Note' }}</span>
                                <span wire:loading wire:target="save">Saving...</span>
                            </button>
                            <button type="button" wire:click="closeModal"
                                class="flex-1 px-6 py-2.5 border border-gray-300 text-gray-700 text-sm font-medium hover:border-black transition-colors duration-200 cursor-pointer">
                                Cancel
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
</div>
