<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h2 class="font-weight-bold">Product List</h2>
                    <div class="">
                        <input wire:model="search" type="text" placeholder="Search Product..." class="form-control">
                    </div>
                </div>
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <img width="100%" src="{{ asset('storage/images/' . $product->image) }}" alt="Image"
                                    style="object-fit: contain; width: 100%; height: 170px">
                                <button wire:click="addItem({{ $product->id }})"
                                    class="btn btn-primary btn-sm position-absolute"
                                    style="top: 0; right: 0;padding : 10px 15px">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                                <div class="my-2">
                                    <h6 class="text-center font-weight-bold">{{ $product->name }}</h6>
                                    <h6 class="text-center">Rp. {{ number_format($product->price, 0, '', '.') }}</h6>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h2 class="font-weight-bold">Cart</h2>
                @if (session()->has('error'))
                    <small class="text-danger alert-stock">{{ session()->get('error') }}</small>
                @endif
                <table class="table table-bordered table-hovered table-striped">
                    <thead class="grey lighten-2">
                        <tr>
                            <th width="5%">No.</th>
                            <th>Nama</th>
                            <th>QTY</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carts as $index => $cart)
                            <tr>
                                <td>
                                    {{ $index + 1 }}
                                    <span wire:click="removeItem('{{ $cart['rowId'] }}')"> 
                                        <i class="fas fa-trash"></i>
                                    </span>
                                </td>
                                <td>
                                    {{ $cart['name'] }}
                                    <br>
                                    {{ number_format($cart['priceSingle'],0,'','.') }}
                                </td>
                                <td>
                                    <button class="btn btn-primary btn-sm" style="padding : 7px 10px" wire:click="increaseItem('{{ $cart['rowId'] }}')">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                    &nbsp;
                                    {{ $cart['qty'] }}
                                    &nbsp;
                                    <button class="btn btn-info btn-sm" style="padding : 7px 10px" wire:click="decreaseItem('{{ $cart['rowId'] }}')">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </td>
                                <td class="text-right">{{ number_format($cart['priceSingle'], 0, '', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <h6 class="text-center">Empty Cart</h6>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <h6 class="font-weight-bold">Cart Summary</h6>
                <table>
                    <tr>
                        <td>Subtotal</td>
                        <td>:</td>
                        <td>Rp. {{ number_format($summary['sub_total'], 0, '', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td>:</td>
                        <td>Rp. {{ number_format($summary['pajak'], 0, '', '.') }}</td>
                    </tr>
                    <tr>
                        <td>Subtotal</td>
                        <td>:</td>
                        <td>Rp. {{ number_format($summary['total'], 0, '', '.') }}</td>
                    </tr>
                </table>
                <div class="mt-2 row">
                    <div class="col-sm-6">
                        <button wire:click="enableTax" class="btn btn-info btn-block">Add Tax</button>
                    </div>
                    <div class="col-sm-6">
                        <button wire:click="disableTax" class="btn btn-danger btn-block">Remove Tax</button>
                    </div>
                </div>
                <div class="mt-4">
                    <button class="btn btn-success btn-block">Save Transaction</button>
                </div>
            </div>
        </div>
    </div>
    @if (session()->has('error'))
        <script>
            setTimeout(function() {
                document.querySelector('.alert-stock').removeClass = 'd-none'
                document.querySelector('.alert-stock').className = 'd-none'
            }, 1000)

        </script>
    @endif
</div>
