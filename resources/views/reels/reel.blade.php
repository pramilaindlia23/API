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
    <!-- Video Upload Form -->
    <h2 class="text-center mb-4">Upload Reel</h2>
    <div class="card shadow-sm" style="max-width: 600px; margin: 0 auto;">
        <div class="card-body">
            <form id="upload-form" method="post" enctype="multipart/form-data">
                <label for="reel">Select Video:</label>
                <input type="file" id="reel" name="reel" accept="video/*" required>
                <button type="submit">Upload Video</button>
            </form>
        </div>
    </div>

    <!-- Display Message -->
    <div id="message" class="mt-3"></div>
    <h1 class="text-center mb-4">Reel List</h1>

    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title">Reel Videos</h4>
            <div id="reels-container">
                <!-- Reels will be displayed here -->
            
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Reel</th>
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
</div>
<!-- Bootstrap 4 JS -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
    // Upload video
    document.getElementById('upload-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData();
        const reelFile = document.getElementById('reel').files[0];

        if (!reelFile) {
            alert('Please select a video file.');
            return;
        }

        formData.append('reel', reelFile);

        // Use Fetch API to send a POST request
        fetch('/reels', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Reel uploaded successfully!') {
                alert('Video uploaded successfully!');
                loadReels(); // Reload the list of reels after upload
            } else {
                alert('Error uploading video: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error uploading video:', error);
            alert('An error occurred while uploading the video.');
        });
    });

    // Load list of uploaded videos
    function loadReels() {
        fetch('/reels')
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('reels-container');
            container.innerHTML = ''; // Clear previous list

            data.forEach(reel => {
                const reelDiv = document.createElement('div');
                reelDiv.classList.add('reel');

                reelDiv.innerHTML = `
                    <h3>${reel.filename}</h3>
                    <video width="300" controls>
                        <source src="/storage/${reel.path}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                    <p>Duration: ${reel.duration} seconds</p>
                    <button onclick="deleteReel(${reel.id})">Delete</button>
                    <button onclick="editReel(${reel.id})">Edit</button>
                `;
                container.appendChild(reelDiv);
            });
        })
        .catch(error => {
            console.error('Error loading reels:', error);
        });
    }

    // Delete a video
    function deleteReel(id) {
        if (!confirm('Are you sure you want to delete this video?')) {
            return;
        }

        fetch(`/reels/${id}`, {
            method: 'DELETE',
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Video deleted successfully') {
                alert('Video deleted successfully!');
                loadReels(); // Reload the list of reels after deletion
            } else {
                alert('Error deleting video: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting video:', error);
        });
    }

    // Edit a video (open an edit form or perform an update)
    function editReel(id) {
        const newTitle = prompt("Enter new title for the video:");
        if (!newTitle) return;

        const categoryId = prompt("Enter category ID for the video:");

        fetch(`/reels/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                title: newTitle,
                category_id: categoryId,
            }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.message === 'Video updated successfully!') {
                alert('Video updated successfully!');
                loadReels(); // Reload the list of reels after updating
            } else {
                alert('Error updating video: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error updating video:', error);
        });
    }

    // Load reels when the page is loaded
    window.onload = function() {
        loadReels();
    };
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
