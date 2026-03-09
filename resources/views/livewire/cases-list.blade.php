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
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">
                            {{ $category->name }} ({{ $category->cases_count }})
                        </option>
                    @endforeach
                </select>

                <!-- Clear Filter -->
                @if ($search || $selectedCategory)
                    <button wire:click="clearFilters"
                        class="px-4 py-2.5 text-sm text-gray-600 hover:text-black cursor-pointer">
                        Clear
                    </button>
                @endif
            </div>
        </div>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="bg-black text-white px-4 py-2.5 text-sm flex items-center justify-between">
                <span>{{ session('message') }}</span>
                <button onclick="this.parentElement.remove()" class="text-white/80 hover:text-white">×</button>
            </div>
        </div>
    @endif

    <!-- Notes Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        @if ($cases->count() > 0)

            <!-- Masonry Layout -->
            <div class="columns-1 sm:columns-2 lg:columns-3 xl:columns-4 gap-6">
                @foreach ($cases as $case)
                    <!-- Card -->
                    <div wire:click="$dispatch('openCaseDetail', { caseId: {{ $case->id }} })"
                        class="break-inside-avoid mb-6 bg-white border border-gray-200 rounded-lg hover:-translate-y-1 hover:shadow-lg cursor-pointer transition-all duration-200">
                        <!-- Image -->
                        @if ($case->image)
                            <div class="w-full bg-gray-100 overflow-hidden rounded-t-lg">
                                <img src="{{ Storage::url($case->image) }}" alt="{{ $case->title }}"
                                    class="w-full h-auto object-cover">
                            </div>
                        @endif
                        <!-- Content -->
                        <div class="p-4">
                            <!-- Category -->
                            @if ($case->category)
                                <div class="mb-2">
                                    <span class="inline-block px-2 py-1 text-xs font-medium rounded"
                                        style="background-color: {{ $case->category->color }}15; color: {{ $case->category->color }}">
                                        {{ $case->category->name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Title -->
                            <h3 class="font-semibold mb-2 text-base line-clamp-2">
                                {{ $case->title }}
                            </h3>

                            <!-- Description -->
                            <p class="text-gray-600 text-sm mb-3 line-clamp-4">
                                {{ $case->description }}
                            </p>

                            <!-- Tags -->
                            @if ($case->tags)
                                <div class="flex flex-wrap gap-1.5 mb-3">
                                    @foreach (array_slice(explode(',', $case->tags), 0, 3) as $tag)
                                        <span class="px-2 py-0.5 bg-gray-100 text-xs text-gray-600 rounded">
                                            {{ trim($tag) }}
                                        </span>
                                    @endforeach

                                    @if (count(explode(',', $case->tags)) > 3)
                                        <span class="px-2 py-0.5 text-xs text-gray-400">
                                            +{{ count(explode(',', $case->tags)) - 3 }}
                                        </span>
                                    @endif
                                </div>
                            @endif

                            <!-- Date -->
                            <div class="text-xs text-gray-400">
                                {{ $case->created_at->format('d M Y') }}
                            </div>

                        </div>
                    </div>
                    <!-- End Card -->
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $cases->links('') }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500 text-lg">
                    @if ($search || $selectedCategory)
                        No notes found
                    @else
                        No notes yet. Create your first note!
                    @endif
                </p>
            </div>
        @endif
    </div>

    <!-- Modals -->
    <livewire:case-form />
</div>
