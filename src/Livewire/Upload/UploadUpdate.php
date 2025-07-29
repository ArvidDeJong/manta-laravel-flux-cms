<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Flux\Flux;
use Manta\FluxCMS\Models\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Livewire\Component;
use Manta\FluxCMS\Traits\MantaTrait;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class UploadUpdate extends Component
{
    use MantaTrait;
    use UploadTrait;

    public ?string $image;
    public ?int $replace;
    public object $model_vars;
    public $thumbnails;


    public function mount(Request $request, Upload $upload)
    {
        $this->id = $upload->id;

        $this->fill(
            $upload->only(
                'locale',
                'title',
                'identifier',
                'seo_title',
                'content',
                'data',
            ),
        );


        $this->data_content = $upload->data[$this->data_locale] ?? [];

        // dd($this->data_content);

        $this->item = $upload;
        $this->model_vars = $upload->model::find($upload->model_id);
        $this->redirect_url = $request->input('redirect_url');
        $this->redirect_title = $request->input('redirect_title');
        $this->image = $upload->getImage()['src'];

        $thumbnails = [];
        $thumbnails[] = [
            'id' => $upload->id,
            'title' => $upload->title,
            'main' => $upload->main,
            'identifier' => $upload->identifier,
            'image' => $upload->image,
            'icon' => $upload->getIcon(),
            'url' => $upload->getImage()['src'],
            'url_update' => route('upload.update', ['upload' => $upload, 'redirect_url' => url()->full(), 'redirect_title' => $upload->title]),
            'url_crop' => route('upload.crop', ['upload' => $upload, 'redirect_url' => url()->full(), 'redirect_title' => $upload->title]),
        ];
        $this->thumbnails = $thumbnails;

        $string =  $upload->model;
        $result = module_config(Str::afterLast($string, '\\'));
        if (isset($result['upload_data']['fields'])) {
            $this->data_fields = $result['upload_data']['fields'];
        }

        $this->getBreadcrumb('update');
    }

    public function render()
    {
        // dd($this->data, $this->data_content);
        return view('manta-cms::livewire.upload.upload-update')->layoutData(['title' => 'Upload']);
    }

    public function save()
    {
        $this->validate();

        if (!isset($this->data[$this->data_locale]))  $this->data = [$this->data_locale => []];
        $this->data[$this->data_locale] = $this->data_content;
        // dd($this->data, $this->data_content);

        $row = $this->only(
            'title',
            'identifier',
            'seo_title',
            'content',
            'data',
        );
        $row['updated_by'] = auth('staff')->user()->name;
        Upload::where('id', $this->id)->update($row);

        // return redirect()->to(url($this->redirect_url));
        Flux::toast('Opgeslagen', duration: 1000, variant: 'success');
    }

    public function set_data_locale($data_locale)
    {
        $this->data[$this->data_locale] = $this->data_content;
        Upload::where('id', $this->item->id)->update(['data' => $this->item->data]);
        $this->data_locale = $data_locale;
        $this->data_content = $this->item->data[$this->data_locale] ?? [];
    }
}
