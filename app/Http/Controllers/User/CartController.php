<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
// Cartモデルの追加
use App\Models\Cart;
// Userモデルの追加
use App\Models\User;
// Stockモデルの追加
use App\Models\Stock;
// 認証モデルの追加
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // indexPage用メソッド
    public function index()
    {
        // userの取得
        $user = User::findOrFail(Auth::id());
        // user⇔productとの多対多リレーション
        $products = $user->products;
        // 総額表示
        $totalPrice = 0;

        // 製品を1つずつ取得
        foreach($products as $product){
            // 製品の価格 * 中間テーブルの数量の数
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        // dd($products, $totalPrice);

        return \view('user.cart',
        compact('products', 'totalPrice'));
    }


    // 商品追加のためのメソッド
    public function add(Request $request)
    {
        // Cartに商品があるかどうか
        $itemInCart = Cart::where('user_id',Auth::id())
        ->where('product_id',$request->product_id)->first();

        if($itemInCart){
            // cart内に商品がある場合、数量追加
            $itemInCart->quantity += $request->quantity;
            $itemInCart->save();

        }else{
            // cart内に商品がない場合、新規追加
            Cart::Create([
                'user_id'=> Auth::id(),
                'product_id'=>$request->product_id,
                'quantity'=>$request->quantity
            ]);
        }

        // dd('Test');
        // user.indexにリダイレクト
        return redirect()->route('user.cart.index');
    }

    // Cartの削除
    public function delete($id)
    {
        // Cart内の選択したproduct_idを削除
        Cart::where('product_id',$id)
        ->where('user_id',Auth::id())
        ->delete();

        // cart/indexにリダイレクト
        return redirect()->route('user.cart.index');
    }

    // Cart決済処理
    public function checkout()
    {
        // Userの取得
        $user = User::findOrFail(Auth::id());
        // 製品の取得
        $products = $user->products;

        // 製品リスト
        $lineItems = [];

        foreach($products as $product)
        {
            /* 決済前に在庫確認 */
            $quantity="";
            $quantity =Stock::where('product_id', $product->id)
            ->sum('quantity');

            // Cart内の数量が在庫数より多いかどうか
            if($product->pivot->quantity > $quantity){
                // 多い場合:indexにリダイレクト処理
                return redirect()->route('user.cart.index');
            }else {
                // そうでなければ決済処理
                // StripeAPIドキュメント(Create)
                $lineItem = [
                    'name' => $product->name,
                    'description' => $product->information,
                    'amount' => $product->price,
                    'currency' => 'jpy',
                    'quantity' => $product->pivot->quantity,
                ];
                array_push($lineItems, $lineItem);
            }
        }

        // 在庫数量を減らす
        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        // dd("TEST");

        /*<Stripeへ渡すSession情報>
        https://stripe.com/docs/checkout/integration-builder*/

        // Stripe_SECRET_KEYの読み込み
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Sessionの生成(渡す情報、mode,checkout成功時・キャンセル時のredirect情報)
        $checkout_session = \Stripe\Checkout\Session::create([
            // 決済情報をカードに指定
            'payment_method_types' => ['card'],
            // 商品情報
            'line_items' => [$lineItems],
            // mode
            'mode' => 'payment',
            // checkout成功時のリダイレクト
            'success_url' => route('user.cart.success'),
            // checkoutキャンセル時のリダイレクト
            'cancel_url' => route('user.cart.cancel'),
            ]);

            /* Stripeマニュアル変更(1)
            Stripe_PUBLIC_KEYの読み込み不要 */
            // $publicKey = env('STRIPE_PUBLIC_KEY');

            /* Stripeマニュアル変更(2)
            Stripeの決済画面へ直接リダイレクトするので不要 */
            // return view('user.checkout',compact('checkout_session', 'publicKey'));

            /* Stripeマニュアル変更(3)
            Stripeの決済画面に直接リダイレクトさせるコードを追加 */
            return redirect($checkout_session ->url, 303);
    }

    // Stripe成功時の処理
    public function success()
    {
        // カートを0にする
        Cart::where('user_id', Auth::id())->delete();
        // user/itmes/indexにredirectする
        return redirect()->route('user.items.index');
    }

    // Stripe決済失敗時の処理
    public function cancel()
    {
        // UserIDを取得
        $user = User::findOrFail(Auth::id());

        // 在庫を元に戻す
        foreach($user->products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['add'],
                'quantity' => $product->pivot->quantity
            ]);
        }
        // cart.blade.phpにリダイレクト
        return redirect()->route('user.cart.index');
    }
}
