<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Manta\FluxCMS\Models\Upload;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Route;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Layout;
use Manta\FluxCMS\Models\MantaModule;

#[Layout('manta-cms::layouts.app')]
trait UploadTrait
{
    public function __construct()
    {
        $this->route_name = 'upload';
        $this->route_list = Route::has('upload.list') ? route('upload.list') : null;
        $settings = MantaModule::where('name', 'upload')->first()->toArray();

        $this->config = $settings;

        $this->fields = $settings['fields'];
        $this->tab_title = isset($settings['tab_title']) ? $settings['tab_title'] : null;
        $this->moduleClass = 'Manta\Models\Upload';
    }

    public ?Upload $item = null;
    public ?Upload $itemOrg = null;




    public $files;

    #[Locked]
    public ?string $redirect_title = null;

    #[Locked]
    public ?string $redirect_url = null;

    #[Locked]
    public ?string $redirect_route = null;

    public ?string $sort = null;
    public ?string $main = null;
    public ?string $created_by = null;
    public ?string $updated_by = null;
    public ?string $user_id = null;
    public ?string $company_id = null;
    public ?string $host = null;
    public ?string $locale = null;
    public ?string $title = null;

    public ?string $seo_title = null;
    public ?string $private = null;
    public ?string $disk = null;
    public ?string $location = null;
    public ?string $filename = null;
    public ?string $extension = null;
    public ?string $mime = null;
    public ?string $size = null;
    public ?string $model = null;
    public ?string $model_id = null;
    public ?string $pid = null;
    public ?string $identifier = null;
    public ?string $originalName = null;
    public ?string $content = null;


    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            :          $query->where(function (Builder $querysub) {
                $querysub->where('title', 'LIKE', "%{$this->search}%")
                    ->orWhere('content', 'LIKE', "%{$this->search}%");
            });
    }

    public function rules()
    {
        return [
            'title' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'De titel is verplicht',
        ];
    }
}
