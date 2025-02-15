<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Products</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   </head>
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
   <body>
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

        {{-- /// working code //// --}}
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

                // **Category Click Event**
                document.body.addEventListener("click", function (event) {
    if (event.target.classList.contains("category-image")) {
        const categoryId = event.target.getAttribute("data-category-id");
        window.location.href = `/productCat/${categoryId}`;
    }
});

                
//                 document.body.addEventListener("click", function (event) {
//     if (event.target.classList.contains("category-image")) {
//         const categoryId = event.target.getAttribute("data-category-id");

//         if (categoryId) {
//             console.log("Redirecting to category page:", categoryId);
//             window.location.href = `/products/category/${categoryId}`;
//             // Redirect to category page
//         } else {
//             console.error("Error: categoryId is undefined");
//         }
//     }
// });
                // **Submit Rating**
                // document.body.addEventListener("click", function (event) {
                //     if (event.target.classList.contains("rating-star")) {
                //         const productId = event.target.getAttribute("data-product-id");
                //         const rating = event.target.getAttribute("data-rating");
                //         axios.post('/api/rate-product', { product_id: productId, rating: rating })
                //             .then(() => {
                //                 alert("Rating submitted successfully!");
                //                 location.reload();
                //             })
                //             .catch(() => alert("Error submitting rating."));
                //     }
                // });
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
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

      
   </body>
</html>