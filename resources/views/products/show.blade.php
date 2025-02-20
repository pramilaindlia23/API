<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <!-- Product Image -->
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top rounded" alt="{{ $product->name }}">
                </div>
            </div>
            
            <!-- Product Details -->
            <div class="col-md-6">
                <div class="p-3">
                    <h2 class="fw-bold">{{ $product->name }}</h2>
                    <p class="text-muted">{{ $product->description }}</p>
                    {{-- <p><strong>Original Price:</strong> <span class="text-decoration-line-through text-muted">${{ number_format($product->price, 2) }}</span></p>
                    <p class="text-success"><strong>Discount: {{ $product->discount_code ? $product->discount_code . "%" : "No Discount" }}</strong></p>
                    <p class="fs-5"><strong>Final Price: ${{ number_format($product->price - ($product->price * $product->discount_code / 100), 2) }}</strong></p> --}}
                    
                    {{-- <button class="btn btn-primary w-100 add-to-cart" data-id="{{ $product->id }}">
                        <i class="fas fa-shopping-cart"></i> Add to Cart
                    </button> --}}
                </div>
            </div>
        </div>
    
        <hr class="my-5">
    
        <!-- Customer Reviews Section -->
        <h3 class="mb-4">Customer Reviews</h3>
        <div class="list-group">
            @forelse ($reviews as $review)
                <div class="list-group-item p-3 shadow-sm mb-2 border-0">
                    <h5 class="fw-bold">{{ $review->customer_name }} <span class="badge bg-success ms-2">{{ $review->rating }}/5</span></h5>
                    <p class="text-muted">{{ $review->review }}</p>
                </div>
            @empty
                <p class="text-muted">No reviews yet. Be the first to review this product!</p>
            @endforelse
        </div>
    
        <hr class="my-5">
    
        <!-- Add Review Form -->
        <h4>Add a Review</h4>
       <!-- Only show form if the user bought the product -->
       <div class="card shadow-sm p-4">
        <h4 class="text-center">Leave a Review</h4>
        <form id="review-form">
            <input type="hidden" id="product_id" value="{{ $product->id }}">
            <input type="hidden" id="user_id" value="{{ auth()->user()->id }}"> <!-- Fetch user ID -->
        
            <div class="mb-3">
                <label class="form-label fw-bold">Your Name</label>
                <input type="text" id="user_name" name="user_name" class="form-control" value="{{ auth()->user()->name }}" readonly>
            </div>
        
            <div class="mb-3">
                <label for="title" class="form-label fw-bold">Title</label>
                <input type="text" id="title" name="title" class="form-control" placeholder="Give your review a title" required>
            </div>
        
            <div class="mb-3">
                <label for="rating" class="form-label fw-bold">Rating</label>
                <select id="rating" name="rating" class="form-select">
                    <option value="5">⭐ 5 - Excellent</option>
                    <option value="4">⭐ 4 - Good</option>
                    <option value="3">⭐ 3 - Average</option>
                    <option value="2">⭐ 2 - Poor</option>
                    <option value="1">⭐ 1 - Bad</option>
                </select>
            </div>
        
            <div class="mb-3">
                <label for="review" class="form-label fw-bold">Review</label>
                <input type="text" id="review" name="review" class="form-control" placeholder="Write your review" required>
            </div>
        
            <button type="submit" class="btn btn-success w-100">
                <i class="fas fa-paper-plane"></i> Submit Review
            </button>
        </form>
    </div>
    {{-- <script>
        document.getElementById('review-form').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = {
        user_id: document.getElementById('user_id').value,
        product_id: document.getElementById('product_id').value,
        review_title: document.getElementById('title').value,
        rating: document.getElementById('rating').value,
        review: document.getElementById('review').value,
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
        alert(data.message);
        location.reload();
    })
    .catch(error => console.error('Error submitting review:', error));
});

    </script> --}}
    

    <script>
        document.getElementById('review-form').addEventListener('submit', function (e) {
            e.preventDefault();
    
            const formData = {
                user_id: document.getElementById('user_id').value,
                product_id: document.getElementById('product_id').value,
                user_name: document.getElementById('user_name').value, // Ensure this is included
                title: document.getElementById('title').value, // Fixed name to match backend
                rating: document.getElementById('rating').value,
                review: document.getElementById('review').value,
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
                alert(data.message);
                location.reload();
            })
            .catch(error => console.error('Error submitting review:', error));
        });
    </script>
        
        {{-- <script>
            document.querySelector("#review-form").addEventListener("submit", function(e) {
                e.preventDefault();
        
                const productId = document.querySelector("#product_id").value;
                const customerName = document.querySelector("#customer_name").value;
                const reviewTitle = document.querySelector("#review_title").value;
                // const content = document.querySelector("#content").value;
                const rating = document.querySelector("#rating").value;
        
                fetch('/api/reviews', {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        customer_name: customerName,
                        title: reviewTitle,
                        // content: content,
                        rating: rating
                    })
                })
                .then(response => response.json())
                .then(data => {
                    alert("Review added successfully!");
                    location.reload();
                })
                .catch(error => console.error("Error adding review:", error));
            });
        </script> --}}
        

    {{-- <div class="container">
        <div class="row">
            <div class="col-md-6">
                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid rounded" alt="{{ $product->name }}">
            </div>
            <div class="col-md-6">
                <h2>{{ $product->name }}</h2>
                <p>{{ $product->description }}</p>
                <p><strong>Original Price:</strong> <span style="text-decoration: line-through;">${{ number_format($product->price, 2) }}</span></p>
                <p class="text-success"><strong>Discount: {{ $product->discount_code ? $product->discount_code . "%" : "No Discount" }}</strong></p>
                <p><strong>Final Price: ${{ number_format($product->price - ($product->price * $product->discount_code / 100), 2) }}</strong></p>
                
                <button class="btn btn-primary add-to-cart" data-id="{{ $product->id }}">Add to Cart</button>
            </div>
        </div>
    
        <hr>
    
        <h3>Customer Reviews</h3>
        <ul class="list-group">
            @foreach ($reviews as $review)
                <li class="list-group-item">
                    <strong>{{ $review->customer_name }}:</strong> {{ $review->content }} 
                    <span class="badge bg-success">{{ $review->rating }}/5</span>
                </li>
            @endforeach
        </ul>
    
        <hr>
    
        <h4>Add a Review</h4>
        <form id="review-form">
            <input type="hidden" id="product_id" value="{{ $product->id }}">
            <div class="mb-3">
                <label for="customer_name" class="form-label">Your Name</label>
                <input type="text" id="customer_name" class="form-control">
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Review</label>
                <textarea id="content" class="form-control"></textarea>
            </div>
            <div class="mb-3">
                <label for="rating" class="form-label">Rating</label>
                <select id="rating" class="form-select">
                    <option value="5">5 - Excellent</option>
                    <option value="4">4 - Good</option>
                    <option value="3">3 - Average</option>
                    <option value="2">2 - Poor</option>
                    <option value="1">1 - Bad</option>
                </select>
            </div>
            <button type="submit" class="btn btn-success">Submit Review</button>
        </form>
    </div>
    
    <script>
        document.querySelector("#review-form").addEventListener("submit", function(e) {
            e.preventDefault();
    
            const productId = document.querySelector("#product_id").value;
            const customerName = document.querySelector("#customer_name").value;
            const content = document.querySelector("#content").value;
            const rating = document.querySelector("#rating").value;
    
            fetch('/api/reviews', {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    product_id: productId,
                    customer_name: customerName,
                    content: content,
                    rating: rating
                })
            })
            .then(response => response.json())
            .then(data => {
                alert("Review added successfully!");
                location.reload();
            })
            .catch(error => console.error("Error adding review:", error));
        });
    </script> --}}
</body>
</html>