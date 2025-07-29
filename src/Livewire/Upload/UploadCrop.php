<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Flux\Flux;
use Manta\FluxCMS\Models\Upload;
use Livewire\Component;
use Illuminate\Http\Request;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class UploadCrop extends Component
{

    use UploadTrait;
    use MantaTrait;

    public ?string $image;

    public ?int $replace;
    // public string $redirect_url;
    // public string $redirect_title;

    public function mount(Request $request, Upload $upload)
    {
        $this->redirect_url = $request->input('redirect_url');
        $this->redirect_title = $request->input('redirect_title');
        $this->image = $upload->fullPath(); // Gebruik fullPath zonder size parameter voor originele afbeelding

        $this->item = $upload;


        $this->getBreadcrumb('Crop');
    }

    public function render()
    {
        return view('manta-cms::livewire.upload.upload-crop')->title('Upload crop');
    }



    /**
     * Validates the input, uploads a new image, and redirects to the specified URL.
     *
     * @param array $post The post data containing the new image.
     * @return \Illuminate\Http\RedirectResponse The redirect response to the specified URL.
     */
    public function store($post)
    {
        $this->validate(
            [
                'replace' => 'required',
            ],
            [
                'replace.required' => 'Actie is verplicht',
            ]
        );

        (new Upload())->upload(file_get_contents($post['newimage']), $this->item->model, $this->item->model_id, [
            'replace' => $this->replace,
            'upload_id' => $this->item->id,
            'filename' =>  $this->item->filename,
        ]);



        Flux::toast('Opgeslagen', duration: 1000, variant: 'success');

        return $this->redirect(url($this->redirect_url));
    }
}
