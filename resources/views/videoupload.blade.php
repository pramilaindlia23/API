<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload</title>
    <!-- Bootstrap 4 CDN -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .video-container {
            margin-top: 30px;
        }
        .video-container video {
            max-width: 100%;
            height: auto;
        }
        .error {
            color: red;
        }
        .card-img-top {
            width: 100%;
            height: 15vw;
            object-fit: cover;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Upload Video</h1>

    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white text-center">
            <h4>Upload Your Video</h4>
        </div>
        <div class="card-body">
            <form id="upload-form" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="title">Title:</label>
                    <input type="text" id="title" name="title" class="form-control" required>
                </div>
                <div class="form-group">
                    <label for="video">Select Video:</label>
                    <input type="file" id="video" name="video" class="form-control-file" accept="video/*" required>
                </div>
                <button type="submit" class="btn btn-success btn-block">Upload Video</button>
            </form>
        </div>
    </div>

    <!-- Message Display -->
    <div id="message" class="mt-3"></div>

    <h2 class="mt-5">Uploaded Videos</h2>
    <div id="videos" class="video-container row">
    </div>
</div>

<!-- Bootstrap JS & Dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    const form = document.getElementById('upload-form');
    const messageDiv = document.getElementById('message');
    const videosDiv = document.getElementById('videos');

    // Function to display uploaded videos in a Bootstrap grid layout
    function displayVideos() {
        fetch('/api/video-list')
            .then(response => response.json())
            .then(data => {
                let videoHtml = '';
                data.forEach(video => {
                    videoHtml += `
                        <div class="col-md-4 mb-4">
                            <div class="card">
                                <video controls class="card-img-top">
                                    <source src="/storage/${video.file_path}" type="${video.mime_type}">
                                    Your browser does not support the video tag.
                                </video>
                                <div class="card-body">
                                    <h5 class="card-title">${video.title}</h5>
                                    <p class="card-text">File Size: ${Math.round(video.file_size / 1024)} KB</p>
                                </div>
                            </div>
                        </div>
                    `;
                });
                videosDiv.innerHTML = videoHtml;
            });
    }

    // Handle form submission for video upload
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(form);
        fetch('/api/upload-video', {
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
                displayVideos(); // Reload videos after successful upload
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">${data.errors}</div>`;
            }
        })
        .catch(error => {
            messageDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${error.message}</div>`;
        });
    });

    // Initially load and display the videos
    displayVideos();
</script>

</body>
</html>
