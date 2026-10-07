<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @extends ResourceCollection<RecruitResource>
 */
class RecruitCollection extends ResourceCollection
{
    /**
     * The item type, so clients can rely on the collection shape instead of guessing.
     *
     * @var class-string<RecruitResource>
     */
    public $collects = RecruitResource::class;
}
