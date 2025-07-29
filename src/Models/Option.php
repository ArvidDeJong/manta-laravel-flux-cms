<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

/**
 * Option model voor het opslaan van configuratieopties en instellingen.
 */
class Option extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'locale',
        'pid',
        'model',
        'key',
        'value',
        'data',        // Nieuwe kolom
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
    ];

    // De data wordt automatisch als array gecast via $casts

    /**
     * Statisch geheugen voor de huidige gebruiker ID om meerdere Auth checks te voorkomen.
     * 
     * @var int|null
     */
    private static ?int $userId = null;

    /**
     * Stel een optie in met een bepaalde key en waarde.
     *
     * @param string $key De key van de optie
     * @param mixed $value De waarde van de optie
     * @param string|null $model Optioneel gekoppeld model
     * @param string|null $locale Optionele taalinstelling
     * @return void
     */
    public static function set(string $key, $value, ?string $model = null, ?string $locale = null): void
    {
        if ($locale == null) {
            $locale = env('APP_LOCALE');
        }


        // Initialize user ID once per request
        if (self::$userId === null) {
            self::$userId = Auth::guard('staff')->id() ?? null;
            if (self::$userId === null) {
                return; // Early exit if no authenticated staff user
            }
        }

        self::updateOrCreate(
            ['key' => $key, 'model' => $model, 'locale' => $locale],
            ['updated_by' => self::$userId, 'value' => $value]
        );
    }

    /**
     * Haal een optie op basis van key, model en locale.
     *
     * @param string $key De key van de optie
     * @param string|null $model Optioneel gekoppeld model
     * @param string|null $locale Optionele taalinstelling
     * @return mixed De waarde van de optie of null
     */
    public static function get(string $key, ?string $model = null, ?string $locale = null): mixed
    {

        $defaults = [
            'DEFAULT_LATITUDE' => env('DEFAULT_LATITUDE'),
            'DEFAULT_LONGITUDE' => env('DEFAULT_LONGITUDE'),
            'GOOGLE_MAPS_ZOOM' => env('GOOGLE_MAPS_ZOOM'),
        ];
        if ($locale == null) {
            $locale = env('APP_LOCALE');
        }

        if ($locale != null) {
            $item = self::where(['key' => $key, 'model' => $model, 'locale' => $locale])->first();
        }
        // dd($key, $model);
        // Check if item is found and not empty, else set default if available
        if ($item) {
            return $item->value;
        }
        $item = self::where(['key' => $key, 'model' => $model, 'locale' => env('APP_LOCALE')])->first();
        if ($item) {
            return $item->value;
        }
        // if ($key == 'DEFAULT_ADDRESS') {
        //     dump($key, $model, $item);
        // }
        // Check if a default value exists in env variables
        $defaultValue = $defaults[$key] ?? null;

        if ($defaultValue == null && !empty($defaultValue)) {

            self::set($key, $defaultValue, $model, $locale);
            return $defaultValue;
        }

        return null;
    }
    
    /**
     * Scope voor het filteren op een specifieke key.
     *
     * @param Builder $query
     * @param string $key
     * @return Builder
     */
    public function scopeByKey(Builder $query, string $key): Builder
    {
        return $query->where('key', $key);
    }
    
    /**
     * Scope voor het filteren op een specifiek model.
     *
     * @param Builder $query
     * @param string $model
     * @return Builder
     */
    public function scopeByModel(Builder $query, string $model): Builder
    {
        return $query->where('model', $model);
    }
    
    /**
     * Scope voor het filteren op een specifieke locale.
     *
     * @param Builder $query
     * @param string $locale
     * @return Builder
     */
    public function scopeByLocale(Builder $query, string $locale): Builder
    {
        return $query->where('locale', $locale);
    }
    
    /**
     * Scope voor het filteren op actieve opties.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }
    
    /**
     * Relatie naar het bedrijf (als die bestaat).
     *
     * @return BelongsTo|null
     */
    public function company(): ?BelongsTo
    {
        if (class_exists('\App\Models\Company')) {
            return $this->belongsTo('\App\Models\Company');
        }
        
        return null;
    }
}
