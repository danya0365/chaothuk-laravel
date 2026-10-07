<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @extends ResourceCollection<RecruitBookingResource>
 */
class RecruitBookingCollection extends ResourceCollection
{
    /**
     * The item type, so clients can rely on the collection shape instead of guessing.
     *
     * @var class-string<RecruitBookingResource>
     */
    public $collects = RecruitBookingResource::class;
}
