<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Message extends JsonResource
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
            'date' => $this->date,
            'chat_id' => $this->chat_id,
            'seen' => $this->seen,
            'content' => $this->content,
            'user1' =>"/api/user/".$this->id."/users",
            'user2' =>"/api/user/".$this->id."/users",
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
