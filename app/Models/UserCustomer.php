<?php

namespace App\Models;

use App\Enums\PersonType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use App\Traits\Scopes;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCustomer extends Model
{
    use HasFactory, Notifiable, Scopes;

    public $incrementing = false;

    public $primaryKey = 'user_id';

    /**
     * The attributes that should be mass-assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'person_type', 'person_info', 'referral_program'];

    protected $casts = [
        'person_info' => 'json',
    ];

    protected $perPage = 30;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCreateDate(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('Y-m-d');
    }

    public function getCreateDateFormat(): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s',  $this->created_at)->format('d/m/Y');
    }

    public function getPersonInfoJson(): string
    {
        return json_encode($this->person_info, JSON_PRETTY_PRINT);
    }

    public function getPersonInfo(): UserCustomerPersonInfo
    {
        $personInfo =  UserCustomerPersonInfo::createFromJson(($this->person_info));
        return $personInfo;
    }

    public function isNatural(): string
    {
        return $this->person_type == PersonType::NATURAL->value;
    }

    public function isJuristic(): string
    {
        return $this->person_type == PersonType::JURISTIC->value;
    }

    public function coupons(): HasMany
    {
        return $this->hasMany(UserCoupon::class, 'user_id');
    }

    public function missions(): HasMany
    {
        return $this->hasMany(UserMission::class, 'user_id');
    }

    public function issuePoints(): HasMany
    {
        return $this->hasMany(IssuePoint::class, 'user_id');
    }

    public function getLatestCoupons()
    {
        return $this->coupons()->take(10)->orderBy('id', 'desc')->get();
    }

    public function getLatestMissions()
    {
        return $this->missions()->take(10)->orderBy('id', 'desc')->get();
    }

    public function getLatestIssuePoints()
    {
        return $this->issuePoints()->take(10)->orderBy('id', 'desc')->get();
    }

    public function getLatestPointLogs()
    {
        $userId = $this->user_id;
        $query = UserPointLog::whereHas('userPoint', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
        return $query->take(10)->orderBy('id', 'desc')->get();
    }

    public function getLatestCouponLogs()
    {
        $userId = $this->user_id;
        $query = UserCouponLog::whereHas('userCoupon', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
        return $query->take(10)->orderBy('id', 'desc')->get();
    }
}


class UserCustomerPersonInfo
{
    public string $firstName;
    public string $lastName;
    public string $juristicName;
    public string $gender;
    public string $identificationNo;
    public string $juristicId;
    public string $birthDate;
    public string $registrationDate;
    public string $mobilePhone;
    public string $contactNumber;

    public static function createFromJson($object)
    {
        $personInfo = new UserCustomerPersonInfo();
        $personInfo->firstName = $object['first_name'] ?? '';
        $personInfo->lastName = $object['last_name'] ?? '';
        $personInfo->juristicName = $object['juristic_name'] ?? '';
        $personInfo->gender = $object['gender'] ?? '';
        $personInfo->identificationNo = $object['identification_no'] ?? '';
        $personInfo->juristicId = $object['juristic_id'] ?? '';
        $personInfo->birthDate = $object['birth_date'] ?? '';
        $personInfo->registrationDate = $object['registration_date'] ?? '';
        $personInfo->mobilePhone = $object['mobile_phone'] ?? '';
        $personInfo->contactNumber = $object['contact_number'] ?? '';
        return $personInfo;
    }

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getGenderFormat(): string
    {
        return __('common.' . $this->gender);
    }

    public function getAge(): string
    {
        if (!$this->birthDate) return '-';
        return Carbon::createFromFormat("Y-m-d", $this->birthDate)->age;
    }
}
