<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\ExportResource;
use App\Http\Resources\RawFirmResource;

class ExportRequested implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $export, $rawFirm;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($export, $rawFirm)
    {
        $this->export = $export;
        $this->rawFirm = $rawFirm;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('export');
    }
    public function broadcastWith(){
        return [
            'export'=>new ExportResource($this->export),
            'rawFirm'=> new RawFirmResource($this->rawFirm),
        ];
    }
}
