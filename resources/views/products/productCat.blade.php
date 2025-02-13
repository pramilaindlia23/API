<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Products</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script defer src="category.js"></script>
</head>
<body>
    <h2 id="category-title">Category Products</h2>
    <div id="category-products" class="row">
        <h2 class="text-center">{{ $category->name }} Products</h2>

        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-4">
                    <div class="card">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">${{ number_format($product->price, 2) }}</p>
                            <a href="#" class="btn btn-primary">Buy Now</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

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
</html>
