<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
// メール送信用クラスの使用
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Mail\ThanksMail;


class SendThanksMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    // インスタンスの生成
    public $products;
    public $user;

    // 初期化
    public function __construct($products,$user)
    {
        $this->products = $products;
        $this->user = $user;
    }

    /**
    * Execute the job.
    *
    * @return void
    */
    public function handle()
    {
        // Mail::to('test@example.com') // 受信者の指定
        // ->send(new TestMail());      // Mailableクラス

        // user宛に送信
        Mail::to($this->user)
        ->send(new ThanksMail($this->products, $this->user));
    }
}
