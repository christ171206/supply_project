<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Exception;

class CloudinaryImageService
{
    private $cloudName;
    private $apiKey;
    private $apiSecret;
    private $folder;
    private $baseUrl = 'https://api.cloudinary.com/v1_1';

    public function __construct()
    {
        $this->cloudName = config('services.cloudinary.cloud_name');
        $this->apiKey = config('services.cloudinary.api_key');
        $this->apiSecret = config('services.cloudinary.api_secret');
        $this->folder = config('services.cloudinary.folder', 'supply');

        if (!$this->cloudName || !$this->apiKey || !$this->apiSecret) {
            throw new Exception('Cloudinary credentials not configured');
        }
    }

    /**
     * Télécharger une image vers Cloudinary
     *
     * @param UploadedFile $file
     * @param string $folder (e.g., "products", "vendors")
     * @param string $publicId (e.g., "product_123")
     * @return array
     */
    public function upload(UploadedFile $file, $folder = 'general', $publicId = null)
    {
        try {
            $tempPath = $file->getRealPath();

            if (!$publicId) {
                $publicId = $folder . '/' . Str::slug($file->getClientOriginalName(), '-') . '_' . time();
            }

            $response = Http::attach(
                'file',
                fopen($tempPath, 'r'),
                $file->getClientOriginalName()
            )
                ->post("{$this->baseUrl}/{$this->cloudName}/image/upload", [
                    'api_key' => $this->apiKey,
                    'public_id' => $publicId,
                    'folder' => $this->folder . '/' . $folder,
                    'resource_type' => 'auto',
                    'quality' => 'auto',
                    'fetch_format' => 'auto',
                    // Optimisations
                    'transformation' => [
                        ['quality' => 'auto', 'fetch_format' => 'auto'],
                    ],
                ]);

            if ($response->failed()) {
                return [
                    'success' => false,
                    'error' => $response->json()['error']['message'] ?? 'Upload failed',
                ];
            }

            $data = $response->json();

            return [
                'success' => true,
                'public_id' => $data['public_id'],
                'url' => $data['secure_url'],
                'width' => $data['width'],
                'height' => $data['height'],
                'size' => $data['bytes'],
                'format' => $data['format'],
                'cloudinary_id' => $data['public_id'],
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Supprimer une image de Cloudinary
     *
     * @param string $publicId
     * @return bool
     */
    public function delete($publicId)
    {
        try {
            $timestamp = time();
            $stringToSign = "public_id={$publicId}&timestamp={$timestamp}" . $this->apiSecret;
            $signature = hash('sha1', $stringToSign);

            $response = Http::post("{$this->baseUrl}/{$this->cloudName}/image/destroy", [
                'public_id' => $publicId,
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);

            if ($response->failed()) {
                return false;
            }

            $result = $response->json();
            return $result['result'] === 'ok';
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     * Générer une URL optimisée pour une image
     *
     * @param string $publicId
     * @param array $options (width, height, quality, format, etc.)
     * @return string
     */
    public function getOptimizedUrl($publicId, $options = [])
    {
        $transformations = [];

        if (isset($options['width']) || isset($options['height'])) {
            $transform = [];
            if (isset($options['width'])) {
                $transform['w'] = $options['width'];
            }
            if (isset($options['height'])) {
                $transform['h'] = $options['height'];
            }
            if (isset($options['crop'])) {
                $transform['c'] = $options['crop']; // fill, fit, thumb, etc.
            } else {
                $transform['c'] = 'fill'; // par défaut
            }
            $transformations[] = $transform;
        }

        // Qualité auto + format auto
        $transformations[] = [
            'q' => $options['quality'] ?? 'auto',
            'f' => $options['format'] ?? 'auto',
        ];

        // Construire l'URL
        $transformationString = implode('/', array_map(function ($t) {
            return http_build_query($t, '', ',');
        }, $transformations));

        return "https://res.cloudinary.com/{$this->cloudName}/image/upload/{$transformationString}/{$publicId}";
    }

    /**
     * Récupérer les métadonnées d'une image
     *
     * @param string $publicId
     * @return array
     */
    public function getResourceInfo($publicId)
    {
        try {
            $timestamp = time();
            $stringToSign = "public_id={$publicId}&timestamp={$timestamp}" . $this->apiSecret;
            $signature = hash('sha1', $stringToSign);

            $response = Http::get("{$this->baseUrl}/{$this->cloudName}/resources/image/{$publicId}", [
                'api_key' => $this->apiKey,
                'timestamp' => $timestamp,
                'signature' => $signature,
            ]);

            if ($response->failed()) {
                return null;
            }

            return $response->json();
        } catch (Exception $e) {
            return null;
        }
    }

    /**
     * Générer une URL de transformations multiples pour une galerie
     *
     * @param string $publicId
     * @return array
     */
    public function getGalleryVersions($publicId)
    {
        return [
            'thumbnail' => $this->getOptimizedUrl($publicId, [
                'width' => 220,
                'height' => 220,
                'crop' => 'fill',
            ]),
            'preview' => $this->getOptimizedUrl($publicId, [
                'width' => 600,
                'height' => 600,
                'crop' => 'fill',
            ]),
            'full' => $this->getOptimizedUrl($publicId, [
                'width' => 1200,
                'height' => 1200,
                'crop' => 'fit',
            ]),
            'original' => "https://res.cloudinary.com/{$this->cloudName}/image/upload/{$publicId}",
        ];
    }

    /**
     * Récupérer la clé publique pour les signatures côté client
     *
     * @return string
     */
    public function getCloudName()
    {
        return $this->cloudName;
    }

    /**
     * Générer une signature pour les uploads côté client (Widget Cloudinary)
     *
     * @param array $params
     * @return array
     */
    public function generateSignature($params = [])
    {
        $timestamp = time();

        $paramsToSign = array_merge($params, [
            'timestamp' => $timestamp,
            'api_key' => $this->apiKey,
        ]);

        ksort($paramsToSign);

        $paramsString = '';
        foreach ($paramsToSign as $key => $value) {
            if (is_array($value)) {
                $value = json_encode($value);
            }
            $paramsString .= "{$key}={$value}&";
        }
        $paramsString = rtrim($paramsString, '&');

        $signature = hash('sha256', $paramsString . $this->apiSecret);

        return [
            'signature' => $signature,
            'timestamp' => $timestamp,
            'public_key' => $this->apiKey,
        ];
    }
}
