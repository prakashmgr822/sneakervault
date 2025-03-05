<?php

namespace App\Jobs;

use App\Mail\OrderConfirmationEmail;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderConfirmationEmail implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $userEmail;

    /**
     * Create a new job instance.
     * @param Order $order
     * @param $userEmail
     */
    public function __construct(Order $order, $userEmail)
    {
        $this->order = $order;
        $this->userEmail = $userEmail;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        Mail::to($this->userEmail)->send(new OrderConfirmationEmail($this->order));
    }
}
