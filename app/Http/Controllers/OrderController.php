<?php

namespace CakeDeal\Http\Controllers;

use Auth;
use CakeDeal\User;
use CakeDeal\Order;
use CakeDeal\Product;
use Illuminate\Http\Request;
use CakeDeal\Http\Requests;

class OrderController extends Controller
{
    public function index($id)
    {
        $users = User::all()->take(4);

        $order = Product::where('id', $id)->first();

        return view('app.show', compact('order', 'users'));
    }

    public function store(Request $request, $id)
    {
        $order = new Order();
        $order->user_id = Auth::user()->id;
        $order->product_id = Product::find($id)->first()->id;
        $order->quantity = $request->input('quantity');
        $order->message = $request->input('message');
        $order->delivery_date = $request->input('delivery-date');

        $order->save();

        return redirect()->to('/dashboard');
    }

    public function viewOrder($id)
    {
        $users = User::all()->take(4);

        $order = Order::find($id);

        return view('order_details', compact('users', 'order'));
    }

    public function changeOrderStatus(Request $request)
    {
        $id = $request->input('id');
        $newState = $request->input('submit');

        $order = Order::find($id);
        $order->status = $newState;
        $order->save();

        return view('order_details', [
                        'order' => $order, 
                        'message' => 'This order\'s status has now been updated to "'.$newState.'"'
                        ]);
    }

    public function getUserOrders()
    {
        $userId = Auth::user()->id;

        //fetch all orders this user made
        $userOrders = User::find($userId)->orders;

        return view('user_orders', ['userOrders' => $userOrders]);
    }
}
