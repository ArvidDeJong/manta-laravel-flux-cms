<?php

namespace Manta\FluxCMS\Livewire\Option;

use Flux\Flux;
use Manta\FluxCMS\Models\Option;
use Manta\FluxCMS\Traits\MantaTrait;
use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Layout;

#[Layout('manta-cms::layouts.app')]
#[Title('Instellingen')]
class OptionUpdate extends Component
{
    use MantaTrait;
    use OptionTrait;

    public $latitude;
    public $longitude;

    // Google maps
    public $DEFAULT_LATITUDE;
    public $DEFAULT_LONGITUDE;
    public $GOOGLE_MAPS_ZOOM;
    public $maps_set_center = true;
    public $GOOGLE_MAPS_MAP_TYPE = 'roadmap';
    public $maps_id = 'default';
    public $maps_update = false;
    public $markers = [];

    public $DEFAULT_COMPANY;
    public $DEFAULT_CONTACT;
    public $DEFAULT_ADDRESS;
    public $DEFAULT_ADDRESS_2;
    public $DEFAULT_ZIPCODE;
    public $DEFAULT_CITY;
    public $DEFAULT_COUNTRY;
    public $DEFAULT_PHONE;
    public $DEFAULT_PHONE_LINK;
    public $DEFAULT_EMAIL;
    public $DEFAULT_KVK;
    public $DEFAULT_VAT;
    public $DEFAULT_FACEBOOK;
    public $DEFAULT_INSTAGRAM;
    public $DEFAULT_TWITTER;
    public $DEFAULT_LINKEDIN;
    public $DEFAULT_YOUTUBE;
    public $DEFAULT_TIKTOK;

    public $CHATGPT_DESCRIPTION;
    public $locale;

    // public $fields = [];

    public function mount()
    {
        // $this->getBreadcrumb();


        $this->locale = env('APP_LOCALE');

        $this->DEFAULT_COMPANY = Option::get('DEFAULT_COMPANY', null, $this->locale) ?? env('DEFAULT_COMPANY');
        $this->DEFAULT_CONTACT = Option::get('DEFAULT_CONTACT', null, $this->locale) ?? env('DEFAULT_CONTACT');
        $this->DEFAULT_ADDRESS = Option::get('DEFAULT_ADDRESS', null, $this->locale) ?? env('DEFAULT_ADDRESS');
        $this->DEFAULT_ADDRESS_2 = Option::get('DEFAULT_ADDRESS_2', null, $this->locale) ?? env('DEFAULT_ADDRESS_2');
        $this->DEFAULT_ZIPCODE = Option::get('DEFAULT_ZIPCODE', null, $this->locale) ?? env('DEFAULT_ZIPCODE');
        $this->DEFAULT_CITY = Option::get('DEFAULT_CITY', null, $this->locale) ?? env('DEFAULT_CITY');
        $this->DEFAULT_COUNTRY = Option::get('DEFAULT_COUNTRY', null, $this->locale) ?? env('DEFAULT_COUNTRY');
        $this->DEFAULT_PHONE = Option::get('DEFAULT_PHONE', null, $this->locale) ?? env('DEFAULT_PHONE');
        $this->DEFAULT_PHONE_LINK = Option::get('DEFAULT_PHONE_LINK', null, $this->locale) ?? env('DEFAULT_PHONE_LINK');
        $this->DEFAULT_EMAIL = Option::get('DEFAULT_EMAIL', null, $this->locale) ?? env('DEFAULT_EMAIL');
        $this->DEFAULT_KVK = Option::get('DEFAULT_KVK', null, $this->locale) ?? env('DEFAULT_KVK');
        $this->DEFAULT_VAT = Option::get('DEFAULT_VAT', null, $this->locale) ?? env('DEFAULT_VAT');
        $this->DEFAULT_FACEBOOK = Option::get('DEFAULT_FACEBOOK', null, $this->locale) ?? env('DEFAULT_FACEBOOK');
        $this->DEFAULT_INSTAGRAM = Option::get('DEFAULT_INSTAGRAM', null, $this->locale) ?? env('DEFAULT_INSTAGRAM');
        $this->DEFAULT_TWITTER = Option::get('DEFAULT_TWITTER', null, $this->locale) ?? env('DEFAULT_TWITTER');
        $this->DEFAULT_LINKEDIN = Option::get('DEFAULT_LINKEDIN', null, $this->locale) ?? env('DEFAULT_LINKEDIN');
        $this->DEFAULT_YOUTUBE = Option::get('DEFAULT_YOUTUBE', null, $this->locale) ?? env('DEFAULT_YOUTUBE');
        $this->DEFAULT_TIKTOK = Option::get('DEFAULT_TIKTOK', null, $this->locale) ?? env('DEFAULT_TIKTOK');

        $this->GOOGLE_MAPS_ZOOM = Option::get('GOOGLE_MAPS_ZOOM', null, $this->locale) ?? env('GOOGLE_MAPS_ZOOM');
        $this->DEFAULT_LATITUDE = Option::get('DEFAULT_LATITUDE', null, $this->locale) ?? env('DEFAULT_LATITUDE');
        $this->DEFAULT_LONGITUDE = Option::get('DEFAULT_LONGITUDE', null, $this->locale) ?? env('DEFAULT_LONGITUDE');

        $this->CHATGPT_DESCRIPTION = Option::get('CHATGPT_DESCRIPTION', null, $this->locale) ?? env('CHATGPT_DESCRIPTION');
        // $this->DEFAULT_LONGITUDE = Option::get('DEFAULT_LONGITUDE');

        $zoom = [];
        for ($i = 1; $i <= 21; $i++) {
            $zoom[$i] = $i;
        }

        $maptypes = [
            'roadmap' => 'Standaard',
            'satellite' => 'Satelliet',
            'hybrid' => 'Hybride',
            'terrain' => 'Terrein',
        ];

        $this->fields = [
            'DIVIDER_1' => ['type' => 'divider', 'title' => 'Algemeen', 'read' => true],
            'CHATGPT_DESCRIPTION' => ['type' => 'textarea', 'title' => 'Omschrijf het bedrijf voor ChatGPT', 'read' => true, 'value' => Option::get('CHATGPT_DESCRIPTION', null, $this->locale) ?? env('CHATGPT_DESCRIPTION')],
            'DEFAULT_COMPANY' => ['type' => 'text', 'title' => 'Bedrijfsnaam', 'read' => true, 'value' => Option::get('DEFAULT_COMPANY', null, $this->locale) ?? env('DEFAULT_COMPANY')],
            'DEFAULT_CONTACT' => ['type' => 'text', 'title' => 'Contactpersoon', 'read' => true, 'value' => Option::get('DEFAULT_CONTACT', null, $this->locale) ?? env('DEFAULT_CONTACT')],
            'DEFAULT_ADDRESS' => ['type' => 'text', 'title' => 'Adres', 'read' => true, 'value' => Option::get('DEFAULT_ADDRESS', null, $this->locale) ?? env('DEFAULT_ADDRESS')],
            'DEFAULT_ADDRESS_2' => ['type' => 'text', 'title' => 'Adres 2', 'read' => true, 'value' => Option::get('DEFAULT_ADDRESS_2', null, $this->locale) ?? env('DEFAULT_ADDRESS_2')],
            'DEFAULT_ZIPCODE' => ['type' => 'text', 'title' => 'Postcode', 'read' => true, 'value' => Option::get('DEFAULT_ZIPCODE', null, $this->locale) ?? env('DEFAULT_ZIPCODE')],
            'DEFAULT_CITY' => ['type' => 'text', 'title' => 'Plaats', 'read' => true, 'value' => Option::get('DEFAULT_CITY', null, $this->locale) ?? env('DEFAULT_CITY')],
            'DEFAULT_COUNTRY' => ['type' => 'text', 'title' => 'Land', 'read' => true, 'value' => Option::get('DEFAULT_COUNTRY', null, $this->locale) ?? env('DEFAULT_COUNTRY')],
            'DEFAULT_PHONE' => ['type' => 'text', 'title' => 'Telefoon', 'read' => true, 'value' => Option::get('DEFAULT_PHONE', null, $this->locale) ?? env('DEFAULT_PHONE')],
            'DEFAULT_PHONE_LINK' => ['type' => 'text', 'title' => 'Telefoon link', 'read' => true, 'value' => Option::get('DEFAULT_PHONE_LINK', null, $this->locale) ?? env('DEFAULT_PHONE_LINK')],
            'DEFAULT_EMAIL' => ['type' => 'text', 'title' => 'Email', 'read' => true, 'value' => Option::get('DEFAULT_EMAIL', null, $this->locale) ?? env('DEFAULT_EMAIL')],
            'DEFAULT_KVK' => ['type' => 'text', 'title' => 'KVK', 'read' => true, 'value' => Option::get('DEFAULT_KVK', null, $this->locale) ?? env('DEFAULT_KVK')],
            'DEFAULT_VAT' => ['type' => 'text', 'title' => 'BTW. Nr.', 'read' => true, 'value' => Option::get('DEFAULT_VAT', null, $this->locale) ?? env('DEFAULT_VAT')],
            'DEFAULT_FACEBOOK' => ['type' => 'text', 'title' => 'Facebook', 'read' => true, 'value' => Option::get('DEFAULT_FACEBOOK', null, $this->locale) ?? env('DEFAULT_FACEBOOK')],
            'DEFAULT_INSTAGRAM' => ['type' => 'text', 'title' => 'Instagram', 'read' => true, 'value' => Option::get('DEFAULT_INSTAGRAM', null, $this->locale) ?? env('DEFAULT_INSTAGRAM')],
            'DEFAULT_TWITTER' => ['type' => 'text', 'title' => 'Twitter', 'read' => true, 'value' => Option::get('DEFAULT_TWITTER', null, $this->locale) ?? env('DEFAULT_TWITTER')],
            'DEFAULT_LINKEDIN' => ['type' => 'text', 'title' => 'LinkedIn', 'read' => true, 'value' => Option::get('DEFAULT_LINKEDIN', null, $this->locale) ?? env('DEFAULT_LINKEDIN')],
            'DEFAULT_YOUTUBE' => ['type' => 'text', 'title' => 'Youtube', 'read' => true, 'value' => Option::get('DEFAULT_YOUTUBE', null, $this->locale) ?? env('DEFAULT_YOUTUBE')],
            'DEFAULT_TIKTOK' => ['type' => 'text', 'title' => 'Tiktok', 'read' => true, 'value' => Option::get('DEFAULT_TIKTOK', null, $this->locale) ?? env('DEFAULT_TIKTOK')],
            'DIVIDER_2' => ['type' => 'divider', 'title' => 'Geografische gegevens', 'read' => true],
            'DEFAULT_LATITUDE' => ['type' => 'text', 'title' => 'Latitude', 'read' => true, 'value' => Option::get('DEFAULT_LATITUDE', null, $this->locale) ?? env('DEFAULT_LATITUDE')],
            'DEFAULT_LONGITUDE' => ['type' => 'text', 'title' => 'Longitude', 'read' => true, 'value' => Option::get('DEFAULT_LONGITUDE', null, $this->locale) ?? env('DEFAULT_LONGITUDE')],
            'DIVIDER_3' => ['type' => 'divider', 'title' => 'Google maps', 'read' => true],
            'GOOGLE_MAPS_ZOOM' => ['type' => 'select', 'title' => 'Zoom', 'read' => true, 'value' => Option::get('GOOGLE_MAPS_ZOOM', null, $this->locale) ?? env('GOOGLE_MAPS_ZOOM'), 'options' => $zoom],
            'GOOGLE_MAPS_MAP_TYPE' => ['type' => 'select', 'title' => 'Map type', 'read' => true, 'value' => Option::get('GOOGLE_MAPS_MAP_TYPE', null, $this->locale) ?? env('GOOGLE_MAPS_MAP_TYPE'), 'options' => $maptypes],
        ];
    }

    public function render()
    {
        $this->markers[] = ['id' => 'general', 'latitude' => (float)$this->DEFAULT_LATITUDE, 'longitude' => (float)$this->DEFAULT_LONGITUDE, 'title' => 'Algemene instellingen', 'draggable' => true];
        $this->DEFAULT_LATITUDE = $this->DEFAULT_LATITUDE;
        $this->DEFAULT_LONGITUDE = $this->DEFAULT_LONGITUDE;
        return view('manta-cms::livewire.option.option-edit');
    }

    public function updatedGOOGLEMAPSZOOM()
    {
        $this->dispatch('updateMapCenter', ['latitude' => $this->DEFAULT_LATITUDE, 'longitude' => $this->DEFAULT_LONGITUDE, 'zoom' => (int)$this->GOOGLE_MAPS_ZOOM]);
    }

    public function updatedMapsType()
    {
        $this->dispatch('updateMapType', ['type' => $this->GOOGLE_MAPS_MAP_TYPE]);
    }

    public function save()
    {
        // $this->validate([
        //     'DEFAULT_LATITUDE' => 'required',
        //     'DEFAULT_LONGITUDE' => 'required'
        // ], [
        //     'DEFAULT_LATITUDE.required' => 'De latitude is verplicht',
        //     'DEFAULT_LONGITUDE.required' => 'De longitude is verplicht'
        // ]);


        Option::set('DEFAULT_LATITUDE', $this->DEFAULT_LATITUDE, null, $this->locale);
        Option::set('DEFAULT_LONGITUDE', $this->DEFAULT_LONGITUDE, null, $this->locale);
        Option::set('GOOGLE_MAPS_ZOOM', $this->GOOGLE_MAPS_ZOOM, null, $this->locale);
        Option::set('GOOGLE_MAPS_MAP_TYPE', $this->GOOGLE_MAPS_MAP_TYPE, null, $this->locale);

        Option::set('DEFAULT_COMPANY', $this->DEFAULT_COMPANY, null, $this->locale);
        Option::set('DEFAULT_CONTACT', $this->DEFAULT_CONTACT, null, $this->locale);
        Option::set('DEFAULT_ADDRESS', $this->DEFAULT_ADDRESS, null, $this->locale);
        Option::set('DEFAULT_ADDRESS_2', $this->DEFAULT_ADDRESS_2, null, $this->locale);
        Option::set('DEFAULT_ZIPCODE', $this->DEFAULT_ZIPCODE, null, $this->locale);
        Option::set('DEFAULT_CITY', $this->DEFAULT_CITY, null, $this->locale);
        Option::set('DEFAULT_COUNTRY', $this->DEFAULT_COUNTRY, null, $this->locale);
        Option::set('DEFAULT_PHONE', $this->DEFAULT_PHONE, null, $this->locale);
        Option::set('DEFAULT_PHONE_LINK', $this->DEFAULT_PHONE_LINK, null, $this->locale);
        Option::set('DEFAULT_EMAIL', $this->DEFAULT_EMAIL, null, $this->locale);
        Option::set('DEFAULT_KVK', $this->DEFAULT_KVK, null, $this->locale);
        Option::set('DEFAULT_VAT', $this->DEFAULT_VAT, null, $this->locale);
        Option::set('DEFAULT_FACEBOOK', $this->DEFAULT_FACEBOOK, null, $this->locale);
        Option::set('DEFAULT_INSTAGRAM', $this->DEFAULT_INSTAGRAM, null, $this->locale);
        Option::set('DEFAULT_TWITTER', $this->DEFAULT_TWITTER, null, $this->locale);
        Option::set('DEFAULT_LINKEDIN', $this->DEFAULT_LINKEDIN, null, $this->locale);
        Option::set('DEFAULT_YOUTUBE', $this->DEFAULT_YOUTUBE, null, $this->locale);
        Option::set('DEFAULT_TIKTOK', $this->DEFAULT_TIKTOK, null, $this->locale);

        Option::set('CHATGPT_DESCRIPTION', $this->CHATGPT_DESCRIPTION, null, $this->locale);

        Flux::toast('Opgeslagen', duration: 1000, variant: 'success');
    }
}
