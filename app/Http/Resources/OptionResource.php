<?php

namespace App\Http\Resources;
use App\Enums\UserRole;

use Illuminate\Http\Resources\Json\JsonResource;

class OptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
                //         $this->mergeWhen(
                // auth()->check() && auth()->user()->role  === UserRole::Admin,
                // [
                    'is_correct' => $this->is_correct,
        //         ]
        //     ),
        ];
    }
}