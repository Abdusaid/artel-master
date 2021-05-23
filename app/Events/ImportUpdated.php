<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use App\Http\Resources\ImportResource;
use App\Http\Resources\RawFirmResource;

class ImportUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public  $import, $rawFirm;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($import, $rawFirm)
    {
        $this->import = $import;
        $this->rawFirm = $rawFirm;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel('import');
    }
    public function broadcastWith(){
        return [
            'import' => new ImportResource($this->import),
            'rawFirm'=> new RawFirmResource($this->rawFirm)
        ];
    }
}
