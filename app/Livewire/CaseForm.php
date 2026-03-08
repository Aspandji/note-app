<?php

namespace App\Livewire;

use App\Livewire\Forms\CaseFormObject;
use App\Models\CaseModel;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class CaseForm extends Component
{

    use WithFileUploads;

    public CaseFormObject $form;

    public $isOpen = false;
    public $caseId = null;

    protected $listeners = [
        'openCaseForm' => 'openModal'
    ];

    #[Validate('nullable|image|max:2048')]
    public $image;

    public $existingImage = null;

    public function openModal($caseId = null)
    {
        $this->resetValidation();
        $this->reset(['image', 'existingImage']);

        $this->caseId = $caseId;

        if ($caseId) {
            $case = CaseModel::findOrFail($caseId);
            $this->form->setCase($case);
            $this->existingImage = $case->image;
        } else {
            $this->form->reset();
        }

        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['caseId', 'image', 'existingImage']);
        $this->form->reset();
        $this->resetValidation();
    }

    public function save()
    {
        // Validate form object
        $this->form->validate();

        // Validate image
        $this->validateOnly('image');

        // Handle image upload
        $imageData = [];
        if ($this->image) {
            if ($this->existingImage) {
                Storage::disk('public')->delete($this->existingImage);
            }
            $imageData['image'] = $this->image->store('cases', 'public');
        }

        // Save case
        if ($this->caseId) {
            $case = $this->form->update();
            if ($imageData) {
                $case->update($imageData);
            }
            $message = 'Case updated successfully!';
        } else {
            $case = $this->form->store();
            if ($imageData) {
                $case->update($imageData);
            }
            $message = 'Case created successfully!';
        }

        $this->dispatch('case-saved');
        $this->closeModal();

        session()->flash('message', $message);
    }

    public function deleteImage()
    {
        if ($this->existingImage) {
            Storage::disk('public')->delete($this->existingImage);

            if ($this->caseId && $this->form->case) {
                $this->form->case->update(['image' => null]);
            }

            $this->existingImage = null;
        }

        $this->image = null;
    }

    public function render()
    {
        $categories = Category::orderBy('name')->get();

        return view('livewire.case-form', [
            'categories' => $categories
        ]);
    }
}
