<?php

namespace Manta\FluxCMS\Traits;

use GuzzleHttp\Client;
use Manta\FluxCMS\Models\Option;
use App\Services\GoogleGeocodeService;
use Flux\Flux;

trait MantaMapsTrait
{
    // Google maps
    public ?float $DEFAULT_LATITUDE = null;
    public ?float $DEFAULT_LONGITUDE = null;
    public ?int $GOOGLE_MAPS_ZOOM = null;
    public bool $maps_set_center = false;
    public ?string $maps_id = 'default';
    public array $markers = [];
    public ?string $address = null;
    public ?string $address_nr = null;
    public ?string $city = null;
    // public ?string $country = 'nl';
    public array $address_suggestions = [];
    public ?string $error_message = null;
    public bool $is_loading = false;

    protected function getGeocodeService(): GoogleGeocodeService
    {
        return app(GoogleGeocodeService::class);
    }

    function getCoordinates($address = null)
    {
        if ($address == null) {
            $this->dispatch('toastr:error', ['message' => 'Adres is verplicht']);
            return false;
        }

        $apiKey = env('GOOGLE_KEY_PHP');
        $client = new Client();
        $response = $client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json', [
            'query' => [
                'address' => $address,
                'key' => $apiKey
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if ($data['status'] == 'OK') {
            $location = $data['results'][0]['geometry']['location'];
            $this->DEFAULT_LATITUDE = $location['lat'];
            $this->DEFAULT_LONGITUDE = $location['lng'];
        }
    }

    public function getByAddress(): void
    {
        $this->is_loading = true;
        $this->error_message = null;

        try {
            $searchAddress = $this->formatSearchAddress();

            if (empty($searchAddress)) {
                $this->error_message = 'Vul een adres in om te zoeken.';
                return;
            }

            $result = $this->getGeocodeService()->getCoordinates($searchAddress);

            if ($result === null) {
                $this->error_message = 'Kon geen coördinaten vinden voor dit adres.';
                return;
            }

            $this->updateLocationFromResult($result);
            $this->dispatch('address-found', [
                'latitude' => $this->DEFAULT_LATITUDE,
                'longitude' => $this->DEFAULT_LONGITUDE
            ]);

            // Dispatch updateMarkerPosition event om de marker op de kaart te updaten
            if (property_exists($this, 'id')) {
                $this->dispatch(
                    'updateMarkerPosition',
                    markerId: 'marker' . $this->id,
                    latitude: (float)$this->DEFAULT_LATITUDE,
                    longitude: (float)$this->DEFAULT_LONGITUDE
                );
            }
        } catch (\Exception $e) {
            $this->handleGeocodeError('Geocoding error', $e, ['address' => $searchAddress ?? null]);
        } finally {
            $this->is_loading = false;
        }
    }

    public function getAddressSuggestions(): void
    {
        if (strlen($this->address) < 3) {
            $this->address_suggestions = [];
            return;
        }

        $this->address_suggestions = $this->getGeocodeService()->getAddressSuggestions($this->address);
    }

    public function selectAddressSuggestion(string $address): void
    {
        $this->address = $address;
        $this->address_suggestions = [];
        $this->getByAddress();
    }

    public function updateLocationByClick(float $latitude, float $longitude): void
    {
        $this->is_loading = true;
        $this->error_message = null;

        try {
            $result = $this->getGeocodeService()->getAddress($latitude, $longitude);

            if ($result === null) {
                $this->error_message = 'Kon geen adres vinden voor deze locatie.';
                return;
            }

            $this->updateLocationFromResult($result);
        } catch (\Exception $e) {
            $this->handleGeocodeError('Reverse geocoding error', $e, [
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);
        } finally {
            $this->is_loading = false;
        }
    }

    protected function updateLocationFromResult(array $result): void
    {
        $this->DEFAULT_LATITUDE = $result['latitude'];
        $this->DEFAULT_LONGITUDE = $result['longitude'];
        $this->address = $result['route'] . ' ' . $result['street_number'];
        $this->address_nr = $result['street_number'];
        $this->city = $result['locality'];
        $this->country = $result['country'];
    }

    protected function initializeLocationData(): void
    {
        $this->fill($this->house->only([
            'latitude',
            'longitude',
            'address',
            'address_nr',
            'city',
            'country'
        ]));

        $this->formatAddress();
    }

    protected function initializeMapSettings(): void
    {
        $this->DEFAULT_LATITUDE = $this->latitude ?? (float)Option::get('DEFAULT_LATITUDE', null, app()->getLocale());
        $this->DEFAULT_LONGITUDE = $this->longitude ?? (float)Option::get('DEFAULT_LONGITUDE', null, app()->getLocale());
        $this->GOOGLE_MAPS_ZOOM = (int)Option::get('GOOGLE_MAPS_ZOOM', null, app()->getLocale());
    }

    protected function formatSearchAddress(): string
    {
        return trim(implode(', ', array_filter([
            $this->address,
            $this->city,
            $this->country
        ])));
    }

    protected function handleGeocodeError(string $type, \Exception $e, array $context = []): void
    {
        $this->error_message = 'Er is een fout opgetreden bij het ophalen van de gegevens.';
        logger()->error($type . ': ' . $e->getMessage(), array_merge($context, ['exception' => $e]));
    }

    protected function formatAddress(): void
    {
        if (!empty($this->address_nr)) {
            $this->address .= ' ' . $this->address_nr;
        }
        if (!empty($this->city)) {
            $this->address .= ', ' . $this->city;
        }
        if (!empty($this->country)) {
            $this->address .= ', ' . $this->country;
        }
    }

    protected function updateMarkers(): void
    {
        $this->markers = [[
            'id' => $this->house->id,
            'latitude' => $this->DEFAULT_LATITUDE,
            'longitude' => $this->DEFAULT_LONGITUDE,
            'title' => $this->house->title ?? 'Location',
            'draggable' => true
        ]];
    }
}
