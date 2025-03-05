
<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Audio</title>
      <!-- Bootstrap 5 CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
      <link
         href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
         rel="stylesheet">
      <!-- Custom styles for this template-->
      <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
      <link rel="icon" type="image/x-icon" href="https://pbs.twimg.com/profile_images/1625786717935640577/QUQt8syP_400x400.png">
   </head>
   <body id="page-top">
      <div id="wrapper">
      <!-- Sidebar -->
      @include('dashboard.sidebar')
      <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
      @include('dashboard.header')

<div class="container mt-5">
    <h4>Results</h4>
    <p>Check each product page for other buying options. Price and other details may vary based on product size and colour.</p>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        @foreach ($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm border-light rounded">
                    <a href="{{ route('products.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ route('products.show', $product->id) }}" class="text-dark text-decoration-none"><strong>Name:</strong>           
                            {{ $product->name }}</a>
                        </h5>
                        <p class="card-text"><strong>Brand:</strong>    {{$product->brand_name}}</p>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text text-muted"><strong>Original Price:</strong> <span style="text-decoration: line-through;">${{ number_format($product->price, 2) }}</span></p>
                        <p class="card-text text-success"><strong>✔ Discount: {{ $product->discount_code ? $product->discount_code . "%" : "No Discount" }}</strong></p>
                        <p class="card-text text-dark"><strong>Final Price: ${{ number_format($product->price - ($product->price * $product->discount_code / 100), 2) }}</strong></p>
                    </div>
                    <div class="card-footer text-center">
                        <button class="btn btn-primary w-100 add-to-cart" data-id="{{ $product->id }}">Add to Cart</button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    
</div>

<!-- Axios for Add to Cart -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.body.addEventListener("click", function (event) {
        if (event.target.classList.contains("add-to-cart")) {
            const productId = event.target.getAttribute("data-id");
            axios.post(`/api/add-to-cart/${productId}`)
                .then(() => alert("Product added to cart successfully!"))
                .catch(() => alert("Error adding product to cart."));
        }
    });
</script>
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



