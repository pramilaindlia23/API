<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Your Cart</title>
    <!-- Bootstrap 4 or 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Custom styles for this template-->
<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>
<body id="page-top">
    {{-- Page wrapper --}}
    <div id="wrapper">
     <!-- Sidebar -->
     @include('dashboard.sidebar')
     <div id="content-wrapper" class="d-flex flex-column">
     <div id="content">
        
        @include('dashboard.header')
    <div class="container mt-5">
    
        <h2 class="text-center mb-4 card-body bg-success">Shopping Cart</h2>
        <!-- Success and Error Alerts -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <!-- Cart Items Table -->
        @if(count($cart) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($cart as $id => $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                {{-- <td>${{ $item['price'] }}</td> --}}
                                <td>
                                    <form action="{{ route('cart.update', $id) }}" method="POST">
                                        @csrf
                                        <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="form-control form-control-sm w-50 d-inline-block">
                                        <button type="submit" class="btn btn-sm btn-warning">Update</button>
                                    </form>
                                </td>
                                <td>${{ number_format($item['price'], 2) }}</td>
                                <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                                <td>
                                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                                
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Total -->
            <div class="text-end mt-3">
                <h4>Total: ${{ number_format(array_sum(array_map(function($item) {
                    return $item['price'] * $item['quantity'];
                }, $cart)), 2) }}</h4>
            </div>

           
        <div class="text-right">
            <a href="{{ route('checkout.index') }}" class="btn btn-success">Proceed to Checkout</a>
        </div>

        @else
            <div class="alert alert-info text-center">
                Your cart is empty. Start adding products to your cart!
            </div>
        @endif
    </div>
    </div>
    </div>
    </div>

    <!-- Bootstrap JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Core plugin JavaScript-->
    <script src="{{ asset('assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

    <!-- Custom scripts for all pages-->
    <script src="{{ asset('assets/js/sb-admin-2.min.js') }}"></script>

    <!-- Page level plugins -->
    <script src="{{ asset('assets/vendor/chart.js/Chart.min.js') }}"></script>

    <!-- Page level custom scripts -->
    <script src="{{ asset('assets/js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('assets/js/demo/chart-pie-demo.js') }}"></script>
    @include('dashboard.footer')
</body>
</html>
