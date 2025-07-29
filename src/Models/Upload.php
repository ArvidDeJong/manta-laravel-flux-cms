<?php

namespace Manta\FluxCMS\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Imagick\Driver;
use Intervention\Image\ImageManager;
use Manta\FluxCMS\Services\PdfToImage;
use setasign\Fpdi\Fpdi;

class Upload extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'uploads';

    protected $fillable = [
        'created_by',
        'updated_by',
        'deleted_by',
        'company_id',
        'host',
        'pid',
        'locale',
        'active',
        'sort',
        'main',
        'user_id',
        'title',
        'seo_title',
        'private',
        'disk',
        'url',
        'location',
        'filename',
        'extension',
        'mime',
        'size',
        'model',
        'model_id',
        'identifier',
        'filenameOriginal',
        'image',
        'pdfLock',
        'content',
        'error',
        'pages',
        'data',        // Nieuwe kolom
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
        'main' => 'boolean',
        'private' => 'boolean',
        'image' => 'boolean',
        'pdfLock' => 'boolean',
        'pages' => 'integer',
        'size' => 'integer',
        'data' => 'array',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [];

    // De data wordt automatisch als array gecast via $casts

    /**
     * Relatie naar het bijbehorende Company model.
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Scope voor het filteren op actieve uploads.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Scope voor het filteren op afbeeldingen.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeImages(Builder $query): Builder
    {
        return $query->where('image', true);
    }

    /**
     * Scope voor het filteren op model type en ID.
     *
     * @param Builder $query
     * @param string $model
     * @param int|string $modelId
     * @return Builder
     */
    public function scopeForModel(Builder $query, string $model, int|string $modelId): Builder
    {
        return $query->where('model', $model)->where('model_id', $modelId);
    }

    /**
     * Scope voor het filteren op bestandsextensie.
     *
     * @param Builder $query
     * @param string|array $extension Één extensie of array van extensies
     * @return Builder
     */
    public function scopeByExtension(Builder $query, string|array $extension): Builder
    {
        return is_array($extension)
            ? $query->whereIn('extension', $extension)
            : $query->where('extension', $extension);
    }

    /**
     * Upload een bestand naar de server en sla de informatie op in de database.
     *
     * @param mixed $file Het bestand of de string inhoud van een bestand
     * @param string $model Het modeltype waarmee dit bestand geassocieerd is
     * @param int|string|null $model_id De ID van het model
     * @param array $options Extra opties voor de upload
     * @return bool|Upload|null Het Upload object of false bij mislukking
     */
    public function upload(mixed $file, string $model, int|string|null $model_id, array $options = []): bool|self|null
    {
        if (isset($options['replace']) && !isset($options['upload_id'])) {
            return false;
        }
        if (isset($options['upload_id'])) {
            $upload = Upload::find($options['upload_id']);
            if ($upload) {
                $extension = $upload->extension;
                $mime = $upload->mime;
                $size = $upload->size;
            }
        } else {
            $upload = null;
        }

        // Default disk en directory locatie instellen
        $disk = $options['disk'] ?? config('manta-cms.media.disk');

        $location = $options['location'] ?? 'uploads/' . env('THEME') . '/' . date('Y') . '/' . date('m') . '/';

        // Map maken met specifieke rechten
        Storage::disk($disk)->makeDirectory($location, 0755, true, true); // Recursief en publiek

        if (is_string($file) && !is_object($file)) {
            // Controleer of de bestandsnaam in de opties is opgegeven, anders gebruik de oorspronkelijke bestandsnaam
            $filenameOriginal = isset($options['filename']) ? $options['filename'] : null;

            // Als vervangen is ingeschakeld en er een upload is, gebruik de bestaande gegevens
            if (isset($options['replace']) && $options['replace'] == 1 && $upload) {
                $disk = $upload->disk;
                $location = $upload->location;
                $filename = $upload->filename;
            } else {
                // Anders genereer een unieke bestandsnaam
                $filename = $this->uniqueFileName($filenameOriginal, $disk, $location, false);
            }

            // Controleer of het bestand met succes is opgeslagen
            if (isset($disk, $location, $filename) && Storage::disk($disk)->put($location . $filename, $file)) {
                if (Storage::disk($disk)->exists($location . $filename)) {
                    // Als het bestand bestaat, haal de MIME-type en grootte op
                    $extension = strtolower(pathinfo($filenameOriginal, PATHINFO_EXTENSION));
                    $mime = Storage::disk($disk)->mimeType($location . $filename);
                    $size = Storage::disk($disk)->size($location . $filename);
                }
            }
        } elseif (is_object($file)) {
            $filenameOriginal = $file->getClientOriginalName();
            $extension = strtolower($file->getClientOriginalExtension());
            $mime = $file->getMimeType();
            $size = $file->getSize();
            // Unieke bestandsnaam genereren
            $filename = $this->uniqueFileName($file->getClientOriginalName(), $disk, $location, true);
            // Bestand opslaan met specifieke rechten
            if (Storage::disk($disk)->putFileAs($location, $file, $filename, ['visibility' => 'public'])) {
                // Stel bestandsrechten in na het uploaden (leesbaar door anderen)
                Storage::disk($disk)->setVisibility($location . $filename, 'public');
            }
        }
        $extension =  strtolower($extension);
        $values = [
            'model' => $model,
            'model_id' => $model_id,
            'disk' => $disk,
            'url' => env('APP_URL'),
            'location' => $location,
            'filename' => $filename,
            'filenameOriginal' => $filenameOriginal,
            'mime' => $mime,
            'size' => $size,
            'extension' => $extension,
            'image' => in_array($extension, ['jpg', 'jpeg', 'png']) ? 1 : 0,
        ];

        if (isset($options['replace']) && $options['replace'] == 1) {
            $values['updated_by'] = auth('staff')->user()->name;
            $upload->update($values);
        } else {
            $values['title'] = isset($options['main']) ? $options['main'] : $filenameOriginal;
            $values['seo_title'] = isset($options['main']) ? $options['main'] : $filenameOriginal;
            $values['content'] = isset($options['content']) ? $options['content'] : null;
            $values['private'] = isset($options['private']) ? $options['private'] : 0;
            $values['identifier'] = isset($options['identifier']) ? $options['identifier'] : null;
            $values['company_id'] = isset($options['company_id']) ? $options['company_id'] : 1;
            $values['locale'] = isset($options['locale']) ? $options['locale'] : getLocaleManta();
            $values['sort'] = isset($options['sort']) ? $options['sort'] : 0;
            $values['main'] = isset($options['main']) ? $options['main'] : 0;
            $values['host'] = isset($options['host']) ? $options['host'] : Request::getHost();
            $values['created_by'] = auth('staff')->user()->name;
            $values['user_id'] = auth('staff')->user()->id;
            $upload = upload::create($values);

            if ($model_id == null) {
                $upload->update(['model_id' => $upload->id]);
            }
        }

        if (in_array($upload->extension, ['jpg', 'jpeg', 'png']) && config('manta-cms.media.thumbnails')) {
            foreach (config('manta-cms.media.thumbnails') as $size) {
                $upload->resize($size);
            }
        }
        $upload = Upload::find($upload->id);
        Log::info("Upload created with ID: {$upload->id}, extension: {$upload->extension}");
        if (in_array($upload->extension, ['pdf'])) {
            Log::info("PDF detected, calling pdfToPages for upload ID: {$upload->id}");
            $upload->pdfToPages();
        } else {
            Log::info("Not a PDF file, extension is: {$upload->extension}");
        }
        return $upload;
    }

    /**
     * Genereer een unieke bestandsnaam om overschrijving te voorkomen.
     *
     * @param string $filename Originele bestandsnaam
     * @param string $disk Storage disk
     * @param string $location Opslaglocatie
     * @param bool $timename Of de tijd voor de bestandsnaam moet worden geplaatst
     * @return string|null Unieke bestandsnaam of null bij een fout
     */
    public function uniqueFileName(string $filename, string $disk, string $location, bool $timename = true): ?string
    {
        try {
            // Extract base name and extension
            $basename = pathinfo($filename, PATHINFO_FILENAME);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);

            // Generate a safe and clean filename
            $basename = Str::slug(substr($basename, 0, 20));

            // Optionally prepend the current timestamp
            if ($timename) {
                $basename = time() . '-' . $basename;
            }

            // Generate the full file path
            $fullfile = $basename . '.' . $extension;

            // Check if the file exists and generate a new name if necessary
            if (Storage::disk($disk)->exists($location . $fullfile)) {
                $imageToken = substr(sha1(mt_rand()), 0, 5);
                $fullfile = $basename . '-' . $imageToken . '.' . $extension;
            }

            // Return the unique filename with path
            return $fullfile;
        } catch (\Exception $e) {
            // Log error details
            Log::error("Error generating a unique filename", [
                'class' => __CLASS__,
                'function' => __FUNCTION__,
                'line' => __LINE__,
                'location' => $location,
                'filename' => $filename,
                'exception' => $e->getMessage(),
                'user' => auth('staff')->user() ? auth('staff')->user()->toArray() : 'N/A'
            ]);

            return null;
        }
    }

    /**
     * Genereer het volledige pad naar een bestand, inclusief eventuele thumbnail paden.
     *
     * @param int|null $size Thumbnail grootte indien nodig
     * @param bool $check_exist Controleer of thumbnail bestaat en maak deze indien nodig
     * @return string Volledig pad naar het bestand of thumbnail
     */
    public function fullPath(?int $size = null, bool $check_exist = false): string
    {
        $baseLocation = $this->location;
        $thumbnailLocation = 'cache/thumbnails/';

        // Correctly format the location for public disk
        if ($this->disk == 'public') {
            $baseLocation = "/storage/" . $baseLocation;
        }

        $finalPath = $baseLocation . $this->filename;
        if ($size !== null) {
            $thumbnailPath = $baseLocation . $thumbnailLocation . $size . '/' . $this->filename;
            $fullUrl = $this->url . $thumbnailPath;

            // Check if the specific thumbnail exists
            if ($check_exist && !file_exists($fullUrl)) {
                // Resize only if the specified size thumbnail doesn't exist
                $this->resize($size);
            }
            $finalPath = $thumbnailPath;
        }

        return $this->url . $finalPath;
    }
    /**
     * Maak een thumbnail van een afbeelding met opgegeven afmetingen.
     *
     * @param int $width Gewenste breedte van de thumbnail
     * @param int|null $height Gewenste hoogte van de thumbnail of null voor automatische hoogte
     * @return void
     * @throws \InvalidArgumentException Wanneer zowel width als height null zijn
     */
    public function resize(int $width = 400, ?int $height = null): void
    {
        // Check if both width and height are null and throw exception if needed
        if ($width === null && $height === null) {
            throw new \InvalidArgumentException("Both width and height cannot be null.");
        }

        // Create a new ImageManager instance
        $manager = new ImageManager(new Driver());

        // Fetch the image from storage
        try {
            $imagePath = $this->location . $this->filename;
            $imageContent = Storage::disk($this->disk)->get($imagePath);
            $image = $manager->read($imageContent);

            // Resize the image maintaining the aspect ratio
            if ($height === null) {
                $image->scaleDown(width: $width);
            } else {
                $image->scaleDown(height: $height);
            }

            // Construct the thumbnail directory path
            $folderSize = $width ?: $height;
            $thumbnailDirectory = $this->location . 'cache/thumbnails/' . $folderSize . '/';
            $thumbnailPath = $thumbnailDirectory . $this->filename;

            // Make directory if it doesn't exist
            if (!Storage::disk($this->disk)->exists($thumbnailPath)) {
                Storage::disk($this->disk)->makeDirectory($thumbnailDirectory, 0755, true);
            }

            // Save the resized image
            Storage::disk($this->disk)->put($thumbnailPath, (string)$image->toPng());
        } catch (\Exception $e) {
            Log::error('Error resizing image: ' . $e->getMessage());
            // Optionally: throw $e; // Rethrow the exception if needed
        }
    }


    /**
     * Converteer een PDF naar afbeeldingen per pagina.
     *
     * @return void
     */
    public function pdfToPages(): void
    {
        Log::info("pdfToPages called for upload ID: {$this->id}, extension: {$this->extension}");

        if ($this->pdfLock == 0 && $this->extension == 'pdf' && Storage::disk($this->disk)->exists($this->location . $this->filename)) {
            Log::info("pdfToPages conditions met, starting processing for upload ID: {$this->id}");
            $this->pdfLock = 1;
            $this->save();

            Log::info("Setting up temp directory for upload ID: {$this->id}");
            Storage::disk('local')->put("pdf_temp/" . $this->id . "/" . $this->filename, Storage::disk($this->disk)->get($this->location . $this->filename));
            $temp_location = Storage::disk('local')->path("pdf_temp/" . $this->id . "/" . $this->filename);
            Log::info("Temp file created at: {$temp_location}");
            /**
             * * Try to read PDF
             */
            Log::info("Attempting to read PDF file for upload ID: {$this->id}");
            try {
                $pdfi = new Fpdi();
                $pdfi->setSourceFile($temp_location);
                Log::info("PDF file successfully read with Fpdi for upload ID: {$this->id}");
            } catch (\Exception $e) {
                Log::error("Failed to read PDF with Fpdi for upload ID: {$this->id}: " . $e->getMessage());
                $this->error = $e->getMessage();
                $this->save();
            }

            /**
             * * Try to create thumbnails
             */
            Log::info("Starting thumbnail creation for upload ID: {$this->id}");
            try {
                $pdf = new PdfToImage($temp_location);
                Log::info("PdfToImage object created for upload ID: {$this->id}");

                Log::info("Setting PDF properties for upload ID: {$this->id}");
                $pdf->setCompressionQuality(60);
                $pdf->setOutputFormat('jpg');
                $pdf->setColorspace(1);

                Log::info("Getting number of pages for upload ID: {$this->id}");
                $this->pages = $pdf->getNumberOfPages();
                Log::info("PDF has {$this->pages} pages for upload ID: {$this->id}");

                // Zorg ervoor dat de cache directory bestaat
                $cacheDir = $this->location . "cache/pdf/" . $this->id;
                Log::info("Cache directory path: {$cacheDir} for upload ID: {$this->id}");
                if (!Storage::disk($this->disk)->exists($cacheDir)) {
                    Log::info("Creating cache directory for upload ID: {$this->id}");
                    Storage::disk($this->disk)->makeDirectory($cacheDir);
                    Log::info("Cache directory created for upload ID: {$this->id}");
                } else {
                    Log::info("Cache directory already exists for upload ID: {$this->id}");
                }

                Log::info("Starting to process {$this->pages} pages for upload ID: {$this->id}");
                foreach (range(1, $this->pages) as $pageNumber) {
                    Log::info("Processing page {$pageNumber} for upload ID: {$this->id}");
                    $tempPath = storage_path("app/private/pdf_temp/" . $this->id . "/") . "page" . $pageNumber . ".jpg";
                    Log::info("Temp image path: {$tempPath} for upload ID: {$this->id}");

                    // Genereer de afbeelding
                    Log::info("Calling setPage({$pageNumber})->saveImage() for upload ID: {$this->id}");
                    try {
                        $pdf->setPage($pageNumber)->saveImage($tempPath);
                        Log::info("Image generated successfully for page {$pageNumber}, upload ID: {$this->id}");
                    } catch (\Exception $e) {
                        Log::error("Failed to generate image for page {$pageNumber}, upload ID: {$this->id}: " . $e->getMessage());
                        continue; // Skip deze pagina en ga door met de volgende
                    }

                    // Controleer of het tijdelijke bestand bestaat
                    if (file_exists($tempPath)) {
                        $imageContent = file_get_contents($tempPath);
                        if ($imageContent !== false) {
                            // Sla het bestand op in de storage
                            $success = Storage::disk($this->disk)->put($cacheDir . "/page{$pageNumber}.jpg", $imageContent, 'public');
                            if (!$success) {
                                Log::error("Failed to save PDF page {$pageNumber} for upload {$this->id}");
                            }
                        } else {
                            Log::error("Could not read temp file: {$tempPath}");
                        }
                    } else {
                        Log::error("Temp file does not exist: {$tempPath}");
                    }
                }
                Storage::disk('local')->deleteDirectory("pdf_temp/" . $this->id . "/");
                Storage::disk('local')->deleteDirectory("pdf_temp" . $this->location . "../");

                $this->pages = $pdf->getNumberOfPages();
                $this->save();
            } catch (\Exception $e) {
                $this->error = $e->getMessage();
                $this->save();
            }
        }
    }

    /**
     * Haal alle geconverteerde PDF pagina-afbeeldingen op.
     *
     * @return array Lijst met pad naar alle gegenereerde afbeeldingen
     */
    public function pdfGetImages(): array
    {
        return Storage::disk($this->disk)->allFiles($this->location . "/cache/pdf/" . $this->id);
    }

    /**
     * Converteer een bestandsgrootte string (bijv. '5MB') naar bytes.
     *
     * @param string $size Bestandsgrootte met eenheid
     * @return int Grootte in bytes
     */
    public function parseSize(string $size): int
    {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    /**
     * Converteer bestandsgrootte in bytes naar leesbare formaat (KB, MB, GB, etc.).
     *
     * @param int $decimals Aantal decimalen in het resultaat
     * @return string Leesbare bestandsgrootte met eenheid
     */
    public function convertFilesize(int $decimals = 2): string
    {
        $bytes = $this->size;
        $size = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . @$size[$factor];
    }

    /**
     * Haal de afbeeldingsgegevens op, inclusief paden voor thumbnails indien nodig.
     *
     * @param int|null $size Grootte van de thumbnail indien nodig
     * @return array Informatie over de afbeelding, inclusief src en url
     * @throws \InvalidArgumentException bij ongeldige parameters
     */
    public function getImage(?int $size = null): array
    {

        $return     = ['src' => null, 'url' => null];
        $location   = $this->location;

        if ($this->filename && in_array($this->extension, ['jpg', 'jpeg', 'png', 'svg'])) {


            if (!Storage::disk($this->disk)->exists($location . $this->filename) && $size == null) {
                // dd(__FILE__ . ' ' . __LINE__, $location . $this->filename, $this->disk, $this->id, $this);
                return $return; // Ensure an array is returned
            } elseif (!Storage::disk($this->disk)->exists($location . "cache/thumbnails/{$size}/" . $this->filename) && $size != null) {
                // dd(__FILE__ . ' ' . __LINE__, $location . $this->filename, $this->disk, $this->id, $this);
                $this->resize($size);
            }

            if ($size != null) {
                $location = $location . "cache/thumbnails/{$size}/";
            }
            if ($this->disk == 'public') {
                $location = "/storage/" . $location;
            }

            $return['src'] = env('APP_URL') .  $location . $this->filename;
            $return['url'] = env('APP_URL')  .  $location . $this->filename;
            $return['file'] = $this->filename;
        } elseif ($this->filename && in_array($this->extension, ['pdf']) && count($this->pdfGetImages()) > 0) {
            $location = '';
            if ($this->disk == 'public') {
                $location = "/storage/";
            }
            $return['src'] = $location . $this->pdfGetImages()[0];
            $return['url'] = $location . $this->pdfGetImages()[0];
            $return['file'] = 'pdf';
        } else {
            $return['src'] = null;
            $return['url'] = null;
            $return['file'] = 'noimg';
        }
        return $return;
    }


    /** @return string  */
    public function getIcon(): string
    {
        if (in_array($this->extension, ['xls', 'xlsx'])) {
            return 'fa-solid fa-file-excel';
        } elseif (in_array($this->extension, ['doc', 'docx'])) {
            return 'fa-solid fa-file-word';
        } elseif (in_array($this->extension, ['jpg', 'jpeg', 'png', 'svg', 'gif', 'tiff', 'bmp'])) {
            return 'fa-solid fa-image';
        } elseif ($this->extension == 'pdf') {
            return 'fa-solid fa-file-pdf';
        } else {
            return 'fa-solid fa-file';
        }
    }

    public function file_upload_max_size(): float
    {
        static $max_size = -1;

        if ($max_size >= 0) {
            // Return cached max size if it's already calculated
            return $max_size;
        }

        // Fetch the maximum sizes from ini settings
        $post_max_size = $this->parseSize(ini_get('post_max_size'));
        $upload_max_filesize = $this->parseSize(ini_get('upload_max_filesize'));

        // Determine the effective max size
        if ($post_max_size == 0) {
            $max_size = $upload_max_filesize;  // post_max_size is unlimited
        } elseif ($upload_max_filesize == 0) {
            $max_size = $post_max_size;  // upload_max_filesize is unlimited
        } else {
            $max_size = min($post_max_size, $upload_max_filesize);  // Neither is unlimited, take the smaller
        }

        return $max_size;
    }
}
