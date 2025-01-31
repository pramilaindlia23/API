<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Audio</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">

        <!-- Card for Upload Form -->
        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h1 class="text-center mb-4">Upload Audio File</h1>
                <form action="{{ url('http://127.0.0.1:8000/api/audio') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="audio">Choose Audio File:</label>
                        <input type="file" name="audio" id="audio" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Upload</button>
                </form>
            </div>
        </div>

        <!-- Card for List of Uploaded Audio Files -->
        <div class="card shadow-sm mt-5 mx-auto" style="max-width: 900px;">
            <div class="card-header">
                <h2 class="text-center mb-0">List of Uploaded Audio Files</h2>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="thead-light">
                            <tr>
                                <th>#</th>
                                <th>Filename</th>
                                <th>Audio Preview</th>
                            </tr>
                        </thead>
                        <tbody id="audio-list">
                            <!-- The list of audio files will be populated here dynamically -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        // Fetch the list of audio files and display them
        function fetchAudioFiles() {
            fetch('http://127.0.0.1:8000/api/audio')
                .then(response => response.json())
                .then(data => {
                    const audioList = document.getElementById('audio-list');
                    audioList.innerHTML = '';  // Clear the list before populating

                    // Loop through the audio data and populate the table
                    data.forEach((audio, index) => {
                        const row = document.createElement('tr');

                        // Create table data for the index, filename, audio player
                        const cellIndex = document.createElement('td');
                        cellIndex.textContent = index + 1;
                        const cellFilename = document.createElement('td');
                        cellFilename.textContent = audio.filename;
                        const cellPreview = document.createElement('td');
                        const audioElement = document.createElement('audio');
                        audioElement.controls = true;
                        audioElement.src = 'http://127.0.0.1:8000/storage/audio/' + audio.filename;
                        cellPreview.appendChild(audioElement);

                        // Append cells to the row
                        row.appendChild(cellIndex);
                        row.appendChild(cellFilename);
                        row.appendChild(cellPreview);
                        audioList.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching audio files:', error));
        }

        // Fetch the audio files when the page is loaded
        window.onload = fetchAudioFiles;
    </script>

</body>
</html>
