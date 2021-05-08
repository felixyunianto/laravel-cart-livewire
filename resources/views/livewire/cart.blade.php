<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <h2 class="font-weight-bold">Product List</h2>
                <div class="row">
                    @foreach ($products as $product)
                        <div class="col-md-3 mb-3">
                            <div class="card">
                                <div class="card-body d-flex justify-content-center align-items-center" style="height: 150px;">
                                    <img width="100%"  src="{{ asset('storage/images/' . $product->image) }}" alt="Image"
                                        class="img-fluid">
                                </div>
                                <div class="card-footer">
                                    <h6 class="text-center">{{ $product->name }}</h6>
                                    <button wire:click="addItem({{ $product->id }})"
                                        class="btn btn-primary btn-sm btn-block">Add To Cart</button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="card">
            <div class="card-body">
                <h2 class="font-weight-bold">Cart</h2>
                @if(session()->has('error'))
                    <small class="text-danger alert-stock">{{ session()->get('error') }}</small>
                @endif
                <table class="table table-bordered table-hovered table-striped">
                    <thead>
                        <tr>
                            <th width="5%">No.</th>
                            <th>Nama</th>
                            <th>QTY</th>
                            <th>Price</th>
                            <th width="2%">#</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($carts as $index => $cart)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $cart['name'] }}</td>
                                <td>
                                    <button wire:click="increaseItem('{{$cart['rowId']}}')" class="btn btn-warning btn-sm">+</button>
                                    &nbsp;
                                    {{ $cart['qty'] }}
                                    &nbsp;
                                    <button wire:click="decreaseItem('{{$cart['rowId']}}')" class="btn btn-danger btn-sm">-</button>
                                </td>
                                <td class="text-right">{{ number_format($cart['priceSingle'],0,'','.') }}</td>
                                <td><button wire:click="removeItem('{{ $cart['rowId'] }}')" class="btn btn-danger btn-sm"><i class="fas fa-trash"></i></button></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
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
                        <td>Rp. {{ number_format($summary['sub_total'],0,'','.') }}</td>
                    </tr>
                    <tr>
                        <td>Tax</td>
                        <td>:</td>
                        <td>Rp. {{ number_format($summary['pajak'],0,'','.') }}</td>
                    </tr>
                    <tr>
                        <td>Subtotal</td>
                        <td>:</td>
                        <td>Rp. {{ number_format($summary['total'],0,'','.') }}</td>
                    </tr>
                </table>
                <div class="mt-2">
                    <button wire:click="enableTax" class="btn btn-info btn-block">Add Tax</button>
                    <button wire:click="disableTax" class="btn btn-danger btn-block">Remove Tax</button>
                </div>
                <div class="mt-4">
                    <button class="btn btn-success btn-block">Save Transaction</button>
                </div>
            </div>
        </div>
    </div>
    @if (session()->has('error'))
    <script>
        setTimeout(function(){
            document.querySelector('.alert-stock').removeClass = 'd-none'
            document.querySelector('.alert-stock').className = 'd-none'
        }, 1000)
    </script>
@endif
</div>
