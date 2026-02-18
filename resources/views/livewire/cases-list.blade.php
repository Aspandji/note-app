<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between gap-4">
                <h1 class="text-2xl font-light tracking-tight">Notes</h1>

                <div class="flex items-center gap-3">
                    <button wire:click="$dispatch('openCategoryManagement')"
                        class="px-4 py-2 text-sm border border-gray-300 hover:border-black transition-colors duration-200 cursor-pointer">
                        Categories
                    </button>
                    <button wire:click="$dispatch('openCaseForm')"
                        class="px-6 py-2 bg-black text-white text-sm font-medium hover:bg-gray-800 transition-colors duration-200 cursor-pointer">
                        New Note
                    </button>
                </div>
            </div>

            <!-- Tabs & Date -->
            <div class="flex items-center justify-between mt-4 text-sm">
                <div class="flex gap-6">
                    <button class="font-medium border-b-2 border-black pb-1 cursor-pointer">All Notes</button>
                    <button class="text-gray-500 hover:text-black pb-1 cursor-pointer">Categories</button>
                </div>
                <div class="text-gray-500">
                    {{ now()->format('d M Y') }}
                </div>
            </div>
        </div>
    </header>
    <!-- Search & Filter Bar -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex gap-3 items-center">
                <!-- Search -->
                <div class="flex-1">
                    <input type="text" wire:model.live.debounce.500ms="search" placeholder="Search notes..."
                        class="w-full px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black">
                </div>

                <!-- Category Filter -->
                <select wire:model.live="selectedCategory"
                    class="px-4 py-2.5 bg-gray-50 border-0 text-sm focus:outline-none focus:ring-1 focus:ring-black cursor-pointer">
                    <option value="">All Categories</option>
                    {{-- @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }} ({{ $category->cases_count }})
                        </option>
                    @endforeach --}}
                </select>

                <!-- Clear Filter -->
                {{-- @if ($search || $selectedCategory) --}}
                <button wire:click="clearFilters"
                    class="px-4 py-2.5 text-sm text-gray-600 hover:text-black cursor-pointer">
                    Clear
                </button>
                {{-- @endif --}}
            </div>
        </div>
    </div>
</div>
