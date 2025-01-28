<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">

<!-- Custom styles for this template-->
<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    {{-- Page wrapper --}}
    <div id="wrapper">
     <!-- Sidebar -->
     @include('dashboard.sidebar')
     <div id="content-wrapper" class="d-flex flex-column">
     <div id="content">
        
        @include('dashboard.header')
    <div class="container mt-5">
        <h2 class="text-center mb-4">Add New Category</h2>
    
        <!-- Success message -->
        @if(session('success'))
            <div class="alert alert-success text-center">{{ session('success') }}</div>
        @endif
    
        <!-- Category Form -->
        <div class="card p-4 shadow-sm mb-4" style="max-width: 600px; margin: 0 auto;">
            <form action="{{ route('category.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="category_name" class="form-label">Category Name</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2">Add Category</button>
            </form>
        </div>
    
        <h3 class="mt-5 mb-3 text-center">Upload Image for Category</h3>
    
        <!-- Image Upload Form -->
        <div class="card p-4 shadow-sm mb-5" style="max-width: 600px; margin: 0 auto;">
            <form action="{{ route('uploadImage') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="category_id" class="form-label">Select Category</label>
                    <select class="form-select" id="category_id" name="category_id" required>
                        <option value="" disabled selected>Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>
    
                <div class="mb-3">
                    <label for="image" class="form-label">Category Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
    
                <button type="submit" class="btn btn-primary w-100 py-2">Upload Image</button>
            </form>
        </div>
    
        <!-- Categories Table -->
        <h2 class="text-center mb-4">Image Categories</h2>
    
        <a href="{{ route('category.create') }}" class="btn btn-success mb-2">Add New Image Category</a>
    
        <div class="card shadow-sm" style="max-width: 1000px; margin: 0 auto;">
            <table class="table table-bordered table-striped table-hover">
                <thead class="bg-primary text-white">
                    <tr>
                        <th>#</th>
                        <th>Category Name</th>
                        <th>Image</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($categories as $category)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $category->category_name }}</td>
                            <td>
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" class="img-thumbnail" style="width: 80px;">
                                @else
                                    <span class="text-muted">No Image</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('category.editcategory', $category->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                <form action="{{ route('category.destroy', $category->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>
</div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            fetch('/api/categories')
                .then(response => response.json())
                .then(categories => {
                    const selectElement = document.getElementById('sel1');
                   
                    categories.forEach(category => {
                        const option = document.createElement('option');
                        option.value = category.category_name;
                        option.textContent = category.category_name;
                        selectElement.appendChild(option);
                    });
                })
                .catch(error => console.error('Error fetching categories:', error));


            fetchUploadedImages();
        });

        function fetchUploadedImages() {
            fetch('/api/images')
                .then(response => response.json())
                .then(images => {
                    const uploadedImagesList = document.getElementById('uploaded-images-list');
                    uploadedImagesList.innerHTML = '';

                    images.forEach(image => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td><img src="${image.image_url}" alt="${image.add_category}" class="img-thumbnail" style="width: 80px;"></td>
                            <td>${image.category_name}</td>
                        `;
                        uploadedImagesList.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching images:', error));
        }

        const form = document.getElementById('upload-form');
        const messageDiv = document.getElementById('message');

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(form);

            fetch('/api/upload-image', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                },
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                    fetchUploadedImages();
                } else {
                    messageDiv.innerHTML = `<div class="alert alert-danger">Error: ${data.errors}</div>`;
                }
            });
        });

        function fetchCategories() {
           
        }
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
