<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Products</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
      {{-- <script>
         axios.get('/api/products')
         .then(function (response) {
             console.log('API Response:', response.data); 
         })
         .catch(function (error) {
             console.error('There was an error fetching the products:', error);
         });
         
      </script> --}}
      {{-- <script>
         document.addEventListener("DOMContentLoaded", function () {
         axios.get('/api/products')
         .then(function (response) {
         const products = response.data.products || response.data;
         const container = document.getElementById('products-container');
         container.innerHTML = "";
         
         products.forEach(function (product) {
             const originalPrice = parseFloat(product.price) || 0;
             let discountPercentage = 0;
         
             if (product.discount_code === 'SAVE10' || product.discount_code === '10') {
                 discountPercentage = 10;
             } else if (product.discount_code === 'SAVE20' || product.discount_code === '20') {
                 discountPercentage = 20;
             }
         
             const finalPrice = (originalPrice - (originalPrice * discountPercentage / 100)).toFixed(2);
             const averageRating = product.average_rating || 0;
             const maxStars = 5;
             let starsHTML = '';
         
             for (let i = 1; i <= maxStars; i++) {
                 starsHTML += `<i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}" 
                                 data-product-id="${product.id}" 
                                 data-rating="${i}"></i>`;
             }
         
             const productCard = `
                 <div class="col">
                     <div class="card h-100 shadow-sm border-light rounded">
                         <img src="/storage/${product.image}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                         <div class="card-body">
                             <h5 class="card-title">${product.name}</h5>
                             <p class="card-text">${product.description}</p>
         
                            
         
                             <p class="card-text text-muted">
                                 <strong>Original Price:</strong>
                                 <span style="text-decoration: line-through;">$${originalPrice.toFixed(2)}</span>
                             </p>
         
                             <p class="card-text text-success">
                                 <strong>✔ Discount: ${discountPercentage}%</strong>
                             </p>
         
                             <p class="card-text text-dark">
                                 <strong>Final Price: $${finalPrice}</strong>
                             </p>
         
                             <div class="rating-container" data-product-id="${product.id}">
                                 ${starsHTML} 
                                 <strong>(${averageRating}/5)</strong>
                             </div>
                         </div>
                         <div class="card-footer text-center">
                             <button class="btn btn-primary w-100 add-to-cart" data-id="${product.id}">Add to Cart</button>
                         </div>
                     </div>
                 </div>
             `;
         
             container.innerHTML += productCard;
         });
         
         document.querySelectorAll(".rating-star").forEach(star => {
             star.addEventListener("click", function () {
                 const productId = this.getAttribute("data-product-id");
                 const rating = this.getAttribute("data-rating");
                 submitRating(productId, rating);
             });
         });
         
         document.querySelectorAll(".add-to-cart").forEach(button => {
             button.addEventListener("click", function () {
                 const productId = this.getAttribute("data-id");
                 addToCart(productId);
             });
         });
         })
         .catch(function (error) {
         console.error('Error fetching the products:', error);
         });
         });
         
         function addToCart(productId) {
             axios.post(`/api/add-to-cart/${productId}`)
                 .then(response => {
                     alert("Product added to cart successfully!");
                 })
                 .catch(error => {
                     alert("Error adding product to cart. Please try again.");
                 });
         }
         
         function submitRating(productId, rating) {
             axios.post('/api/rate-product', {
                 product_id: productId,
                 rating: rating
             })
             .then(response => {
                 alert("Rating submitted successfully!");
                 location.reload();
             })
             .catch(error => {
                 alert("Error submitting rating. Please try again.");
             });
         }
         
      </script> --}}
      <script>
      document.addEventListener("DOMContentLoaded", function () {
    axios.get('/api/products')
        .then(function (response) {
            const products = response.data.products || response.data;
            const container = document.getElementById('products-container');
            container.innerHTML = "";

            products.forEach(function (product) {
                const originalPrice = parseFloat(product.price) || 0;
                const discountAmount = parseFloat(product.discount_amount) || 0;
                const finalPrice = parseFloat(product.discounted_price) || originalPrice;

                // Fix discount display
                let discountText = discountAmount > 0 
                    ? `<strong>✔ Discount: ${(discountAmount / originalPrice * 100).toFixed(0)}%</strong>` 
                    : `<strong>✔ Discount: No Discount</strong>`;

                const productCard = `
                    <div class="col">
                        <div class="card h-100 shadow-sm border-light rounded">
                            <img src="/storage/${product.image}" class="card-img-top" alt="${product.name}" style="height: 200px; object-fit: cover;">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>

                                <p class="card-text text-muted">
                                    <strong>Original Price:</strong>
                                    <span style="text-decoration: line-through;">$${originalPrice.toFixed(2)}</span>
                                </p>

                                <p class="card-text text-success">
                                    ${discountText}
                                </p>

                                <p class="card-text text-dark">
                                    <strong>Final Price: $${finalPrice.toFixed(2)}</strong>
                                </p>
                            </div>
                        </div>
                    </div>
                `;

                container.innerHTML += productCard;
            });
        })
        .catch(function (error) {
            console.error('Error fetching the products:', error);
        });
});


      </script>
   </body>
</html>