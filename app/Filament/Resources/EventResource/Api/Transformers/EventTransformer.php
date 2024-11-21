<?php
namespace App\Filament\Resources\EventResource\Api\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;

class EventTransformer extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'slug' => $this->slug,
            'title' => $this->title,
            'description' => $this->description,
            'lang' => $this->lang,
            'created_at' => $this->created_at,
            'thumbnail' => $this->media->pluck('original_url')->first(),
        ];
    }
}
