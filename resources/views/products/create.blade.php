<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link
      href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
      rel="stylesheet">
    <!-- Custom styles for this template-->
    {{-- 
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
    --}}
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
          @if(session('success'))
          <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          <div class="container mt-5">
            {{-- 
            <h2 class="text-center mb-5 bg-success text-white p-3 rounded">Create New Product</h2>
            --}}
            <h2 class="text-center mb-4">Add Product Category</h2>
            <div class="card shadow-sm mb-5" style="max-width: 600px; margin: 0 auto;">
              <div class="card-body">
                <form action="{{ route('category.store') }}" method="POST">
                  @csrf
                  <label for="category_name">Category Name</label>
                  <input type="text" id="category_name" name="name" required class="form-control">
                  <button type="submit" class="btn btn-primary mt-3">Add Category</button>
                </form>
              </div>
            </div>
            <h2 class="text-center mb-4">Add New Product</h2>
            <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
              <div class="card-body">
                <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
                  @csrf
                  <label for="name">Product Name</label>
                  <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
                  @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="category_id">Select Category</label>
                  <select id="category_id" name="category_id" class="form-control" required>
                    <option value="" disabled selected>Select a category</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                    </option>
                    @endforeach
                  </select>
                  @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="category_name">Category Name</label>
                  <select id="category_name" name="category_name" class="form-control" required>
                    <option value="" disabled selected>Select a category name</option>
                    @foreach ($categories as $category)
                    <option value="{{ $category->name }}" {{ old('category_name') == $category->name ? 'selected' : '' }}>
                    {{ $category->name }}
                    </option>
                    @endforeach
                  </select>
                  @error('category_name') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="brand_name">Brand Name</label>
                  <input type="text" id="brand_name" name="brand_name" class="form-control" value="{{ old('brand_name') }}" required>
                  @error('brand_name') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="price">Price</label>
                  <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}" required>
                  @error('price') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="discount_code">Discount Code (%)</label>
                  <input type="number" id="discount_code" name="discount_code" class="form-control" value="{{ old('discount_code') }}">
                  @error('discount_code') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="description">Description</label>
                  <textarea id="description" name="description" class="form-control">{{ old('description') }}</textarea>
                  @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="stock">Stock Quantity</label>
                  <input type="number" id="stock" name="stock" class="form-control" value="{{ old('stock') }}" required>
                  @error('stock') <small class="text-danger">{{ $message }}</small> @enderror
                  <label for="image">Product Image</label>
                  <input type="file" name="image" id="image" class="form-control" accept="image/*" required>
                  @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                  <button type="submit" class="btn btn-success w-100 mt-3">Add Product</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <script>
      document.addEventListener('DOMContentLoaded', function () {
          let priceInput = document.getElementById('price');
          let discountCodeInput = document.getElementById('discount_code');
          let discountPercentageDisplay = document.createElement('span');
      
          discountCodeInput.parentNode.appendChild(discountPercentageDisplay);
      
          function updateDiscount() {
              let price = parseFloat(priceInput.value) || 0;
              let discountCode = discountCodeInput.value.trim().toUpperCase();
              let discountPercent = 0;
      
              if (discountCode === 'SAVE10') {
                  discountPercent = 10; 
              } else if (discountCode === 'SAVE20') {
                  discountPercent = 20;
              }
      
              discountPercentageDisplay.textContent = `Discount: ${discountPercent}%`;
          }
      
          discountCodeInput.addEventListener('input', updateDiscount);
          priceInput.addEventListener('input', updateDiscount);
      });
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
    {{-- @include('dashboard.footer') --}}
  </body>
</html>