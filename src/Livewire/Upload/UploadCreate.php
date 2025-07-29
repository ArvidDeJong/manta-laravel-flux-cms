<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Manta\FluxCMS\Models\Upload;
use Illuminate\Http\Request;
use Livewire\Component;
use Livewire\WithFileUploads;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class UploadCreate extends Component
{
    use MantaTrait;
    use UploadTrait;
    use WithFileUploads;

    public ?object $model_class = null;
    public array $documents = [];
    public bool $showForm = true;

    public function mount(Request $request)
    {
        $this->getLocaleInfo();
        $this->getBreadcrumb('create');

        $upload = new Upload();
        $this->model_class = $upload;
    }

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

        $this->redirect(route('upload.list'));
        // $this->dispatch('upload-created', model_class: get_class($this->model_class), id: $this->model_class->id);
    }

    public function render()
    {
        return view('manta-cms::livewire.upload.upload-create');
    }
}
