<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Manta\FluxCMS\Models\Upload;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\Attributes\Locked;
use Manta\FluxCMS\Traits\MantaTrait;


class UploadOverview extends Component
{

    use MantaTrait;
    use UploadTrait;

    public object $items;

    public ?object $model_class = null;
    public ?string $identifier = null;
    // public ?string $title = 'Test';

    public ?int $trashed = null;
    public ?string $deleteId = null;

    public string $orderjson = '';


    public $thumbnails = [];

    // #[Locked]
    // public $redirect_url = null;

    #[On('upload-created')]
    public function uploadCreatedUpdate($model_class, $id)
    {
        $this->model_class = $model_class::find($id);
    }

    #[On('reorder')]
    public function updateOrder($data)
    {
        $thumbnails = [];
        foreach ($data as $key => $value) {
            $result = $this->thumbnails[array_search($value, array_column($this->thumbnails, 'id'))];
            Upload::where('id', $value)->update(['sort' => $key]);
            $thumbnails[] =  $result;
        }
    }

    public function mount()
    {
        $this->redirect_url = url()->full();
    }

    public function render()
    {
        if ($this->model_class) {
            $obj = Upload::where(['model_id' => $this->model_class->id, 'model' => get_class($this->model_class)])->orderBy('sort', 'ASC')->orderBy('created_at', 'ASC');
            if ($this->identifier) {
                $obj->where('identifier', $this->identifier);
            }
            $items = $obj->get();
            $thumbnails = [];
            foreach ($items as $upload) {
                $thumbnails[] = [
                    'id' => $upload->id,
                    'title' => $upload->title,
                    'main' => $upload->main,
                    'identifier' => $upload->identifier,
                    'image' => $upload->image,
                    'icon' => $upload->getIcon(),
                    'url' => $upload->getImage()['src'],
                    'url_update' => route('manta-cms.upload.update', ['upload' => $upload, 'redirect_url' => $this->redirect_url, 'redirect_title' => $upload->title]),
                    'url_crop' => route('manta-cms.upload.crop', ['upload' => $upload, 'redirect_url' => $this->redirect_url, 'redirect_title' => $upload->title]),
                ];
            }
            $this->thumbnails = $thumbnails;
        }

        return view('manta-cms::livewire.upload.upload-overview');
    }



    public function setmain($id)
    {
        $upload = Upload::find($id);
        Upload::where(['model_id' =>  $upload->model_id, 'identifier' => $upload->identifier, 'model' =>  $upload->model])->update(['main' => 0]);
        Upload::where('id', $id)->update(['main' => 1]);
        $this->dispatch('uploadsCreated');
    }


    public function delete($id)
    {
        $this->deleteId = $id;
    }

    public function deleteCancel()
    {
        $this->deleteId = null;
    }

    public function deleteConfirm()
    {
        Upload::find($this->deleteId)->delete();
        $this->deleteId = null;
        $this->trashed = count(Upload::onlyTrashed()->get());
    }

    public function updatedOrderjson()
    {
        $i = 0;
        $rows = json_decode($this->orderjson);

        foreach ($rows->items as $key => $value) {
            Upload::where('id', $value)->update(['sort' => $i++]);
        }
        $this->dispatch('uploadsCreated');
    }
}
