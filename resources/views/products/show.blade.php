<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
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
      <div class="container mt-4">
        <div class="row g-3 align-items-center justify-content-center">
          <!-- Product Image -->
          <div class="col-md-5">
            <div class="card border-0 shadow-sm p-2">
              <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top img-fluid rounded" alt="{{ $product->name }}">
            </div>
          </div>
          <!-- Product Details -->
          <div class="col-md-5">
            <div class="p-3">
              <h3 class="fw-bold text-dark">{{ $product->name }}</h3>
              <p class="text-muted small">{{ $product->description }}</p>
            </div>
          </div>
        </div>
      </div>
      <!-- Customer Reviews Section -->
      <h3 class="mb-4 text-success fw-bold">Customer Reviews</h3>
      <div class="card shadow-sm p-4 border-0">
        <div id="review-list" class="list-group">
          @foreach ($reviews as $review)
          <div class="list-group-item py-3 border-0">
            <div class="d-flex justify-content-between align-items-center">
              <div>
                <h5 class="mb-1 text-primary fw-bold">
                  {{ $review->user ? $review->user->name : 'Unknown User' }}
                </h5>
                <h6 class="text-secondary">{{ $review->title }}</h6>
                <p class="mb-2 text-muted">{{ $review->review }}</p>
              </div>
              <div>
                <span class="badge bg-success fs-6 p-2 rounded-pill">
                ⭐ {{ $review->rating }}/5
                </span>
              </div>
            </div>
          </div>
          <hr class="my-2 text-muted">
          @endforeach
        </div>
      </div>
      <hr class="my-5">
      <!-- Add Review Form -->
      <div class="card shadow-lg p-5 border-0">
        <h3 class="text-center text-success fw-bold">Share Your Experience</h3>
        <p class="text-center text-muted">Your feedback helps others!</p>
        <form id="review-form">
          <input type="hidden" id="product_id" value="{{ $product->id }}">
          <input type="hidden" id="user_id" value="{{ auth()->user()->id }}">
          <div class="mb-3">
            <label class="form-label fw-bold">Your Name</label>
            <input type="text" id="user_name" name="user_name" class="form-control rounded-pill" value="{{ auth()->user()->name }}" readonly>
          </div>
          <div class="mb-3">
            <label for="title" class="form-label fw-bold">Review Title</label>
            <input type="text" id="title" name="title" class="form-control rounded-pill" placeholder="Give your review a title" required>
          </div>
          <div class="mb-3">
            <label class="form-label fw-bold d-block">Rating</label>
            <div class="d-flex justify-content-right gap-2 rating">
              <input type="radio" name="rating" id="star1" value="1"><label for="star1">⭐</label>
              <input type="radio" name="rating" id="star2" value="2"><label for="star2">⭐</label>
              <input type="radio" name="rating" id="star3" value="3"><label for="star3">⭐</label>
              <input type="radio" name="rating" id="star4" value="4"><label for="star4">⭐</label>
              <input type="radio" name="rating" id="star5" value="5"><label for="star5">⭐</label>
            </div>
          </div>
          <div class="mb-3">
            <label for="review" class="form-label fw-bold">Your Review</label>
            <textarea id="review" name="review" class="form-control rounded-3" rows="4" placeholder="Write your review..." required></textarea>
          </div>
          <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill">
          <i class="fas fa-paper-plane"></i> Submit Review
          </button>
        </form>
      </div>
    </div>
    <script>
      document.getElementById('review-form').addEventListener('submit', function (e) {
          e.preventDefault();
      
          // Get form data safely
          const user_id = document.getElementById('user_id')?.value;
          const product_id = document.getElementById('product_id')?.value;
          const user_name = document.getElementById('user_name')?.value;
          const title = document.getElementById('title')?.value;
          const rating = document.querySelector('input[name="rating"]:checked')?.value;
          const review = document.getElementById('review')?.value;
      
          // Validate required fields
          if (!user_id || !product_id ||!user_name || !title || !rating || !review) {
              alert('Please fill out all fields before submitting.');
              return;
          }
      
          // Prepare data to send to API
          const formData = {
              user_id,
              product_id,
              user_name,
              title,
              rating,
              review
          };
      
          fetch('/api/reviews', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
                  'X-CSRF-TOKEN': '{{ csrf_token() }}'
              },
              body: JSON.stringify(formData)
          })
          .then(response => response.json())
          .then(data => {
              alert(data.message); // Show success message
      
              // Clear form fields
              document.getElementById('title').value = '';
              document.getElementById('review').value = '';
              document.getElementById('user_name').value = '';
              document.querySelectorAll('input[name="rating"]').forEach(radio => radio.checked = false);
      
              // Fetch updated reviews and show on frontend
              fetchReviews();
          })
          .catch(error => console.error('Error submitting review:', error));
      });
      
      // Function to fetch and display reviews
      function fetchReviews() {
          const productId = document.getElementById('product_id').value;
      
          fetch(`/api/reviews/${productId}`)
              .then(response => response.json())
              .then(data => updateReviewList(data.reviews))
              .catch(error => console.error('Error fetching reviews:', error));
      }
      function updateReviewList(reviews) {
      let reviewList = document.getElementById('review-list');
      
      if (!reviewList) {
      console.error('Error: #review-list not found in DOM');
      return;
      }
      
      reviewList.innerHTML = ''; 
      
      reviews.forEach(review => {
      let userName = review.user_name || "Anonymous"; // Use API response directly
      
      let reviewItem = `
          <div class="list-group-item py-3 border-0">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h5 class="mb-1 text-primary fw-bold">${userName}</h5>
                      <h6 class="text-secondary">${review.title}</h6>
                      <p class="mb-2 text-muted">${review.review}</p>
                  </div>
                  <div>
                      <span class="badge bg-success fs-6 p-2 rounded-pill">
                          ⭐ ${review.rating}/5
                      </span>
                  </div>
              </div>
          </div>
          <hr class="my-2 text-muted">
      `;
      reviewList.innerHTML += reviewItem;
      });
      }
      
      // Fetch and display reviews on page load
      document.addEventListener("DOMContentLoaded", fetchReviews);
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