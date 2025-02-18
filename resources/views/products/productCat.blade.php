{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Products</title> --}}

    <!-- Bootstrap CSS -->
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-light"> --}}

    {{-- <div class="container py-4">
        <h2 class="text-center mb-4">{{ $category->name }} Products</h2>

        <div class="row" id="product-container">
            @foreach ($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        
                        <!-- Product Image with Bootstrap Carousel -->
                        <div id="carousel-{{ $product->id }}" class="carousel slide" data-bs-ride="carousel">
                            <div class="carousel-inner">
                                @php $images = json_decode($product->images, true) ?? [$product->image]; @endphp
                                @foreach ($images as $index => $image)
                                    <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                        <img src="{{ asset('storage/' . $image) }}" class="d-block w-100" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            </button>
                            <button class="carousel-control-next" type="button" data-bs-target="#carousel-{{ $product->id }}" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            </button>
                        </div>

                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $product->name }}</h5>

                            <!-- Discount Price Calculation -->
                            @php 
                                $discountPercent = ($product->discount_code ?? 0) > 0 ? round(($product->discount_code / $product->price) * 100) : 0;
                                $finalPrice = $product->price - ($product->discount_code ?? 0);
                            @endphp

                            <p class="text-muted">
                                <del>${{ number_format($product->price, 2) }}</del> 
                                <span class="text-success"> ${{ number_format($finalPrice, 2) }}</span>
                                @if ($discountPercent > 0)
                                    <span class="badge bg-danger">{{ $discountPercent }}% Off</span>
                                @endif
                            </p>

                            <!-- Dynamic Star Rating -->
                            <div class="rating" data-product-id="{{ $product->id }}">
                                @php $avgRating = round($product->average_rating ?? 0); @endphp
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star rating-star {{ $i <= $avgRating ? 'text-warning' : 'text-secondary' }}"
                                       data-rating="{{ $i }}" onclick="submitRating({{ $product->id }}, {{ $i }})"></i>
                                @endfor
                                <strong>({{ $avgRating }}/5)</strong>
                            </div>

                            <!-- Add to Cart Button -->
                            <button class="btn btn-primary w-100 mt-3 add-to-cart" data-id="{{ $product->id }}">Add to Cart 🛒</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div> --}}
    {{-- <div class="container">
        <h2>Products in this Category</h2>
        <div class="row">
            @foreach($products as $product)
                <div class="col-md-4">
                    <div class="card">
                        <img src="/storage/{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5>{{ $product->name }}</h5>
                            <p>{{ $product->description }}</p>
                            <p>Price: ${{ $product->price }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div> --}}
    <!-- Bootstrap JS -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Handle Add to Cart Button Click
            document.body.addEventListener("click", function (event) {
                if (event.target.classList.contains("add-to-cart")) {
                    const productId = event.target.getAttribute("data-id");
                    alert(" Product " + productId + " added to cart!");
                }
            });
        });

        // Submit Product Rating
        function submitRating(productId, rating) {
            axios.post('/api/rate-product', {
                product_id: productId,
                rating: rating
            })
            .then(response => {
                alert("⭐ Rating submitted successfully!");
                location.reload();
            })
            .catch(error => {
                alert("Error submitting rating. Please try again.");
            });
        }
    </script>

</body>
</html> --}}







{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Products</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
</head>
<body class="bg-light">

    <div class="container py-4">
        <h2 class="text-center mb-4">{{ $category->name }} Products</h2>

        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted">${{ number_format($product->price, 2) }}</p>
                            <a href="#" class="btn btn-primary">Buy Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    const urlParams = new URLSearchParams(window.location.search);
    const categoryId = urlParams.get("category_id");

    if (!categoryId) {
        document.getElementById("category-title").innerText = "No category selected.";
        return;
    }

    axios.get(`/api/category-products/${categoryId}`)
        .then(response => {
            const products = response.data.products;
            const container = document.getElementById("category-products");
            container.innerHTML = "";

            products.forEach(product => {
                container.innerHTML += `
                    <div class="col">
                        <div class="card">
                            <img src="/storage/${product.image}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">$${product.price}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error("Error fetching category products:", error);
        });
});

    </script>
</body>
</html> --}}


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
    <div class="row row-cols-1 row-cols-md-3 g-4">
       
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



