<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Product as ProductModel;
use Darryldecode\Cart\CartCondition;
use Cart as ShoppingCart;
use Carbon\Carbon;

use Livewire\WithPagination;

class Cart extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $tax = '0%';

    public $search = '';

    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {        
        $products = ProductModel::where('name', 'like', '%'.$this->search.'%')->orderBy('created_at', 'DESC')->paginate(8);


        $condition = new CartCondition([
            'name' => 'pajak',
            'type' => 'tax',
            'target' => 'total',
            'value' => $this->tax,
            'order' => 1
        ]);

        ShoppingCart::session(Auth()->id())->condition($condition);
        $items = ShoppingCart::session(Auth()->id())->getContent()->sortBy(function($cart){
            return $cart->attributes->get('added_at');
        });
        // dd($items);

        if(ShoppingCart::isEmpty()){
            $carts = [];

        }else{
            foreach($items as $item){
                $cart[] = [
                    'rowId' => $item->id,
                    'name' => $item->name,
                    'qty' => $item->quantity,
                    'priceSingle' => $item->price,
                    'priceTotal' => $item->getPriceSum(),
                ];
            }

            $carts = collect($cart);
        }

        $subTotal = ShoppingCart::session(Auth()->id())->getSubTotal();
        $totalPrice = ShoppingCart::session(Auth()->id())->getTotal();

        $newCondition = ShoppingCart::session(Auth()->id())->getCondition('pajak');
        $pajak = $newCondition->getCalculatedValue($subTotal);

        $summary = [
            'sub_total' => $subTotal,
            'pajak' => $pajak,
            'total' => $totalPrice
        ];

        return view('livewire.cart', compact('products', 'carts', 'summary'));
    }

    public function addItem($id){
        $rowId = "Cart".$id;
        $cart = ShoppingCart::session(Auth()->id())->getContent();
        $checkItemId = $cart->whereIn('id', $rowId);

        if($checkItemId->isNotEmpty()){
            ShoppingCart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => 1
                ]
            ]);
        }else{
            $product = ProductModel::findOrFail($id);
            ShoppingCart::session(Auth()->id())->add([
                'id' => 'Cart'.$product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => 1,
                'attributes' => [
                    'added_at' => Carbon::now()
                ]
            ]);
        }
    }

    public function enableTax(){
        $this->tax = '+10%';
    }

    public function disableTax() {
        $this->tax = '0%';
    }

    public function increaseItem($rowId) {
        $productId = substr($rowId, 4,5);
        $product = ProductModel::findOrFail($productId);
        
        $cart = ShoppingCart::session(Auth()->id())->getContent();
        $checkItem = $cart->whereIn('id',$rowId);

        if($product->qty == $checkItem[$rowId]->quantity){
            session()->flash('error', 'Insufficient stock');
        }else{
            ShoppingCart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => 1
                ]
            ]);
        }
    }

    public function decreaseItem($rowId) {
        $cart = ShoppingCart::session(Auth()->id())->getContent();
        $checkItem = $cart->whereIn('id',$rowId);
        if($checkItem[$rowId]->quantity == 1){
            $this->removeItem($rowId);
        }else{
            ShoppingCart::session(Auth()->id())->update($rowId, [
                'quantity' => [
                    'relative' => true,
                    'value' => -1
                ]
            ]);
        }
    }

    public function removeItem($rowId) {
        ShoppingCart::session(Auth()->id())->remove($rowId);
    }
}
