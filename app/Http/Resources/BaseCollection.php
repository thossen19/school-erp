<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BaseCollection extends ResourceCollection
{
    public string $collects;

    protected array $metaData = [];

    public function __construct($resource, string $collects = null)
    {
        parent::__construct($resource);
        $this->collects = $collects ?? BaseResource::class;
    }

    public function withMeta(array $meta): static
    {
        $this->metaData = array_merge($this->metaData, $meta);

        return $this;
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection->map(function ($resource) use ($request) {
                return $this->collects::make($resource);
            }),
        ];
    }

    public function with(Request $request): array
    {
        $response = [
            'success' => true,
        ];

        if ($this->resource instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $response['meta'] = array_merge([
                'current_page' => $this->resource->currentPage(),
                'last_page' => $this->resource->lastPage(),
                'per_page' => $this->resource->perPage(),
                'total' => $this->resource->total(),
                'from' => $this->resource->firstItem(),
                'to' => $this->resource->lastItem(),
            ], $this->metaData);
        } elseif (!empty($this->metaData)) {
            $response['meta'] = $this->metaData;
        }

        return $response;
    }

    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [
            'meta' => array_merge($default['meta'] ?? [], $this->metaData),
        ];
    }
}
