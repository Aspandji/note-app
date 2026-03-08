<?php

namespace App\Livewire\Forms;

use App\Models\CaseModel;
use Livewire\Attributes\Validate;
use Livewire\Form;

class CaseFormObject extends Form
{
    public ?CaseModel $case = null;

    #[Validate('required|string|max:255')]
    public $title = '';

    #[Validate('required|string')]
    public $description = '';

    #[Validate('nullable|string')]
    public $solution = '';

    #[Validate('nullable|exists:categories,id')]
    public $category_id = null;

    #[Validate('nullable|string|max:255')]
    public $tags = '';

    public function setCase(CaseModel $case)
    {
        $this->case = $case;
        $this->fill([
            'title' => $case->title,
            'description' => $case->description,
            'solution' => $case->solution,
            'category_id' => $case->category_id,
            'tags' => $case->tags,
        ]);
    }

    public function store()
    {
        $this->validate();

        $this->case = CaseModel::create($this->only([
            'title',
            'description',
            'solution',
            'category_id',
            'tags'
        ]));

        return $this->case;
    }

    public function update()
    {
        $this->validate();

        $this->case->update($this->only([
            'title',
            'description',
            'solution',
            'category_id',
            'tags'
        ]));

        return $this->case;
    }
}
