<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    protected bool $withPermissions = false;

    protected array $additionalData = [];

    public static function collection($resource): BaseCollection
    {
        return tap(new BaseCollection($resource, static::class), function ($collection) {
            if (property_exists(static::class, 'collects')) {
                $collection->collects = static::$collects;
            }
        });
    }

    public function withPermissions(bool $value = true): static
    {
        $this->withPermissions = $value;

        return $this;
    }

    public function withAdditional(array $data): static
    {
        $this->additionalData = array_merge($this->additionalData, $data);

        return $this;
    }

    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);

        if (!empty($this->additionalData)) {
            $data = array_merge($data, $this->additionalData);
        }

        return $data;
    }

    public function with(Request $request): array
    {
        $response = [
            'success' => true,
        ];

        if ($this->withPermissions && $request->user()) {
            $response['permissions'] = $request->user()->getAllPermissions()->pluck('name');
        }

        return $response;
    }
}
