<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
@endif
<div class="container mt-5">
    <h2 class="text-center mb-5 bg-success text-white p-3 rounded">Create New Product</h2>

    <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
    
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Product Name" required>
        </div>
    
        <div class="mb-3">
            <label for="price" class="form-label">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" placeholder="Enter price">
        </div>
    
        <div class="mb-3">
            <label for="discount_code" class="form-label">Discount Code</label>
            <input type="text" id="discount_code" name="discount_code" class="form-control" placeholder="Enter your discount code">
        </div>
    
        <div class="mb-3">
            <label for="discount_price" class="form-label">Discounted Price</label>
            <input type="text" id="discount_price" class="form-control" readonly placeholder="Discounted Price" style="font-weight: bold; display: none;">
        </div>
    
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" placeholder="Enter Description" name="description">{{ old('description') }}</textarea>
        </div>
    
        <div class="mb-3">
            <label for="image" class="form-label">Product Image</label>
            <input type="file" class="form-control" id="image" name="image">
        </div>
    
        <button type="submit" class="btn btn-success width-100 rounded">Add Product</button>
    </form>
</div>
<script>
    document.getElementById('discount_code').addEventListener('input', function () {
        // Get values
        const originalPrice = parseFloat(document.getElementById('price').value);
        const discountCode = this.value.trim();
        const priceDisplay = document.getElementById('price_display');
        const originalPriceElement = document.getElementById('original_price');
        const discountedPriceElement = document.getElementById('discounted_price');
        const discountPriceField = document.getElementById('discount_price');
        
        // Hide price initially
        priceDisplay.style.display = 'none';
        discountedPriceElement.style.display = 'none';
        discountPriceField.style.display = 'none';

        // Apply discount logic
        let discount = 0;
        if (discountCode === 'DISCOUNT10') {
            discount = 0.10 * originalPrice; // 10% discount
        } else if (discountCode === 'DISCOUNT20') {
            discount = 0.20 * originalPrice; // 20% discount
        }

        if (discount > 0) {
            // Display both original and discounted prices
            const discountedPrice = originalPrice - discount;
            priceDisplay.style.display = 'block';
            originalPriceElement.innerHTML = `<span style="text-decoration: line-through;">$${originalPrice.toFixed(2)}</span>`;
            discountedPriceElement.innerHTML = `$${discountedPrice.toFixed(2)}`;
            discountedPriceElement.style.display = 'inline';

            // Update hidden field for the form to send discounted price
            discountPriceField.value = discountedPrice.toFixed(2);
            discountPriceField.style.display = 'block';
        }
    });
</script>
</body>
</html>







{{-- 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4 text-center">Add a New Product</h2>
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
    
            <div class="card shadow-sm">
                <div class="card-body">
    
                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="name" class="font-weight-bold">Product Name</label>
                        <input type="text" name="name" class="form-control" id="name" placeholder="Enter product name" required>
                    </div>
    
                    <!-- Price -->
                    <div class="form-group">
                        <label for="price" class="font-weight-bold">Price ($)</label>
                        <input type="number" name="price" class="form-control" id="price" placeholder="Enter product price" step="0.01" required>
                    </div>
    
                    <!-- Description -->
                    <div class="form-group">
                        <label for="description" class="font-weight-bold">Description</label>
                        <textarea name="description" class="form-control" id="description" rows="4" placeholder="Enter product description"></textarea>
                    </div>
    
                    <!-- Product Image -->
                    <div class="form-group">
                        <label for="image" class="font-weight-bold">Product Image</label>
                        <input type="file" name="image" class="form-control-file" id="image">
                        <small class="text-muted">Choose a product image (optional).</small>
                    </div>
    
                    <!-- Submit Button -->
                    <div class="form-group text-center">
                        <button type="submit" class="btn btn-success btn-lg w-100">Add Product</button>
                    </div>
    
                </div>
            </div>
        </form>
    </div>
    
    <!-- Optional: Add custom margin for spacing -->
    <style>
        .container {
            max-width: 600px;
        }
        .form-group small {
            display: block;
            margin-top: 5px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html> --}}