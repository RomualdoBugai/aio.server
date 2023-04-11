<?php

namespace App\Http\Controllers\Payment;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Gloudemans\Shoppingcart\Facades\Cart as Cart;
use GuzzleHttp\Client;
use GuzzleHttp\Stream\Stream;

use Session;
use Redirect;
use Log;

use Caffeinated\Flash\Facades\Flash as Flash;
use Illuminate\Support\Facades\Config as Config;

class CheckOutController extends \App\Http\Controllers\Controller
{

    private $order                  = Order::class;
    private $orderItem              = OrderItem::class;
    private $pagSeguro              = PagSeguro::class;
    private $pagSeguroNotification  = PagSeguroNotification::class;
    private $pagSeguroTransaction   = PagSeguroTransaction::class;

    private $sender = array();

    private $config = array();


    public function pagSeguroTransaction()
    {
        $pagSeguroTransaction = new $this->pagSeguroTransaction;
        dd($pagSeguroTransaction::all());
    }

    public function retorno($orderCode = null, Request $request)
    {
        \Debugbar::disable();
        $code = $request->input("notificationCode");
        if ($orderCode != null)
        {

            $order = new $this->order;
            $order = $order->where('code', $orderCode)->first();

            $pagSeguroNotification = new $this->pagSeguroNotification;
            $check = $pagSeguroNotification::where('code', $code)->first();

                $pagSeguroNotification->order_id = $order->id;
                $pagSeguroNotification->code = $code;
                $pagSeguroNotification->save();

                $id = $pagSeguroNotification->id;

                $this->config = config('pagseguro');
                $param = $this->config['credentials'];
                $url  = $this->config['host'] . $this->config['url']['transactions-notifications'];
                $url .= $pagSeguroNotification->code . "?email=" . $param['email'] . "&token=" . $param['token'];
                $client = new Client();
                $res = $client->request('GET', $url);
                $xml = simplexml_load_string( $res->getBody()->getContents() );
                $xml_array = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));
                $status = $xml_array['status'];
                $name = "em aberto";
                switch ($status)
                {
                    case '1': $name = 'Em aberto'; break;
                    case '2': $name = 'Em análise'; break;
                    case '3': $name = 'Paga'; break;
                    case '4': $name = 'Disponível'; break;
                    case '5': $name = 'Em disputa'; break;
                    case '6': $name = 'Devolvida'; break;
                    case '7': $name = 'Cancelada'; break;
                    case '8': $name = 'Debitado'; break;
                    case '9': $name = 'Retenção temporária'; break;
                }
                $pagSeguroTransaction = new $this->pagSeguroTransaction;
                $pagSeguroTransaction->code = $pagSeguroNotification->code;
                $pagSeguroTransaction->status = $status;
                $pagSeguroTransaction->name = $name;
                $pagSeguroTransaction->order_id = $order->id;
                $pagSeguroTransaction->save();

                $order->update(['status' => $status]);

        }
    }

    public function __construct()
    {
        $this->config = config('pagseguro');
        $this->sender = array(
            'sender' => array(
                'email' => $this->config['credentials']['email'],
                'name' => 'Marco Aurelio Possiede',
                'documents' => array(
                    'number' => '40404040411',
                    'type'   => 'CPF'
                ),
                'phone'    => '4130233255',
                'bornDate' => '1988-03-21',
            ),
        );
    }

    public function index(Request $request)
    {



        $param = $this->config['credentials'];
        $url   = $this->config['host'] . $this->config['url']['sessions'];

        $client = new Client(
            array(
                'curl' => array(
                    CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_0,
                    CURLOPT_SSL_VERIFYPEER => false,
                    CURLOPT_FOLLOWLOCATION => true
                )
            )
        );

        $res = $client->request(
            'POST',
            $url,
            array(
                'form_params' => $param
            )
        );

        $xml        = simplexml_load_string( $res->getBody()->getContents() );
        $xml_array  = unserialize(serialize(json_decode(json_encode((array) $xml), 1)));
        $sessionID  = $xml_array['id'];

        $client     = new \App\Services\Client();
        $result     = $client->execute(['user_id' => userId()], 'userAddressServiceGet', '1.0');
        $address    = ( $result['status'] == true ? $result['data'] : [] );

        $data = array(
            'session'   => [
                'id'    => $sessionID
            ],
            'user'      => (object) [
                'addresses'  => (object) [
                    'status' => (bool) ( is_array($address) && count($address) > 0 ? true : false),
                    'data'   => $address
                ]
            ],
            'cart'  => (object) [
                'item'      => Cart::content(),
                'subtotal'  => Cart::subtotal(),
                'total'     => Cart::total(),
            ]
        );

        return view('app.shopping-cart.checkout', $data);
    }

    public function checkOut(Request $request)
    {
        $formName = __FUNCTION__;
        $post = $request->input($formName);

        # yy mm dd userid rand
        $order_code = date("ymd") . session('user.id') . rand(0, 100);

        function array_to_xml( $data, &$xml_data )
        {
            foreach( $data as $key => $value )
            {
                if( is_array($value) )
                {
                    if( is_numeric($key) )
                    {
                        $key = 'item'; //dealing with <0/>..<n/> issues
                    }
                    $subnode = $xml_data->addChild($key);
                    array_to_xml($value, $subnode);
                } else {
                    $xml_data->addChild("$key",htmlspecialchars("$value"));
                }
            }
        }

        if (is_array($post['item']) & count($post['item']) > 0)
        {
            $itms = $post['item'];
        }

        /*
        $address = new UserAddress;
        $userAddress = $address->find($post['address']);
        */

        $amount = 0;
        $quantity = 0;

        $data['paymentMode']    = 'default';
        $data['extraAmount']    = '0.00';
        $data['paymentMethod']  = 'creditCard';
        $data['receiverEmail']  = 'possiede@mpainformatica.com'; # teste
        $data['currency']       = 'BRL';

        $i = 1;
        foreach($post['item'] as $v)
        {
            $data['itemId' . $i]        = $v['id'];
            $data['itemDescription' . $i] = $v['description'];
            $data['itemQuantity' . $i]  = $v['quantity'];
            $data['itemAmount' . $i]    = "{$v['amount']}0";
            $i++;
            $quantity += $v['quantity'];
            $amount += ($v['amount'] * $v['quantity']);
        }

        $data['notificationURL'] = 'http://a2128421.ngrok.io/aio/public/index.php/payment/check-out/retorno/' . $order_code;
        $data['reference']       = session('user.id');
        $data['senderCPF']       = '08518970962';
        $data['senderName']      = "Comprador de Teste";
        $data['senderEmail']     = 'c60884294954836046857@sandbox.pagseguro.com.br'; # teste
        $data['senderAreaCode']  = 41;
        $data['senderPhone']     = 95405366;
        $data['senderHash']      = $post['senderHash'];

        # endereço de entrega
        $data['shippingAddressStreet'] = null; #$userAddress->street;
        $data['shippingAddressNumber'] = null; #$userAddress->number;
        $data['shippingAddressComplement'] = null; #$userAddress->complement;
        $data['shippingAddressDistrict'] = null; #$userAddress->district;
        $data['shippingAddressPostalCode'] = null; #$userAddress->postal_code;
        $data['shippingAddressCity']   = null; #$userAddress->city;
        $data['shippingAddressState']  = null; #strtoupper($userAddress->state);
        $data['shippingAddressCountry'] = null; #strtoupper($userAddress->country);

        $data['creditCardToken']       = (string) $post['creditCardToken'];
        $data['installmentQuantity']   = (int) $post['portionNumber'];
        $data['installmentValue']      = "{$amount}0";
        $data['noInterestInstallmentQuantity'] = 5;
        $data['creditCardHolderName']  = (string) $post['creditCardName'];
        $data['creditCardHolderCPF']   = '08518970962';
        $data['creditCardHolderBirthDate'] = '09/02/1992';
        $data['creditCardHolderAreaCode'] = 41;
        $data['creditCardHolderPhone'] = 95405366;

        # endereço de cobrança
        $data['billingAddressStreet'] = null; #$userAddress->street;
        $data['billingAddressNumber'] = null; #$userAddress->number;
        $data['billingAddressComplement'] = null; #$userAddress->complement;
        $data['billingAddressDistrict'] = null; #$userAddress->district;
        $data['billingAddressPostalCode'] = null; #$userAddress->postal_code;
        $data['billingAddressCity']   = null; #$userAddress->city;
        $data['billingAddressState']  = null; #strtoupper($userAddress->state);
        $data['billingAddressCountry'] = null; #strtoupper($userAddress->country);

        $order           = new $this->order;
        $order->user_id  = session('user.id');
        $order->code     = $order_code;
        $order->amount   = $amount;
        $order->quantity = $quantity;
        $order->json     = json_encode($data);
        $order->status   = 1;
        $order->payment  = 'creditCard';
        $order->save();

        foreach($post['item'] as $v)
        {
            $orderItem = new $this->orderItem;
            $orderItem->product_code = 1;
            $orderItem->quantity = $v['quantity'];
            $orderItem->amount = $v['amount'];
            $orderItem->description = $v['description'];
            $orderItem->order_id = $order->id;
            $orderItem->save();
            unset($orderItem);
        }

        $param = $this->config['credentials'];
        $url   = $this->config['host'] . $this->config['url']['transactions'];

        $client = new Client(
            [
                'curl' => [
                    CURLOPT_SSL_VERIFYPEER => false
                ]
            ]
        );

        $resquest = $client->post(
            $url,
            [
                'form_params' => array_merge($param, $data)
            ]
        );

        $xmlstring = $resquest->getBody()->getContents();
        $xml   = simplexml_load_string($xmlstring, "SimpleXMLElement", LIBXML_NOCDATA);
        $json  = json_encode($xml);
        $array = json_decode($json,TRUE);

        $pagSeguro               = new $this->pagSeguro;
        $pagSeguro->code         = $array['code'];
        $pagSeguro->fee_amount   = $array['feeAmount'];
        $pagSeguro->net_amount   = $array['netAmount'];
        $pagSeguro->extra_amount = $array['extraAmount'];
        $pagSeguro->order_id     = $order->id;
        $pagSeguro->json         = $json;
        $pagSeguro->save();

        echo "<pre>";
        print_r($array);
        echo "</pre>";

    }

}
