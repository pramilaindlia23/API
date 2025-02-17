{{-- <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <style>
            .product-card img {
            width: 50%; 
            height: 200px; 
            object-fit: cover; 
            min-width: 100px; 
            min-height: 100px; 
            border-radius: 8px; 
            }
           
        </style>
    </head>
    <body class="antialiased">
        <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
            @if (Route::has('login'))
                <div class="sm:fixed sm:top-0 sm:right-0 p-6 text-right z-10">
                    @auth
                        <a href="{{ url('/') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Home</a>
                    @else
                        <a href="{{ route('login') }}" class="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Log in</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="ml-4 font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500">Register</a>
                        @endif
                    @endauth
                </div>
            @endif

            <div class="container mt-5">
                <a href="{{ route('products.create') }}" class="btn btn-dark float-right mt-3">Add New Product</a>
                <h2 class="text-center mb-5 bg-success text-white p-3 rounded">Our Products</h2>
                <div class="row row-cols-1 row-cols-md-3 g-4" id="products-container">
                   <div class="modal fade" id="categoryImagesModal" tabindex="-1" aria-labelledby="categoryImagesModalLabel" aria-hidden="true">
                       <div class="modal-dialog modal-lg">
                           <div class="modal-content">
                               <div class="modal-header">
                                   <h5 class="modal-title" id="categoryImagesModalLabel">Category Images</h5>
                                   <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                               </div>
                               <div class="modal-body">
                                   <div class="row" id="category-images-container">
                                       <!-- Images will be loaded here dynamically -->
                                   </div>
                               </div>
                           </div>
                       </div>
                   </div>
                </div>
             </div>
             <!-- Add Axios CDN -->
             <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
             <script>
  
                document.addEventListener("DOMContentLoaded", function () {
                    axios.get('/api/products')
                        .then(response => {
                            const products = response.data;
                            const container = document.getElementById('products-container');
                            container.innerHTML = "";
            
                            function renderProducts(filteredProducts) {
                                container.innerHTML = "";
                                filteredProducts.forEach(product => {
                                    const originalPrice = parseFloat(product.price) || 0;
                                    const discountCode = parseFloat(product.discount_code) || 0;
                                    const finalPrice = originalPrice - (originalPrice * discountCode / 100);
                                    const averageRating = product.average_rating || 0;
                                    const maxStars = 5;
            
                                    let starsHTML = '';
                                    for (let i = 1; i <= maxStars; i++) {
                                        starsHTML += `
                                            <i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"
                                                data-product-id="${product.id}" data-rating="${i}">
                                            </i>`;
                                    }
            
                                    let imageUrl = product.image
                                        ? `/storage/${product.image}`
                                        : "/storage/default-product.jpg";
            
                                    const productCard = `
                                        <div class="col">
                                            <div class="card h-100 shadow-sm border-light rounded product-card" data-category-id="${product.category_id}">
                                                <img src="${imageUrl}" class="card-img-top category-image" data-category-id="${product.category_id}" alt="${product.name}">
                                                <div class="card-body">
                                                    <h5 class="card-title">${product.name}</h5>
                                                    <p class="card-text">${product.description}</p>
                                                    <p class="card-text text-muted"><strong>Original Price:</strong> <span style="text-decoration: line-through;">$${originalPrice.toFixed(2)}</span></p>
                                                    <p class="card-text text-success"><strong>✔ Discount: ${discountCode > 0 ? discountCode + "%" : "No Discount"}</strong></p>
                                                    <p class="card-text text-dark"><strong>Final Price: $${finalPrice.toFixed(2)}</strong></p>
                                                    <div class="rating-container" data-product-id="${product.id}">${starsHTML} <strong>(${averageRating}/5)</strong></div>
                                                </div>
                                                <div class="card-footer text-center">
                                                    <button class="btn btn-primary w-100 add-to-cart" data-id="${product.id}">Add to Cart</button>
                                                </div>
                                            </div>
                                        </div>`;
            
                                    container.innerHTML += productCard;
                                });
                            }
            
                            renderProducts(products); // Show all products on page load
            
            document.body.addEventListener("click", function (event) {
                if (event.target.classList.contains("category-image")) {
                    const categoryId = event.target.getAttribute("data-category-id");
                    window.location.href = `/productCat/${categoryId}`;
                }
            });
            document.body.addEventListener("click", function (event) {
                                if (event.target.classList.contains("rating-star")) {
                                    const productId = event.target.getAttribute("data-product-id");
                                    const rating = event.target.getAttribute("data-rating");
                                    const review = event.target.getAttribute('data-review');
            
                                    // Ask user for a title when submitting a rating
                                    const title = prompt("Enter a title for your rating (optional):");
            
                                    axios.post('/api/rate-product', { 
                                        product_id: productId, 
                                        rating: rating, 
                                        title: title || "" ,
                                        review: review || ""
                                    })
                                    .then(() => {
                                        alert("Rating submitted successfully!");
                                        location.reload();
                                    })
                                    .catch(() => alert("Error submitting rating."));
                                }
                            });
            
                            // **Add to Cart**
                            document.body.addEventListener("click", function (event) {
                                if (event.target.classList.contains("add-to-cart")) {
                                    const productId = event.target.getAttribute("data-id");
                                    axios.post(`/api/add-to-cart/${productId}`)
                                        .then(() => alert("Product added to cart successfully!"))
                                        .catch(() => alert("Error adding product to cart."));
                                }
                            });
                        })
                        .catch(error => console.error('Error fetching the products:', error));
                });
            </script>
                  <!-- Modal -->
                 
            
        </div>
    </body>
</html> --}}


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel Shop | Home</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Custom Styles */
        .hero {
            background: url('https://source.unsplash.com/1600x600/?shopping,store') no-repeat center center/cover;
            height: 400px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
        }

        .hero h1 {
            font-size: 3rem;
            font-weight: bold;
            text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.7);
        }

        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .footer {
            background: #222;
            color: white;
            padding: 20px 0;
            text-align: center;
        }
    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">VCS <sub>group</sub><sup>2</sup></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="/products">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="/cart"><i class="fas fa-shopping-cart"></i> Cart</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="/dashboard"><i class="fas fa-user"></i> Dashboard</a></li>
                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-link nav-link" type="submit">Logout</button>
                            </form>
                        </li>
                    @else
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('register') }}">Register</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container text-center">
            <h1>Welcome to Lowest Price Best Quality Shopping</h1>
            
            <a href="/products" class="btn btn-light btn-lg">Shop Now <i class="fas fa-arrow-right"></i></a>
        </div>
        
    </section>
    

    <!-- Featured Products -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row" id="products-container">
            <!-- Products will be loaded here dynamically -->
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer">
        <p>&copy; 2025 Laravel Shop. All Rights Reserved.</p>
        <p><a href="#" class="text-light">Privacy Policy</a> | <a href="#" class="text-light">Terms of Service</a></p>
    </footer>

    <!-- Bootstrap JS + Axios -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            axios.get('/api/products')
                .then(response => {
                    const products = response.data;
                    const container = document.getElementById('products-container');
                    container.innerHTML = "";

                    products.forEach(product => {
                        const originalPrice = parseFloat(product.price) || 0;
                        const discountCode = parseFloat(product.discount_code) || 0;
                        const finalPrice = originalPrice - (originalPrice * discountCode / 100);
                        const imageUrl = product.image ? `/storage/${product.image}` : "/storage/default-product.jpg";

                        const productCard = `
                            <div class="col-md-4">
                                <div class="card h-100 shadow-sm product-card">
                                    <img src="${imageUrl}" class="card-img-top" alt="${product.name}">
                                    <div class="card-body">
                                        <h5 class="card-title">${product.name}</h5>
                                        <p class="card-text">${product.description}</p>
                                        <p class="text-muted"><strong>Original Price:</strong> <span style="text-decoration: line-through;">$${originalPrice.toFixed(2)}</span></p>
                                        <p class="text-success"><strong>✔ Discount: ${discountCode > 0 ? discountCode + "%" : "No Discount"}</strong></p>
                                        <p class="text-dark"><strong>Final Price: $${finalPrice.toFixed(2)}</strong></p>
                                    </div>
                                    <div class="card-footer text-center">
                                        <button class="btn btn-primary w-100 add-to-cart" data-id="${product.id}">Add to Cart</button>
                                    </div>
                                </div>
                            </div>`;
                        container.innerHTML += productCard;
                    });
                })
                .catch(error => console.error('Error fetching products:', error));
        });

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
