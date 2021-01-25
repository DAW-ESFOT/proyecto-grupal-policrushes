<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'gender' => $this->gender,
            'age' => $this->age,
            'description' => $this->description,
            'location' => $this->location,
            'address' => $this->address,
            'min_age' => $this->min_age,
            'max_age' => $this->max_age,
            'movie_gender' =>"/api/movie-genders/".$this->id."/movie-genders",
            'music_gender' =>"/api/music-genders/".$this->id."/music-genders",
            'preferred_gender' => $this->preferred_gender,
            'preferred_pet' => $this->preferred_pet,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
