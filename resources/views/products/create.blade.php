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

    <form action="{{ route('product.store') }}" method="POST" id='product-form'  enctype="multipart/form-data">
        @csrf
    
        <div class="mb-3">
            <label for="name" class="form-label">Product Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" placeholder="Enter Product Name" required>
        </div>

        <div class="mb-3">
            <label for="stock" class="form-label">Stock Quantity</label>
            <input type="number" class="form-control" id="stock" name="stock" value="{{ old('stock') }}" placeholder="Enter stock quantity" required>
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
            document.addEventListener('DOMContentLoaded', function () {
                const priceInput = document.getElementById('price');
                const discountCodeInput = document.getElementById('discount_code');
                const discountedPriceInput = document.getElementById('discounted_price');

                function updateDiscount() {
                    let price = parseFloat(priceInput.value) || 0;
                    let discountCode = discountCodeInput.value;
                    let discount = 0;

                    if (discountCode === 'SAVE10') {
                        discount = price * 0.10; 
                    } else if (discountCode === 'SAVE20') {
                        discount = price * 0.20; 
                    }

                    let finalPrice = (price - discount).toFixed(2);
                    discountedPriceInput.value = finalPrice;
                }

                discountCodeInput.addEventListener('input', updateDiscount);
                priceInput.addEventListener('input', updateDiscount);
            });
</script>

    
</body>
</html>






