<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Upload Image</h2>
        
        <div id="message"></div> 

        <form id="upload-form" enctype="multipart/form-data">
            @csrf
            <div class="container mt-4">
                <div class="form-group">
                    <label for="add_category" class="form-label">Add Category:</label>
                    <input type="text" class="form-control" id="add_category" name="add_category" placeholder="Enter Image Category" required>
                </div>

                <div class="form-group mt-3">
                    <label for="sel1" class="form-label">Select a Category:</label>
                    <select class="form-select" id="sel1" name="category_name" required>
                        <option value="" disabled selected>Select a category</option>
                        <!-- Categories will be populated dynamically -->
                    </select>
                </div>
            </div>

            <div class="form-group mt-4">
                <label for="image" class="form-label">Select Image:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>

            <button type="submit" class="btn btn-success btn-block mt-3">Upload Image</button>
        </form>

        <h2 class="mt-5">Uploaded Images</h2>
        <div id="uploaded-image" class="row mt-3">
            <!-- Uploaded images will be displayed here -->
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
            console.log('Fetched Images:', images);  
            const uploadedImageDiv = document.getElementById('uploaded-image');
            uploadedImageDiv.innerHTML = '';  

            images.forEach(image => {
                const imageCard = document.createElement('div');
                imageCard.classList.add('col-md-4', 'mb-4');

                imageCard.innerHTML = `
                    <div class="card">
                        <img src="${image.image_url}" class="card-img-top" alt="${image.add_category}">
                        <div class="card-body">
                            <h5 class="card-title">${image.add_category}</h5>
                            <p class="card-text">Category: ${image.category_name}</p>
                            <p class="card-text">Size: ${Math.round(image.file_size / 1024)} KB</p>
                            <p class="card-text">MIME Type: ${image.mime_type}</p>
                        </div>
                    </div>
                `;

                uploadedImageDiv.appendChild(imageCard);
            });
        })
        .catch(error => console.error('Error fetching images:', error));
}
       
        const form = document.getElementById('upload-form');
        const messageDiv = document.getElementById('message');
        const uploadedImageDiv = document.getElementById('uploaded-image');

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
    console.log('Image URL:', data.image_url);  
    if (data.message) {
        messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
        uploadedImageDiv.innerHTML = `
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="${data.image_url}" class="card-img-top" alt="${data.image.add_category}">
                    <div class="card-body">
                        <h5 class="card-title">${data.image.add_category}</h5>
                        <p class="card-text">Size: ${Math.round(data.image.file_size / 1024)} KB</p>
                    </div>
                </div>
            </div>
        `;
    } else {
        messageDiv.innerHTML = `<div class="alert alert-danger">Error: ${data.errors}</div>`;
    }
})

        });
    </script>
</body>
</html>

