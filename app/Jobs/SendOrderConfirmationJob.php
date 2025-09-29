<?php
namespace App\Jobs;

use App\Models\Order; 
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendOrderConfirmationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order; 

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function handle()
    {
        Log::info("✅ Mock confirmation: Order #{$this->order->id} for Shop {$this->order->shop_id} placed by User {$this->order->user_id}");
    }
}
