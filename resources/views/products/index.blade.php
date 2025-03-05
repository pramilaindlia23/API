<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category</title>
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
                    
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
                          function generateStars(productId, rating) {
                          let starsHTML = '';
                          for (let i = 1; i <= maxStars; i++) {
                              starsHTML += `
                                  <i class="fas fa-star rating-star ${i <= rating ? 'text-warning' : 'text-secondary'}"
                                      data-product-id="${productId}" data-rating="${i}">
                                  </i>`;
                          }
                          return `${starsHTML} <strong>(${rating}/5)</strong>`;
                      }
                          let imageUrl = product.image
                              ? `/storage/${product.image}`
                              : "/storage/default-product.jpg";
      
                          const productCard = `
                              <div class="col">
                                  <div class="card h-100 shadow-sm border-light rounded product-card" data-category-id="${product.category_id}">
                                    <img src="${imageUrl}" class="card-img-top category-image w-50 mx-auto d-block" data-category-id="${product.category_id}" alt="${product.name}">
                                      <div class="card-body">
                                      <p><strong>Category:</strong> ${product.category_name}</p>
                                  </div>
                                  </div>
                              </div>`;
      
                          container.innerHTML += productCard;
                      });
                  }
      
                  renderProducts(products); 
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
                      axios.get('/api/ratings')
                  .then(response => {
                      console.log("All Ratings:", response.data.ratings);
                  })
                  .catch(error => {
                      console.error("Error fetching ratings:", error);
                  });
      
                  console.log("Star clicked! Product ID:", productId, "Rating:", rating);
      
                  const title = prompt("Enter a title for your rating (optional):");
      
                  axios.post('/api/rate-product', { 
                      product_id: productId, 
                      rating: rating, 
                      title: title || ""
                  })
                  .then(response => {
                      if (response.status === 200) {
                          alert(`You rated this product ${rating}/5!`);
      
                          updateRatingUI(productId, rating);
                      } else {
                          alert("Failed to submit rating. Please try again.");
                      }
                  })
                  .catch(error => {
                      console.error("Error submitting rating:", error);
                      alert("Error submitting rating.");
                  });
              }
              });
      
              
              function updateRatingUI(productId, rating) {
                  const ratingContainer = document.querySelector(`.rating-container[data-product-id="${productId}"]`);
      
                  if (ratingContainer) {
                      console.log(`Updating UI for product ${productId} with rating ${rating}/5`);
                      ratingContainer.innerHTML = generateStars(rating);
                  } else {
                      console.error(`Rating container not found for product ID: ${productId}`);
                  }
              }
      
              
              function generateStars(rating) {
                  let starsHTML = '';
                  const maxStars = 5;
      
                  for (let i = 1; i <= maxStars; i++) {
                      starsHTML += `<i class="fas fa-star ${i <= rating ? 'text-warning' : 'text-secondary'}"></i>`;
                  }
                  return `${starsHTML} <strong>(${rating}/5)</strong>`;
              }
                           
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
