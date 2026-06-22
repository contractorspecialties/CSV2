<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class SubcontractorProfile extends Model
{
    /**
     * Force model instance table name discovery patterns.
     */
    protected $table = 'subcontractor_profiles';

    /**
     * The environmental system boot lifecycle hook node.
     */
    protected static function booted(): void
    {
        /**
         * SECURE PUBLIC-FACING OFFLINE VISIBILITY SHIELD
         * Automatically blocks unauthenticated public listing requests from intercepting profile data
         * unless the is_public state parameter has been intentionally unlocked.
         */
        static::addGlobalScope('public_ready_filter', function (Builder $builder) {
            if (!auth()->check()) {
                $builder->where('is_public', true);
            }
        });
    }

    /**
     * Programmatic LocalBusiness JSON-LD Schema Generator Engine.
     * Automatically compiles compliant semantic code injection metadata arrays for SERP injection hooks.
     */
    public function compileLocalSeoSchema(): string
    {
        $payload = [
            '@context' => 'https://schema.org',
            '@type'    => 'LocalBusiness',
            'name'     => $this->business_name,
            'image'    => asset('/images/directory-placeholder.webp'),
            'address'  => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $this->street_address ?? 'Available upon verification',
                'addressLocality' => $this->city,
                'addressRegion'   => $this->state,
                'postalCode'      => $this->zip_code,
                'addressCountry'  => 'US'
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => $this->latitude ?? 0.00,
                'longitude' => $this->longitude ?? 0.00
            ],
            'knowsAbout' => [$this->primary_specialty, 'Commercial Construction', 'Subcontracting Services']
        ];

        return json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }
}
