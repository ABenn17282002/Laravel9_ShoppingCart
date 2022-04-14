<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// Mailクラスの使用
use Illuminate\Support\Facades\Mail;
// OrderedMailクラスの使用
use App\Mail\OrderedMail;

class SendOrderedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // インスタンスの生成
    public $product;
    public $user;

    // 初期化
    public function __construct($product,$user)
    {
        $this->product = $product;
        $this ->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Owner側にメール送信
        Mail::to($this->product['email'])
        ->send(new OrderedMail($this->product, $this->user));
    }
}
