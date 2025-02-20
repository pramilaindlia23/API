<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Links</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <h2 class="text-center mb-4">Video Links Management</h2>
    
    <!-- Add Video Form -->
    {{-- <div class="card shadow p-4 mb-4">
        <h4>Add New Video</h4>
        <form id="video-form">
            <div class="mb-3">
                <label for="title" class="form-label">Video Title</label>
                <input type="text" id="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="platform" class="form-label">Platform</label>
                <select id="platform" class="form-control" required>
                    <option value="YouTube">YouTube</option>
                    <option value="Instagram">Instagram</option>
                    <option value="Facebook">Facebook</option>
                    <option value="Twitter">Twitter</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="url" class="form-label">Video URL</label>
                <input type="url" id="url" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Add Video</button>
        </form>
    </div> --}}

    <!-- Video List -->
    <div class="card shadow p-4">
        <h4>Video List</h4>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Platform</th>
                    <th>Link</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="video-list">
                <!-- Video items will be inserted here dynamically -->
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        fetchVideos();

        // Handle form submission
        document.getElementById('video-form').addEventListener('submit', function (e) {
            e.preventDefault();

            const title = document.getElementById('title').value;
            const platform = document.getElementById('platform').value;
            const url = document.getElementById('url').value;

            fetch('api/video-links', {  // Replace with your actual API endpoint
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ title, platform, url })
            })
            .then(response => response.json())
            .then(data => {
                alert('Video added successfully!');
                fetchVideos();  // Refresh the list
                document.getElementById('video-form').reset();
            })
            .catch(error => console.error('Error adding video:', error));
        });

        // Fetch video list
        function fetchVideos() {
            fetch('api/video-links') // Replace with your API
                .then(response => response.json())
                .then(videos => {
                    const videoList = document.getElementById('video-list');
                    videoList.innerHTML = ''; // Clear list

                    videos.forEach((video, index) => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${index + 1}</td>
                            <td>${video.title}</td>
                            <td>${video.platform}</td>
                            <td><a href="${video.url}" target="_blank">Watch</a></td>
                            <td><button class="btn btn-danger btn-sm" onclick="deleteVideo(${video.id})">Delete</button></td>
                        `;
                        videoList.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching videos:', error));
        }

        // Delete video
        window.deleteVideo = function (videoId) {
            if (confirm('Are you sure you want to delete this video?')) {
                fetch(`api/video-links/${videoId}`, {  // Replace with your API
                    method: 'DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    alert('Video deleted successfully!');
                    fetchVideos();  // Refresh the list
                })
                .catch(error => console.error('Error deleting video:', error));
            }
        }
    });
</script>

</body>
</html>
