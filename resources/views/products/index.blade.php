<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Products</title>
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   </head>
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
      {{-- <script>
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
                        const averageRating = product.average_rating || 0;
                        const maxStars = 5;
    
                        // Generate rating stars
                        let starsHTML = '';
                        for (let i = 1; i <= maxStars; i++) {
                            starsHTML += `
                                <i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"
                                    data-product-id="${product.id}" data-rating="${i}">
                                </i>`;
                        }
    
                        // Generate discount text
                        let discountText = discountAmount > 0 
                            ? `<strong>✔ Discount: ${(discountAmount / originalPrice * 100).toFixed(0)}%</strong>` 
                            : `<strong>✔ Discount: No Discount</strong>`;
    
                        // Create Product Card
                        const productCard = `
                            <div class="col">
                                <div class="card h-100 shadow-sm border-light rounded product-card" 
                                     data-category-id="${product.category_id}">
                                
                                    <div id="carousel-${product.id}" class="carousel slide" data-bs-ride="carousel">
                                        <div class="carousel-inner">
                                            ${JSON.parse(product.images || '[]').map((image, index) => `
                                                <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                                   @foreach ($products as $product)
                                                  <div class="product-card">
                                                    <h3>{{ $product->name }}</h3>
                                                    <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="max-width: 200px;">
                                                    <p>Price: ${{ $product->final_price }}</p>
                                                    <p>Rating: {{ number_format($product->average_rating, 1) }} / 5</p>
                                                </div>
                                            @endforeach

                                                </div>
                                            `).join('')}
                                        </div>
                                        <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="prev">
                                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                        </button>
                                        <button class="carousel-control-next" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="next">
                                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                        </button>
                                    </div>
    
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
    
                })
                .catch(function (error) {
                    console.error('Error fetching the products:', error);
                });
    
            // Redirect to category page when product is clicked
            document.body.addEventListener("click", function (event) {
                let productCard = event.target.closest(".product-card");
                if (productCard) {
                    const categoryId = productCard.getAttribute("data-category-id");
                    if (categoryId) {
                        window.location.href = `/productCat/${categoryId}`;
                    }
                }
            });
    
            // Handle rating submission
            document.body.addEventListener("click", function (event) {
                if (event.target.classList.contains("rating-star")) {
                    event.stopPropagation();  // Prevent triggering category click
                    const productId = event.target.getAttribute("data-product-id");
                    const rating = event.target.getAttribute("data-rating");
                    submitRating(productId, rating);
                }
            });
    
            // Handle add to cart button
            document.body.addEventListener("click", function (event) {
                if (event.target.classList.contains("add-to-cart")) {
                    event.stopPropagation(); // Prevent category click
                    const productId = event.target.getAttribute("data-id");
                    addToCart(productId);
                }
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
        // document.addEventListener("DOMContentLoaded", function () {
        //     axios.get('/api/products')
        //         .then(function (response) {
        //             const products = response.data.products || response.data;
        //             const container = document.getElementById('products-container');
        //             container.innerHTML = "";
    
        //             products.forEach(function (product) {
        //                 const originalPrice = parseFloat(product.price) || 0;
        //                 const discountAmount = parseFloat(product.discount_amount) || 0;
        //                 const finalPrice = parseFloat(product.discounted_price) || originalPrice;
        //                 const averageRating = product.average_rating || 0;
        //                 const maxStars = 5;
    
        //                 // Generate rating stars
        //                 let starsHTML = '';
        //                 for (let i = 1; i <= maxStars; i++) {
        //                     starsHTML += `
        //                         <i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"
        //                             data-product-id="${product.id}" data-rating="${i}">
        //                         </i>`;
        //                 }
    
        //                 // Generate discount text
        //                 let discountText = discountAmount > 0 
        //                     ? `<strong>✔ Discount: ${(discountAmount / originalPrice * 100).toFixed(0)}%</strong>` 
        //                     : `<strong>✔ Discount: No Discount</strong>`;
    
        //                 // **Fix Image Handling**
        //                 let imageUrls = [];
        //                 try {
        //                     imageUrls = JSON.parse(product.images || '[]');
        //                 } catch (error) {
        //                     console.error("Error parsing images:", error);
        //                 }
    
        //                 let carouselImages = imageUrls.length > 0
        //                     ? imageUrls.map((image, index) => `
        //                         <div class="carousel-item ${index === 0 ? 'active' : ''}">
        //                             <img src="${image}" class="d-block w-100" alt="${product.name}">
        //                         </div>
        //                     `).join('')
        //                     : `<div class="carousel-item active">
        //                             <img src="/default-product.jpg" class="d-block w-100" alt="No Image">
        //                        </div>`; 
    
        //                 // Create Product Card
        //                 const productCard = `
        //                     <div class="col">
        //                         <div class="card h-100 shadow-sm border-light rounded product-card" 
        //                              data-category-id="${product.category_id}">
                                
        //                             <div id="carousel-${product.id}" class="carousel slide" data-bs-ride="carousel">
        //                                 <div class="carousel-inner">
        //                                     ${carouselImages}
        //                                 </div>
        //                                 <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="prev">
        //                                     <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        //                                 </button>
        //                                 <button class="carousel-control-next" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="next">
        //                                     <span class="carousel-control-next-icon" aria-hidden="true"></span>
        //                                 </button>
        //                             </div>
    
        //                             <div class="card-body">
        //                                 <h5 class="card-title">${product.name}</h5>
        //                                 <p class="card-text">${product.description}</p>
        //                                 <p class="card-text text-muted">
        //                                     <strong>Original Price:</strong>
        //                                     <span style="text-decoration: line-through;">$${originalPrice.toFixed(2)}</span>
        //                                 </p>
        //                                 <p class="card-text text-success">
        //                                     ${discountText}
        //                                 </p>
        //                                 <p class="card-text text-dark">
        //                                     <strong>Final Price: $${finalPrice.toFixed(2)}</strong>
        //                                 </p>
    
        //                                 <div class="rating-container" data-product-id="${product.id}">
        //                                     ${starsHTML} 
        //                                     <strong>(${averageRating}/5)</strong>
        //                                 </div>
        //                             </div>
    
        //                             <div class="card-footer text-center">
        //                                 <button class="btn btn-primary w-100 add-to-cart" data-id="${product.id}">Add to Cart</button>
        //                             </div>
        //                         </div>
        //                     </div>
        //                 `;
    
        //                 container.innerHTML += productCard;
        //             });
    
        //         })
        //         .catch(function (error) {
        //             console.error('Error fetching the products:', error);
        //         });
    
        //     // Redirect to category page when product is clicked
        //     document.body.addEventListener("click", function (event) {
        //         let productCard = event.target.closest(".product-card");
        //         if (productCard) {
        //             const categoryId = productCard.getAttribute("data-category-id");
        //             if (categoryId) {
        //                 window.location.href = `/productCat/${categoryId}`;
        //             }
        //         }
        //     });
    
        //     // Handle rating submission
        //     document.body.addEventListener("click", function (event) {
        //         if (event.target.classList.contains("rating-star")) {
        //             event.stopPropagation();  // Prevent triggering category click
        //             const productId = event.target.getAttribute("data-product-id");
        //             const rating = event.target.getAttribute("data-rating");
        //             submitRating(productId, rating);
        //         }
        //     });
    
        //     // Handle add to cart button
        //     document.body.addEventListener("click", function (event) {
        //         if (event.target.classList.contains("add-to-cart")) {
        //             event.stopPropagation(); // Prevent category click
        //             const productId = event.target.getAttribute("data-id");
        //             addToCart(productId);
        //         }
        //     });
    
        // });
    
        // function addToCart(productId) {
        //     axios.post(`/api/add-to-cart/${productId}`)
        //         .then(response => {
        //             alert("Product added to cart successfully!");
        //         })
        //         .catch(error => {
        //             alert("Error adding product to cart. Please try again.");
        //         });
        // }
    
        // function submitRating(productId, rating) {
        //     axios.post('/api/rate-product', {
        //         product_id: productId,
        //         rating: rating
        //     })
        //     .then(response => {
        //         alert("Rating submitted successfully!");
        //         location.reload();
        //     })
        //     .catch(error => {
        //         alert("Error submitting rating. Please try again.");
        //     });
        // }
        document.addEventListener("DOMContentLoaded", function () {
    axios.get('/api/products')
        .then(function (response) {
            const products = response.data.products || response.data;
            const container = document.getElementById('products-container');
            container.innerHTML = "";

            products.forEach(function (product) {
                const originalPrice = parseFloat(product.price) || 0;
                const discountCode = parseFloat(product.discount_code) || 0; // Fix discount
                const finalPrice = originalPrice - (originalPrice * discountCode / 100); // Corrected discount calculation
                const averageRating = product.average_rating || 0;
                const maxStars = 5;
                // Generate rating stars
                        let starsHTML = '';
                        for (let i = 1; i <= maxStars; i++) {
                            starsHTML += `
                                <i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"
                                    data-product-id="${product.id}" data-rating="${i}">
                                </i>`;
                        }
                // // Generate rating stars
                // let starsHTML = '';
                // for (let i = 1; i <= maxStars; i++) {
                //     starsHTML += `
                //         <i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"
                //             data-product-id="${product.id}" data-rating="${i}">
                //         </i>`;
                // }

                // **Fix Image Handling**
                let imageUrls = [];
                try {
                    if (product.images && product.images.startsWith("[")) {
                        imageUrls = JSON.parse(product.images);
                    } else if (typeof product.images === "string" && product.images !== "") {
                        imageUrls = [product.images]; // If single image, wrap in an array
                    }
                } catch (error) {
                    console.error("Error parsing images:", error);
                }

                let carouselImages = imageUrls.length > 0
                    ? imageUrls.map((image, index) => `
                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                            <img src="${image}" class="d-block w-100" alt="${product.name}">
                        </div>
                    `).join('')
                    : `<div class="carousel-item active">
                            <img src="/default-product.jpg" class="d-block w-100" alt="No Image Available">
                       </div>`; 

                // Create Product Card
                const productCard = `
                    <div class="col">
                        <div class="card h-100 shadow-sm border-light rounded product-card" 
                             data-category-id="${product.category_id}">
                        
                            <div id="carousel-${product.id}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    ${carouselImages}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>

                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="card-text text-muted">
                                    <strong>Original Price:</strong>
                                    <span style="text-decoration: line-through;">$${originalPrice.toFixed(2)}</span>
                                </p>
                                <p class="card-text text-success">
                                    <strong>✔ Discount: ${discountCode > 0 ? discountCode + "%" : "No Discount"}</strong>
                                </p>
                                <p class="card-text text-dark">
                                    <strong>Final Price: $${finalPrice.toFixed(2)}</strong>
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

        })
        .catch(function (error) {
            console.error('Error fetching the products:', error);
        });

    // Redirect to category page when product is clicked
    document.body.addEventListener("click", function (event) {
        let productCard = event.target.closest(".product-card");
        if (productCard) {
            const categoryId = productCard.getAttribute("data-category-id");
            if (categoryId) {
                window.location.href = `/productCat/${categoryId}`;
            }
        }
    });

    // **Handle rating submission**
    document.body.addEventListener("click", function (event) {
        if (event.target.classList.contains("rating-star")) {
            event.stopPropagation(); // Prevent triggering category click
            const productId = event.target.getAttribute("data-product-id");
            const rating = event.target.getAttribute("data-rating");
            submitRating(productId, rating);
        }
    });

    // **Handle add to cart button**
    document.body.addEventListener("click", function (event) {
        if (event.target.classList.contains("add-to-cart")) {
            event.stopPropagation(); // Prevent category click
            const productId = event.target.getAttribute("data-id");
            addToCart(productId);
        }
    });

});

// **Function to add product to cart**
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


    </script>
    

    {{-- <script>
        document.addEventListener("DOMContentLoaded", function () {
    axios.get('/api/products')
        .then(response => {
            const products = response.data.products || response.data;
            const container = document.getElementById('products-container');
            container.innerHTML = "";

            products.forEach(product => {
                const finalPrice = parseFloat(product.discounted_price) || parseFloat(product.price);

                const productCard = `
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            <img src="/storage/${JSON.parse(product.images)[0]}" class="card-img-top" alt="${product.name}">
                            <div class="card-body">
                                <h5 class="card-title">${product.name}</h5>
                                <p class="card-text">${product.description}</p>
                                <p class="text-dark"><strong>Price: $${finalPrice.toFixed(2)}</strong></p>
                            </div>
                            <div class="card-footer">
                                <button class="btn btn-primary add-to-cart" data-id="${product.id}">Add to Cart</button>
                            </div>
                        </div>
                    </div>
                `;

                container.innerHTML += productCard;
            });
        })
        .catch(error => console.error('Error fetching products:', error));
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
                const discountAmount = parseFloat(product.discount_amount) || 0;
                const finalPrice = parseFloat(product.discounted_price) || originalPrice;
                const averageRating = product.average_rating || 0;
                const maxStars = 5;

                // Generate rating stars
                let starsHTML = '';
                for (let i = 1; i <= maxStars; i++) {
                    starsHTML += `
                        <i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}"
                            data-product-id="${product.id}" data-rating="${i}">
                        </i>`;
                }

                // Generate discount text
                let discountText = discountAmount > 0 
                    ? `<strong>✔ Discount: ${(discountAmount / originalPrice * 100).toFixed(0)}%</strong>` 
                    : `<strong>✔ Discount: No Discount</strong>`;

                // Create Product Card with Clickable Functionality
                const productCard = `
                    <div class="col">
                        <div class="card h-100 shadow-sm border-light rounded product-card" 
                             data-category-id="${product.category_id}">
                        
                            <div id="carousel-${product.id}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    ${JSON.parse(product.images || '[]').map((image, index) => `
                                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                            <img src="/storage/${image}" class="d-block w-100 product-image" 
                                                 alt="${product.name}" style="height: 200px; object-fit: cover;">
                                        </div>
                                    `).join('')}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>

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

            // Redirect to category page when product is clicked
            document.addEventListener("DOMContentLoaded", function () {
        document.body.addEventListener("click", function (event) {
            let productCard = event.target.closest(".product-card");
            if (productCard) {
                const categoryId = productCard.getAttribute("data-category-id");
                
                if (categoryId) {
                    window.location.href = "{{ url('/productCat') }}/" + categoryId;
                }
            }
        });
    });

            // Rating and Cart event listeners
            document.querySelectorAll(".rating-star").forEach(star => {
                star.addEventListener("click", function (event) {
                    event.stopPropagation();
                    const productId = this.getAttribute("data-product-id");
                    const rating = this.getAttribute("data-rating");
                    submitRating(productId, rating);
                });
            });

            document.querySelectorAll(".add-to-cart").forEach(button => {
                button.addEventListener("click", function (event) {
                    event.stopPropagation(); 
                    const productId = this.getAttribute("data-id");
                    addToCart(productId);
                });
            });
        })
        .catch(function (error) {
            console.error('Error fetching the products:', error);
        });
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
                const discountAmount = parseFloat(product.discount_amount) || 0;
                const finalPrice = parseFloat(product.discounted_price) || originalPrice;
                const averageRating = product.average_rating || 0;
                const maxStars = 5;
                let starsHTML = '';

                for (let i = 1; i <= maxStars; i++) {
                    starsHTML += `<i class="fas fa-star rating-star ${i <= averageRating ? 'text-warning' : 'text-secondary'}" 
                                    data-product-id="${product.id}" 
                                    data-rating="${i}"></i>`;
                }

                let discountText = discountAmount > 0 
                    ? `<strong>✔ Discount: ${(discountAmount / originalPrice * 100).toFixed(0)}%</strong>` 
                    : `<strong>✔ Discount: No Discount</strong>`;

              
                    const productCard = `
                    <div class="col">
                        <div class="card h-100 shadow-sm border-light rounded">
                        
                            <!-- Bootstrap Carousel for Multiple Images -->
                            <div id="carousel-${product.id}" class="carousel slide" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    ${JSON.parse(product.images || '[]').map((image, index) => `
                                        <div class="carousel-item ${index === 0 ? 'active' : ''}">
                                            <img src="/storage/${image}" class="d-block w-100 product-image" 
                                                 alt="${product.name}" style="height: 200px; object-fit: cover;" 
                                                 data-category-id="${product.category_id}">
                                        </div>
                                    `).join('')}
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#carousel-${product.id}" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                </button>
                            </div>

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

                                <!-- Rating Stars -->
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
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".category-image").forEach(img => {
        img.addEventListener("click", function () {
            const categoryId = this.getAttribute("data-category-id");
            loadCategoryImages(categoryId);
        });
    });
});

function loadCategoryImages(categoryId) {
    axios.get(`/api/category-images/${categoryId}`)
        .then(response => {
            const images = response.data.images;
            let modalContent = '';

            if (images.length > 0) {
                modalContent = images.map(img => `
                    <div class="col-md-4">
                        <img src="/storage/${img}" class="img-fluid" alt="Category Image" style="height: 150px; object-fit: cover;">
                    </div>
                `).join('');
            } else {
                modalContent = `<p class="text-center">No images available for this category.</p>`;
            }

            document.getElementById("category-images-container").innerHTML = modalContent;
            const categoryModal = new bootstrap.Modal(document.getElementById("categoryImagesModal"));
            categoryModal.show();
        })
        .catch(error => {
            console.error("Error fetching category images:", error);
        });
}



      </script> --}}
      <!-- Modal -->
      <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

      
   </body>
</html>