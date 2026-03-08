<?php

namespace App\Livewire;

use App\Models\CaseModel;
use App\Models\Category;
use Livewire\Attributes\On;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class CasesList extends Component
{
    use WithPagination;

    #[Url(as: 'q', history: true)]
    public $search = '';

    #[Url(as: 'category', history: true)]
    public $selectedCategory = null;

    public $perPage = 12;

    // Reset pagination saat search atau filer update (Berubah)
    public function updateSearch()
    {
        $this->resetPage();
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->reset(['search', 'selectedCategory']);
        $this->resetPage();
    }

    // Forms
    #[On('case-saved')]
    #[On('case-deleted')]
    #[On('category-saved')]
    #[On('category-deleted')]
    public function refreshList()
    {
        // Refresh Component
    }

    public function render()
    {
        $cases = CaseModel::query()
            ->search($this->search)
            ->byCategory($this->selectedCategory)
            ->latest()
            ->paginate($this->perPage);

        $categories = Category::withCount('cases')->orderBy('name')->get();

        return view('livewire.cases-list', [
            'cases' => $cases,
            'categories' => $categories
        ]);
    }
}
