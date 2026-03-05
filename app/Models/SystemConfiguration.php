<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SystemConfiguration extends Model
{
    protected $fillable = ['key', 'value', 'type', 'description'];

    public $timestamps = true;

    /**
     * Récupérer une configuration par clé
     */
    public static function get(string $key, $default = null)
    {
        $config = self::where('key', $key)->first();
        return $config ? self::castValue($config->value, $config->type) : $default;
    }

    /**
     * Définir une configuration
     */
    public static function set(string $key, $value, string $type = 'string', ?string $description = null): void
    {
        self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : (string)$value,
                'type' => $type,
                'description' => $description,
            ]
        );
    }

    /**
     * Convertir la valeur selon son type
     */
    private static function castValue($value, string $type)
    {
        return match ($type) {
            'number' => (float)$value,
            'boolean' => (bool)$value,
            'json' => json_decode($value, true),
            default => $value,
        };
    }
}
