<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Video Category</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div  class="card shadow-sm">
    <div class="card-body">
    <form id="category-form">
        <label for="category_name">Category Name</label>
        <input type="text" id="category_name" name="name" required>
        <button type="submit">Add Category</button>
    </form>
</div>
</div>
    <h1 class="text-center mb-4">Upload Video</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Upload Your Video</h4>
        </div>
        <div class="card-body">
            <form id="upload-form" enctype="multipart/form-data">
                <label for="title">Video Title:</label>
                <input type="text" id="title" name="title" class="form-control" required>

                <label for="category_id">Select Category:</label>
                <select id="category_id" name="category_id" class="form-control" required>
                    <option value="" disabled selected>Select a category</option>
                </select>

                <label for="video">Select Video:</label>
                <input type="file" id="video" name="video" class="form-control-file" accept="video/*" required>

                <button type="submit" class="btn btn-success btn-block mt-3">Upload Video</button>
            </form>
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


<script>
    document.getElementById('category-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const categoryName = document.getElementById('category_name').value;
        
        fetch('http://127.0.0.1:8000/api/videocategory', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name: categoryName,
            }),
        })
        .then(response => response.json())
        .then(data => {
            alert('Category added successfully!');
            console.log(data);
        })
        .catch(error => {
            console.error('Error adding category:', error);
        });
    });
    document.addEventListener('DOMContentLoaded', function () {
        const uploadForm = document.getElementById('upload-form');
        const categorySelect = document.getElementById('category_id');

        // Fetch categories and populate the dropdown
        fetch('http://127.0.0.1:8000/api/categories')
            .then(response => response.json())
            .then(data => {
                data.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.category_name;
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading categories:', error));

        // Handle video upload
        uploadForm.addEventListener('submit', function (e) {
            e.preventDefault();

            const formData = new FormData(uploadForm);

            fetch('http://127.0.0.1:8000/api/upload-video', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                console.log(data);
            })
            .catch(error => console.error('Error uploading video:', error));
        });
    });
</script>

</body>
</html>
