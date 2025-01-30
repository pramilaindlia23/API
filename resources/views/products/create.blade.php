
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
</html>