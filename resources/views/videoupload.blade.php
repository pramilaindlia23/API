<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Category and Upload</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
    <!-- Custom styles for this template-->
<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
 <!-- Sidebar -->
 @include('dashboard.sidebar')
 <div id="content-wrapper" class="d-flex flex-column">
 <div id="content">
    
    @include('dashboard.header')

<div class="container mt-5">
    <!-- Video Category Form -->
    <h2 class="text-center mb-4">Add Video Category</h2>
    <div class="card shadow-sm mb-5" style="max-width: 600px; margin: 0 auto;">
        <div class="card-body">
            <form id="category-form">
                <label for="category_name">Category Name</label>
                <input type="text" id="category_name" name="name" required class="form-control">
                <button type="submit" class="btn btn-primary mt-3">Add Category</button>
            </form>
        </div>
    </div>

    <!-- Video Upload Form -->
    <h2 class="text-center mb-4">Upload Video</h2>
    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-body">
            <form id="upload-form" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="title" class="form-label">Video Title</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Select Category</label>
                    <select id="category_id" name="category_id" class="form-control" required>
                        <option value="" disabled selected>Select a category</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="video" class="form-label">Select Video</label>
                    <input type="file" id="video" name="video" class="form-control-file" accept="video/*" required>
                </div>

                <button type="submit" class="btn btn-success w-100">Upload Video</button>
            </form>
        </div>
    </div>

    <!-- Display Message -->
    <div id="message" class="mt-3"></div>
    <h1 class="text-center mb-4">Video List</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title">Uploaded Videos</h4>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Category</th>
                        <th scope="col">Video</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody id="video-list">
                    <!-- Videos will be listed here -->
                </tbody>
            </table>
        </div>
</div>
</div>
 
</div>
</div>
</div>
<!-- Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
    // Fetch categories to populate dropdown for video upload
    fetchVideoCategories();

    // Add category form submission handler
    const categoryForm = document.getElementById('category-form');
    categoryForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const categoryName = document.getElementById('category_name').value;
        fetch('api/videocats', { // Updated API endpoint
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                name: categoryName
            }),
        })
        .then(response => response.json())
        .then(data => {
            alert('Category added successfully!');
            fetchVideoCategories(); // Re-fetch categories after adding a new one
        })
        .catch(error => {
            console.error('Error adding category:', error);
        });
    });

    // Video upload form submission handler
    const uploadForm = document.getElementById('upload-form');
    uploadForm.addEventListener('submit', function (e) {
        e.preventDefault();

        const formData = new FormData(uploadForm);

        fetch('api/upload-video', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
            } else {
                alert('Error uploading video');
            }
        })
        .catch(error => {
            console.error('Error uploading video:', error);
        });
    });

    // Load the videos from the API
    loadVideos();

    // Fetch and display videos
    function loadVideos() {
        fetch('api/videos')
            .then(response => response.json())  
            .then(videos => {
                const videoList = document.getElementById('video-list');
                videoList.innerHTML = ''; 

                videos.forEach((video, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${index + 1}</td>
                        <td>${video.category.category_name}</td> <!-- Display category -->
                        <td>${video.title}</td> <!-- Display video title -->
                        <td><a href="/storage/${video.video_path}" target="_blank">Watch Video</a></td> <!-- Display video path as link -->
                        <td>
                            <button class="btn btn-danger btn-sm delete-btn" data-video-id="${video.id}">Delete</button>
                        </td>`;
                    videoList.appendChild(row);
                });

                // Attach event listener for delete buttons
                attachDeleteButtonListeners();
            })
            .catch(error => console.error('Error loading videos:', error));
    }

    // Attach event listener for delete buttons
    function attachDeleteButtonListeners() {
        const deleteButtons = document.querySelectorAll('.delete-btn');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function () {
                const videoId = this.getAttribute('data-video-id');
                deleteVideo(videoId);
            });
        });
    }

    // Delete video
    function deleteVideo(videoId) {
        if (confirm('Are you sure you want to delete this video?')) {
            fetch(`api/video/${videoId}`, {
                method: 'DELETE',
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                loadVideos(); 
            })
            .catch(error => console.error('Error deleting video:', error));
        }
    }

    // Fetch categories for the upload form
    function fetchVideoCategories() {
        fetch('api/videocats') // Corrected API endpoint to match controller
            .then(response => response.json())
            .then(categories => {
                console.log("API Response:", categories); // Debugging output

                if (!Array.isArray(categories)) {
                    console.error("Expected an array but got:", categories);
                    return;
                }

                const categorySelect = document.getElementById('category_id');
                categorySelect.innerHTML = '<option value="" disabled selected>Select a category</option>';

                categories.forEach(category => {
                    const option = document.createElement('option');
                    option.value = category.id;
                    option.textContent = category.category_name; 
                    categorySelect.appendChild(option);
                });
            })
            .catch(error => console.error('Error loading video categories:', error));
    }
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
@include('dashboard.footer')
</body>
</html>
