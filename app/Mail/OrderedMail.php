<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderedMail extends Mailable
{
    use Queueable, SerializesModels;

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
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.ordered')
        ->subject('商品が注文されました。');;
    }
}
