<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Video Reel</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h1 class="text-center mb-4">Upload Video Reel</h1>

                <!-- Success/Error Alerts -->
                <div id="alert-container"></div>

                <!-- Video Upload Form -->
                <form id="uploadForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="video">Choose Video File:</label>
                        <input type="file" name="video" id="video" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Upload Reel</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Show an alert (success or error)
        function showAlert(message, type) {
            const alertContainer = document.getElementById('alert-container');
            alertContainer.innerHTML = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
        }

        // Handle the form submission (AJAX)
        document.getElementById('uploadForm').addEventListener('submit', function (e) {
            e.preventDefault(); // Prevent normal form submission

            const formData = new FormData(this);
            
            fetch('http://127.0.0.1:8000/api/reels', {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.reel) {
                    showAlert('Video uploaded successfully!', 'success');
                    fetchReels(); 
                } else {
                    showAlert('Failed to upload video.', 'danger');
                }
            })
            .catch(error => {
                showAlert('An error occurred. Please try again.', 'danger');
                console.error('Error:', error);
            });
        });

        // Fetch and display the list of uploaded reels
        function fetchReels() {
            fetch('http://127.0.0.1:8000/api/reels')
                .then(response => response.json())
                .then(data => {
                    const reelList = document.getElementById('reel-list');
                    reelList.innerHTML = '';  // Clear existing list

                    data.forEach((reel) => {
                        const listItem = document.createElement('li');
                        listItem.classList.add('list-group-item');
                        listItem.innerHTML = `
                            <strong>${reel.filename}</strong><br>
                            Duration: ${reel.duration}s
                            <video controls width="300">
                                <source src="${reel.video_url}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        `;
                        reelList.appendChild(listItem);
                    });
                })
                .catch(error => console.error('Error fetching reels:', error));
        }

        // Fetch the reels when the page loads
        window.onload = fetchReels;
    </script>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
