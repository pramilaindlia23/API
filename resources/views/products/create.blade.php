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
    {{-- <form action="{{ route('product.store') }}" method="POST" id='product-form' enctype="multipart/form-data">
        @csrf --}}
    <!-- Product Category Form -->
{{-- <h2 class="text-center mb-4">Add Product Category</h2>
<div class="card shadow-sm mb-5" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form id="category-form">
            <label for="category_name">Category Name</label>
            <input type="text" id="category_name" name="name" required class="form-control">
            <button type="submit" class="btn btn-primary mt-3">Add Category</button>
        </form>
    </div>
</div>

<!-- Product Upload Form -->
<h2 class="text-center mb-4">Add New Product</h2>
<div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
    <div class="card-body">
        <form id="product-form" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Product Name</label>
                <input type="text" id="name" name="name" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="category_id" class="form-label">Select Category</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="" disabled selected>Select a category</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="stock" class="form-label">Stock Quantity</label>
                <input type="number" id="stock" name="stock" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" id="price" name="price" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="discount_code" class="form-label">Discount Code</label>
                <input type="text" id="discount_code" name="discount_code" class="form-control" placeholder="Enter your discount code">
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description</label>
                <textarea class="form-control" id="description" placeholder="Enter Description" name="description"></textarea>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Product Image</label>
                <input type="file" id="image" name="image" class="form-control-file" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success w-100">Add Product</button>
        </form>
    </div> --}}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const originalPrice = parseFloat(product.price);
        const discountedPrice = parseFloat(product.discounted_price);
        const discountPercentage = ((originalPrice - discountedPrice) / originalPrice * 100).toFixed(2);
function updateDiscount() {
    let price = parseFloat(priceInput.value) || 0;
    let discountCode = discountCodeInput.value;
    let discountPercent = 0;

    
    if (discountCode === 'SAVE10') {
        discountPercent = 10; 
    } else if (discountCode === 'SAVE20') {
        discountPercent = 20;
    } else {
        discountPercent = 0; 
    }

    
    discountPercentageDisplay.textContent = discountPercent + "%"; 
}

discountCodeInput.addEventListener('input', updateDiscount);
priceInput.addEventListener('input', updateDiscount);
});

</script>

    
</body>
</html>






