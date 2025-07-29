<?php

namespace Manta\FluxCMS\Livewire\Upload;

use Livewire\Component;
use Manta\FluxCMS\Models\Upload;
use Manta\FluxCMS\Traits\MantaTrait;
use Illuminate\Http\Request;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
class UploadRead extends Component
{
    use MantaTrait;
    use UploadTrait;

    public function mount(Request $request, Upload $upload)
    {
        $this->item = $upload;
        $this->itemOrg = $upload;
        $this->locale = $upload->locale;
        if ($request->input('locale') && $request->input('locale') != getLocaleManta()) {
            $this->pid = $upload->id;
            $this->locale = $request->input('locale');
            $upload_translate = Upload::where(['pid' => $upload->id, 'locale' => $request->input('locale')])->first();
            $this->item = $upload_translate;
        }

        if ($upload) {
            $this->id = $upload->id;
        }
        $this->getLocaleInfo();
        $this->getBreadcrumb();
        // $this->getTablist();
    }

    public function render()
    {
        return view('manta-cms::livewire.upload.upload-read')->title('Upload bekijken');
    }

    public function getBreadcrumb()
    {
        $this->breadcrumb = [
            ["title" => $this->breadcumbHomeName, "url" => route('cms.dashboard')],
            ["title" => "Uploads", "url" => route('upload.list')],
            ["title" => "Bekijken", "flag" => count(getLocalesManta()) > 1 ? $this->locale_info['class'] : null],
        ];
    }
}
