<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Category</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Edit Category</h2>

        <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="category_name" value="{{ $category->category_name }}" required>
            </div>

            <div class="mb-3">
                <label for="image" class="form-label">Category Image</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
                @if($category->image)
                    <img src="{{ asset('storage/' . $category->image) }}" class="img-thumbnail" style="width: 80px; margin-top: 10px;">
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Update Category</button>
        </form>
    </div>
</body>
</html>
