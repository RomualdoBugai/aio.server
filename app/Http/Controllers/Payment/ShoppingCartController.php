<?php

namespace App\Http\Controllers\Payment;

use Redirect;
use App\Http\Requests;
use Illuminate\Http\Request as Request;

use Gloudemans\Shoppingcart\Facades\Cart as Cart;

use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

use Session;
use Log;

use App\Modules\Payment\Models\Order as Order;
use App\Modules\Payment\Models\OrderItem as OrderItem;
use App\Modules\Payment\Models\PagSeguro as PagSeguro;
use App\Modules\Payment\Models\PagSeguroNotification as PagSeguroNotification;
use App\Modules\Payment\Models\PagSeguroTransaction as PagSeguroTransaction;

class ShoppingCartController extends \App\Http\Controllers\Controller {


    public function index(Request $request)
	{
        return view('app.shopping-cart.index', [
            'title' => 'carrinho de compras',
            'cart'  => (object) [
                'item'      => Cart::content(),
                'subtotal'  => Cart::subtotal(),
                'total'     => Cart::total(),
            ]
		]);
	}

    public function choosePlan(Request $request)
	{
        $input      = ['is_active' => [1]];
        $route      = 'planServiceGet';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $plans      = $client->execute($input, $route, $version);
        $plans      = $plans['data'];

        return view('app.shopping-cart.choose-plan', [
			'plans'  => $plans
		]);
	}

    public function addPlan(Request $request)
    {
        $formName   = __FUNCTION__;
        $post       = $request->input($formName);
        $id         = (int) $post['id'];

        $input      = ['id' => $id];
        $route      = 'planServiceOne';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $plan       = $client->execute($input, $route, $version);

        if ($plan['status'] == true) {

            $plan       = $plan['data'];

            Cart::destroy();

            Cart::add(
                array(
                    'id'      => (string) $plan['product_code'],
                    'name'    => (string) $plan['name'],
                    'qty'     => (int) 1,
                    'price'   => $plan['price'],
                    'total'   => $plan['price'],
                    'taxRate' => 0
                )
            );

            # added successful
            return redirect()->route('shoppingCart');

        } else {
            # plan not found
            return redirect()->route('choosePlan');
        }
    }

    public function onRequest(Request $request)
    {
        $formName   = __FUNCTION__;
        $post       = $request->input($formName);
        $id         = (int) $post['id'];

        $input      = ['id' => $id];
        $route      = 'planServiceOne';
        $version    = '1.0';
        $client     = new \App\Services\Client();
        $plan       = $client->execute($input, $route, $version);

        $plan       = $plan['data'];

        return view('app.shopping-cart.on-request', [
            'plan'  => $plan
		]);

    }

    public function updateItem(Request $request)
    {
        $formName   = __FUNCTION__;
        $post       = $request->input($formName);

        $itemID     = $post['id'];
        $quantity   = $post['quantity'];

        $item       = Cart::get($itemID);

        $total = $item->price * $quantity;

        Cart::update($itemID, ['qty' => $quantity, 'total' => $total]);

        $data = [
            'status'    => true,
            'message'   => message('common', 'updated'),
            'itemTotal' => $total,
            'cartTotal' => Cart::total()
        ];

        return response()->json($data);
    }

}
