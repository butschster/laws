<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Court
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $name
 * @property string $type
 * @property string|null $url
 * @property string|null $phone
 * @property array $email
 * @property int $region_id
 * @property string $address
 * @property string $code
 * @property string $lon
 * @property string $lat
 * @property string|null $synced_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CourtJurisdiction[] $jurisdictions
 * @property-read \App\Region $region
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court expired($days = 7)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereLat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereLon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereRegionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereSyncedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Court whereUrl($value)
 * @mixin \Eloquent
 */
	class Court extends \Eloquent {}
}

namespace App{
/**
 * App\CourtJurisdiction
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int $court_id
 * @property string $city
 * @property string $address
 * @property-read \App\Court $court
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourtJurisdiction whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourtJurisdiction whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourtJurisdiction whereCourtId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourtJurisdiction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourtJurisdiction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CourtJurisdiction whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class CourtJurisdiction extends \Eloquent {}
}

namespace App{
/**
 * App\FederalDistrict
 *
 * @property int $id
 * @property string $name
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FederalDistrict whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FederalDistrict whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FederalDistrict whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FederalDistrict whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class FederalDistrict extends \Eloquent {}
}

namespace App{
/**
 * App\RefinancingRate
 *
 * @property int $id
 * @property float $rate
 * @property \Carbon\Carbon $created_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RefinancingRate whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RefinancingRate whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RefinancingRate whereRate($value)
 * @mixin \Eloquent
 */
	class RefinancingRate extends \Eloquent {}
}

namespace App{
/**
 * App\Region
 *
 * @property int $id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property string $name
 * @property int $federal_district_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Court[] $courts
 * @property-read \App\FederalDistrict $federalDistrict
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereFederalDistrictId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Region whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Region extends \Eloquent {}
}

namespace App{
/**
 * App\User
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

