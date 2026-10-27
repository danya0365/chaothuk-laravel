<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * @extends ResourceCollection<PortfolioResource>
 */
class PortfolioCollection extends ResourceCollection
{
    /**
     * The item type, so clients can rely on the collection shape instead of guessing.
     *
     * @var class-string<PortfolioResource>
     */
    public $collects = PortfolioResource::class;
}
