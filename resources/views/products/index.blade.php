<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <a href="{{ route('products.create') }}" class="btn btn-dark float-right mt-3">Add New Product</a>
        <h2 class="text-center mb-5 bg-success text-white p-3 rounded">Our Products</h2>

        <div class="row row-cols-1 row-cols-md-3 g-4" id="products-container">
            <!-- Products will be dynamically inserted here -->
        </div>

    </div>
   
    <!-- Add Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.get('/api/products')
    .then(function (response) {
        console.log(response.data); 
    });

    </script>
  
        <script>
          document.addEventListener('DOMContentLoaded', function () {
    axios.get('/api/products')
        .then(function (response) {
            const products = response.data;
            const container = document.getElementById('products-container');

            products.forEach(function (product) {
                const originalPrice = parseFloat(product.price);
                const discountedPrice = parseFloat(product.discounted_price);
                const discountedAmount = (originalPrice - discountedPrice).toFixed(2);

                const productCard = `
                    <div class="col">
                        <div class="card h-100 shadow-sm border-light rounded">
                            <img src="/storage/${product.image}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text text-muted">
                                    <span style="text-decoration: line-through;">$${originalPrice}</span> 
                                    <small>$${discountedPrice}</small>  <!-- Discounted Price -->
                                    <br> <strong> Amount: $${discountedAmount}</strong>
                                </p>
                            </div>
                            <div class="card-footer text-center">
                                <button class="btn btn-primary w-100 add-to-cart" data-id="${product.id}">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                `;

                container.innerHTML += productCard;
            });
        })
        .catch(function (error) {
            console.error('There was an error fetching the products:', error);
        });
});

        </script>
</body>
</html>
