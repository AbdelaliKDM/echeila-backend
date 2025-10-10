<?php

namespace App\Http\Resources;

use App\Models\Notification;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Notification */
class NotificationResource extends JsonResource
{
  public function toArray($request): array
  {
    return [
      'id' => $this->id,
      'type' => class_basename($this->type),
      'title' => $this->title,
      'body' => $this->body,
      'data' => $this->data['data'] ?? [],
      'read' => !is_null($this->read_at),
      'created_at' => $this->created_at->toDateTimeString(),
    ];
  }
}
