
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audio Upload & Player</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            text-align: center;
        }

        table {
            width: 80%;
            margin: 20px auto;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #f4f4f4;
        }

        .audio-controls {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        input[type="range"] {
            width: 150px;
        }

        button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

    <h2>Upload Audio</h2>
    <form id="upload-form">
        <input type="text" id="title" placeholder="Enter title" required>
        <input type="text" id="artist_name" placeholder="Artist Name" required>
        <input type="file" id="audio" accept="audio/*" required>
        <button type="submit">Upload</button>
    </form>

    <h2>Audio List</h2>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Artist Name</th>
                <th>Duration</th>
                <th>Player</th>
                <th>Play</th>
            </tr>
        </thead>
        <tbody id="audio-list">
            <!-- Audio list items will be dynamically added here -->
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const API_BASE_URL = window.location.origin + "/api"; 
const STORAGE_BASE_URL = window.location.origin + "/storage/";

function formatDuration(milliseconds) {
    if (isNaN(milliseconds)) return "0:00";
    let totalSeconds = Math.floor(milliseconds / 1000);
    let mins = Math.floor(totalSeconds / 60);
    let secs = totalSeconds % 60;
    return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
}

document.getElementById('upload-form').addEventListener('submit', function (event) {
    event.preventDefault();

    const audioFile = document.getElementById('audio').files[0];
    if (!audioFile) {
        alert("Please select an audio file.");
        return;
    }

    const audioElement = new Audio();
    audioElement.src = URL.createObjectURL(audioFile);

    audioElement.addEventListener('loadedmetadata', function () {
        let durationInMs = Math.floor(audioElement.duration * 1000); 

        const formData = new FormData();
        formData.append('title', document.getElementById('title').value);
        formData.append('artist_name', document.getElementById('artist_name').value);
        formData.append('audio', audioFile);
        formData.append('duration', durationInMs); 

        axios.post(`${API_BASE_URL}/audio`, formData, { 
            headers: { 'Content-Type': 'multipart/form-data' }
        })
        .then(response => {
            alert('Audio uploaded successfully!');
            fetchAudioFiles();
        })
        .catch(error => {
            console.error('Error uploading audio:', error.response ? error.response.data : error);
            alert('Error: ' + JSON.stringify(error.response ? error.response.data : error));
        });
    });
});

function fetchAudioFiles() {
    axios.get(`${API_BASE_URL}/audio`) 
    .then(response => {
        const audioList = document.getElementById('audio-list');
        audioList.innerHTML = '';

        response.data.forEach((audio, index) => {
            const row = document.createElement('tr');

            const cellIndex = document.createElement('td');
            cellIndex.textContent = index + 1;

            const cellTitle = document.createElement('td');
            cellTitle.textContent = audio.title;

            const cellArtist = document.createElement('td');
            cellArtist.textContent = audio.artist_name || "Unknown";

            const cellDuration = document.createElement('td');
            cellDuration.textContent = formatDuration(audio.duration);
            // Convert milliseconds

            const cellPlayer = document.createElement('td');
            const seekBar = document.createElement('input');
            seekBar.type = "range";
            seekBar.min = 0;
            seekBar.max = 100;
            seekBar.value = 0;
            seekBar.style.width = "120px";

            const currentTimeDisplay = document.createElement('span');
            currentTimeDisplay.textContent = "0:00";

            const totalTimeDisplay = document.createElement('span');
            totalTimeDisplay.textContent = `/ ${formatDuration(audio.duration) || "0:00"}`; // Convert milliseconds

            cellPlayer.appendChild(seekBar);
            cellPlayer.appendChild(currentTimeDisplay);
            cellPlayer.appendChild(totalTimeDisplay);

            const cellPlay = document.createElement('td');
            const playButton = document.createElement('button');
            playButton.textContent = "Play";
            playButton.onclick = function () {
                loadAudio(`${STORAGE_BASE_URL}/${audio.path}`, seekBar, currentTimeDisplay, totalTimeDisplay, playButton);
            };
            cellPlay.appendChild(playButton);

            row.appendChild(cellIndex);
            row.appendChild(cellTitle);
            row.appendChild(cellArtist);
            row.appendChild(cellDuration);
            row.appendChild(cellPlayer);
            row.appendChild(cellPlay);
            audioList.appendChild(row);
        });
    })
    .catch(error => {
        console.error('Error fetching audio files:', error);
    });
}

window.onload = fetchAudioFiles;

function loadAudio(audioUrl, seekBar, currentTimeDisplay, totalTimeDisplay, playButton) {
    const audio = new Audio(audioUrl);

    audio.addEventListener("loadedmetadata", function () {
        if (!isNaN(audio.duration)) { // Prevent NaN issues
            seekBar.max = Math.floor(audio.duration * 1000); // Convert to milliseconds
            totalTimeDisplay.textContent = `/ ${formatDuration(audio.duration * 1000)}`; // Convert to MM:SS
        } else {
            totalTimeDisplay.textContent = "/ 0:00";
        }
    });

    audio.play();
    playButton.textContent = "Pause";

    audio.ontimeupdate = function () {
        seekBar.value = Math.floor(audio.currentTime * 1000); // Convert to milliseconds
        currentTimeDisplay.textContent = formatDuration(audio.currentTime * 1000);
    };

    seekBar.addEventListener('input', function () {
        audio.currentTime = seekBar.value / 1000; // Convert milliseconds back to seconds
    });

    playButton.onclick = function () {
        if (audio.paused) {
            audio.play();
            playButton.textContent = "Pause";
        } else {
            audio.pause();
            playButton.textContent = "Play";
        }
    };
}


</script>
</body>
</html>


