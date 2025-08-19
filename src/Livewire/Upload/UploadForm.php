<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Manta\FluxCMS\Models\Upload;
use Livewire\Component;
use Livewire\WithFileUploads;


class UploadForm extends Component
{
    use WithFileUploads;

    public ?object $model_class = null;
    public array $documents = [];
    public bool $showForm = true;

    public function rules()
    {
        return [
            'documents.*' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'documents.*.required' => 'Het uploaden van documenten is verplicht.',
            'documents.*.image' => 'Alleen afbeeldingen uploaden.',
        ];
    }
    public function updatedDocuments()
    {
        $this->handleDocumentUpload();
    }

    public function save()
    {
        $this->handleDocumentUpload();
    }

    private function handleDocumentUpload()
    {
        $this->validate();

        foreach ($this->documents as $file) {
            (new Upload())->upload($file, get_class($this->model_class), $this->model_class->id);
        }

        $this->documents = [];
        $this->dispatch('upload-created', model_class: get_class($this->model_class), id: $this->model_class->id);
    }

    public function render()
    {
        return view('manta-cms::livewire.upload.upload-form');
    }
}
