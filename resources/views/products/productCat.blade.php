
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- <title>{{ $category->name }} - Products</title> --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="card-body">

<div class="container mt-5">
    <h4>Results</h4>
    <p>Check each product page for other buying options. Price and other details may vary based on product size and colour.</p>
   {{-- <script>
    const productCard = `
     <p><strong>Category:</strong> ${product.category_name}</p>`
    container.innerHTML += productCard;
   </script> --}}
    {{-- <div class="row row-cols-1 row-cols-md-3 g-4">
       
        @foreach ($products as $product)
            <div class="col">
                <div class="card h-100 shadow-sm border-light rounded">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
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
    </div> --}}
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

</body>
</html>



