<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Audio</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">

        <div class="card shadow-sm mx-auto" style="max-width: 600px;">
            <div class="card-body">
                <h1 class="text-center mb-4">Upload Audio File</h1>
                <form id="upload-form">
                    <div class="form-group">
                        <label for="title">Audio Title:</label>
                        <input type="text" name="title" id="title" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="audio">Choose Audio File:</label>
                        <input type="file" name="audio" id="audio" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success btn-block">Upload</button>
                </form>
            </div>
        </div>

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
                                <th>Title</th>
                                <th>Audio Preview</th>
                            </tr>
                        </thead>
                        <tbody id="audio-list"></tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    {{-- <script>
        document.getElementById('upload-form').addEventListener('submit', function(event) {
            event.preventDefault();

            const formData = new FormData();
            formData.append('title', document.getElementById('title').value);
            formData.append('audio', document.getElementById('audio').files[0]);

            fetch('api/audio', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(() => {
                alert('Audio uploaded successfully!');
                fetchAudioFiles();
            })
            .catch(error => console.error('Error uploading audio:', error));
        });

        function fetchAudioFiles() {
            fetch('api/audio')
                .then(response => response.json())
                .then(data => {
                    const audioList = document.getElementById('audio-list');
                    audioList.innerHTML = '';

                    data.forEach((audio, index) => {
                        const row = document.createElement('tr');

                        const cellIndex = document.createElement('td');
                        cellIndex.textContent = index + 1;

                        const cellTitle = document.createElement('td');
                        cellTitle.textContent = audio.title;

                        const cellPreview = document.createElement('td');
                        const audioElement = document.createElement('audio');
                        audioElement.controls = true;
                        audioElement.src = 'storage/' + audio.path;
                        cellPreview.appendChild(audioElement);

                        row.appendChild(cellIndex);
                        row.appendChild(cellTitle);
                        row.appendChild(cellPreview);
                        audioList.appendChild(row);
                    });
                })
                .catch(error => console.error('Error fetching audio files:', error));
        }

        window.onload = fetchAudioFiles;
    </script> --}}

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById('upload-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData();
        formData.append('title', document.getElementById('title').value);
        formData.append('audio', document.getElementById('audio').files[0]);

        axios.post('/api/audio', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            }
        })
        .then(response => {
            alert('Audio uploaded successfully!');
            fetchAudioFiles();
        })
        .catch(error => {
            console.error('Error uploading audio:', error);
            alert('Error uploading audio');
        });
    });

    function fetchAudioFiles() {
        axios.get('/api/audio')
        .then(response => {
            const audioList = document.getElementById('audio-list');
            audioList.innerHTML = '';

            response.data.forEach((audio, index) => {
                const row = document.createElement('tr');

                const cellIndex = document.createElement('td');
                cellIndex.textContent = index + 1;

                const cellTitle = document.createElement('td');
                cellTitle.textContent = audio.title;

                const cellPreview = document.createElement('td');
                const audioElement = document.createElement('audio');
                audioElement.controls = true;
                audioElement.src = '/storage/' + audio.path;
                cellPreview.appendChild(audioElement);

                row.appendChild(cellIndex);
                row.appendChild(cellTitle);
                row.appendChild(cellPreview);
                audioList.appendChild(row);
            });
        })
        .catch(error => {
            console.error('Error fetching audio files:', error);
        });
    }

    window.onload = fetchAudioFiles;
</script>


</body>
</html>
