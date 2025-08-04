<?php

namespace Manta\FluxCMS\Livewire\Cms;

use Manta\Models\Routeseo;
use Livewire\Component;

class CmsNumbers extends Component
{

    public array $breadcrumb = [];

    public function mount()
    {
        $this->getBreadcrumb();
    }

    public function render()
    {

        $translations = [];
        $translations_total = 0;
        $path = resource_path() . '/lang/';
        foreach (getLocalesManta() as $lang) {
            foreach (scandir($path . $lang['locale']) as $keyf => $valuef) :
                $filepath = $path . $lang['locale'] . '/'  . $valuef;
                if (!is_dir($filepath) && $valuef != '.' && $valuef != '..') :
                    if (pathinfo($filepath)['extension'] == 'json') {
                        //json_decode(file_get_contents($filepath), true);
                    } elseif (pathinfo($filepath)['extension'] == 'php') {
                        $translations[] = ['locale' => $lang['locale'], 'file' => $valuef, 'count' => count(include($filepath))];
                        $translations_total += count(include($filepath));
                    }
                endif;
            endforeach;
        }

        $routes = 0;
        $routes_translated = 0;
        $list = Routeseo::whereNull('pid')->get();
        foreach ($list as $row) {
            $routes++;
            if (!empty($row->seo_title)) {
                $routes_translated++;
            }
            if (!empty($row->seo_description)) {
                $routes_translated++;
            }
            if (count(getLocalesManta()) > 1) {
                foreach (getLocalesManta() as $value) {
                    if ($value['locale'] != getLocaleManta()) {
                        $find = Routeseo::where(['pid' => $row->id, 'locale' => $value['locale']])->first();
                        if ($find) {
                            if (!empty($find->seo_title)) {
                                $routes_translated++;
                            }
                            if (!empty($find->seo_description)) {
                                $routes_translated++;
                            }
                        }
                    }
                }
            }
        }

        return view('manta-cms::livewire.cms.cms-numbers', ['translations' => $translations, 'translations_total' => $translations_total, 'routes' => $routes, 'routes_translated' => $routes_translated])->title('Website gegevens');
    }

    public function getBreadcrumb()
    {
        $this->breadcrumb = [
            ["title" => 'Dashboard', "url" => route('cms.dashboard')],
            ["title" => "Website getallen",],
        ];
    }
}
