<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Download extends Model {
    use SoftDeletes;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<string>|bool
     */
    protected $guarded = [];
    /**
     * The data type of the primary key ID.
     *
     * @var string
     */
    protected $keyType = 'string';
    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;
    // URL safe characters used by the ID generation algorithm.
    protected static string $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz9876543210';

    // Relations

    public function state(): BelongsTo {
        return $this->belongsTo(State::class);
    }

    // Methods

    protected static function booted(): void {
        // called right before a model is created in the database
        // handles the generation of IDs for new records
        static::creating(function (Download $download) {
            // Generate a 64-bit timestamp. A simple and effective way is to
            // use a microsecond timestamp, which gives you high precision.
            $timestamp = Carbon::now()->getTimestampMs();
            // Generate a small, random integer for uniqueness in case of
            // simultaneous requests.
            $random = random_int(0, 99);
            // Combine the timestamp and random integer into a large number.
            // This is a simplified version of a Snowflake-like ID.
            $id = $timestamp * 100 + $random;
            // Convert the large number to a URL-safe string using Base62.
            // Base62 encoding is an excellent choice for this, as it's more
            // compact than hexadecimal and uses standard alphanumeric characters.
            // We use a custom function for this, as it's not built-in to PHP.
            $base62 = self::toBase62($id);
            // Set the model's ID.
            $download->id = $base62;
        });
    }

    // A simple Base62 conversion function.
    protected static function toBase62(int $num): string {
        // $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        // $base = strlen($characters);
        $result = '';
        while ($num > 0) {
            $result = self::$characters[$num % 62] . $result;
            $num = (int)($num / 62);
        }
        return $result ?: '0';
    }
}
