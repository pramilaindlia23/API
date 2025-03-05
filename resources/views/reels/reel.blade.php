<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reels</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
    href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
    rel="stylesheet">
    <!-- Custom styles for this template-->
<link href="assets/css/sb-admin-2.min.css" rel="stylesheet">
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
        <!-- Video Upload Form -->
        <h2 class="text-center mb-4">Upload Video Reel</h2>
        <div class="card shadow-lg" style="max-width: 600px; margin: 0 auto; border-radius: 10px;">
            <div class="card-body">
                <form id="upload-form" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="reel" class="h5">Select Reel (Video):</label>
                        <input type="file" id="reel" name="reel" accept="video/*,audio/*" class="form-control" required>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-success w-100 btn-lg mt-3">Upload Reel</button>
                    </div>
                </form>
            </div>
        </div>
    
        <!-- Display Message -->
        <div id="message" class="mt-3 text-center"></div>
    
        <!-- List of Reels -->
        <h2 class="text-center my-4">Uploaded Reels</h2>
    
        <div class="card shadow-lg mb-4">
            <div class="card-body">
                <h4 class="card-title">Reel Videos</h4>
                <div id="reels-container">
                    <!-- Reels will be displayed here -->
                    <table class="table table-bordered table-striped">
                        <thead class="thead-dark">
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Reel</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="video-list">
                            <!-- Videos will be listed here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- Optional: Add a footer to enhance the design -->
<script>

 document.getElementById('upload-form').addEventListener('submit', function(event) {
    event.preventDefault();

    const formData = new FormData();
    const reelFile = document.getElementById('reel').files[0];

    if (!reelFile) {
        alert('Please select a video or audio file.');
        return;
    }

    formData.append('reel', reelFile);

    fetch('/api/reels', {  
        method: 'POST',
        body: formData,
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Reel uploaded successfully!') {
            alert('Reel uploaded successfully!');
            loadReels(); 
        } else {
            alert('Error uploading reel: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error uploading reel:', error);
        alert('An error occurred while uploading the reel.');
    });
});

function loadReels() {
    fetch('/api/reels') 
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('video-list');
        container.innerHTML = '';  

        data.forEach((reel, index) => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td>${index + 1}</td>
                <td>${reel.filename}</td>
                <td>
                    <video width="150" controls>
                        <source src="/storage/${reel.path}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </td>
                <td>
                    <button class='btn btn-danger' onclick="deleteReel(${reel.id})">Delete</button>
                </td>
            `;
            container.appendChild(row);
        });
    })
    .catch(error => {
        console.error('Error loading reels:', error);
        alert('An error occurred while loading the reels.');
    });
}

// Function to delete a reel
function deleteReel(id) {
    if (!confirm('Are you sure you want to delete this reel?')) {
        return;
    }

    fetch(`/api/reels/${id}`, {  
        method: 'DELETE',
    })
    .then(response => response.json())
    .then(data => {
        if (data.message === 'Reel deleted successfully') {
            alert('Reel deleted successfully!');
            loadReels();  
        } else {
            alert('Error deleting reel: ' + data.message);
        }
    })
    .catch(error => {
        console.error('Error deleting reel:', error);
    });
}

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