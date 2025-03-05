<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Audio</title>
      <!-- Bootstrap 5 CSS -->
      <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
      <link href="{{ asset('assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
      <link
         href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
         rel="stylesheet">
      <!-- Custom styles for this template-->
      <link href="{{ asset('assets/css/sb-admin-2.min.css') }}" rel="stylesheet">
      <link rel="icon" type="image/x-icon" href="https://pbs.twimg.com/profile_images/1625786717935640577/QUQt8syP_400x400.png">
      <meta name="csrf-token" content="{{ csrf_token() }}">

   </head>
   <body id="page-top">
      <div id="wrapper">
      <!-- Sidebar -->
      @include('dashboard.sidebar')
      <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
      @include('dashboard.header')
      <div class="container mt-5">
         <!-- Upload Form -->
         <div class="card shadow-sm">
            <div class="card-header bg-primary text-white text-center">
               <h4>Upload Audio</h4>
            </div>
            <div class="card-body">
               <form id="upload-form">
                  <div class="mb-3">
                     <label for="title" class="form-label">Title</label>
                     <input type="text" class="form-control" id="title" placeholder="Enter title" required>
                  </div>
                  <div class="mb-3">
                     <label for="artist_name" class="form-label">Artist Name</label>
                     <input type="text" class="form-control" id="artist_name" placeholder="Artist Name" required>
                  </div>
                  <div class="mb-3">
                     <label for="audio" class="form-label">Upload Audio</label>
                     <input type="file" class="form-control" id="audio" accept="audio/*" required>
                  </div>
                  <button type="submit" class="btn btn-primary w-100">Upload</button>
               </form>
            </div>
         </div>
         <!-- Audio List -->
         <div class="card shadow-sm mt-4">
            <div class="card-header bg-dark text-white text-center">
               <h4>Audio List</h4>
            </div>
            <div class="card-body">
               <table class="table table-bordered text-center">
                  <thead class="table-dark">
                     <tr>
                        <th>#</th>
                        <th>Title</th>
                        <th>Artist Name</th>
                        <th>Duration</th>
                        <th>Player</th>
                        <th>Play</th>
                        <th>Actions</th>
                     </tr>
                  </thead>
                  <tbody id="audio-list">
                     <!-- Audio list items will be dynamically added here -->
                  </tbody>
               </table>
            </div>
         </div>
      </div>
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
            
            // Handle Audio Upload
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
            
            // Fetch Audio Files
            function fetchAudioFiles() {
                axios.get(`${API_BASE_URL}/audio`) 
                .then(response => {
                    const audioList = document.getElementById('audio-list');
                    audioList.innerHTML = '';
            
                    response.data.forEach((audio, index) => {
                        const row = document.createElement('tr');
                        row.id = `audio-row-${audio.id}`;
            
                        const cellIndex = document.createElement('td');
                        cellIndex.textContent = index + 1;
            
                        const cellTitle = document.createElement('td');
                        cellTitle.textContent = audio.title;
            
                        const cellArtist = document.createElement('td');
                        cellArtist.textContent = audio.artist_name || "Unknown";
            
                        const cellDuration = document.createElement('td');
                        cellDuration.textContent = formatDuration(audio.duration);
            
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
                        totalTimeDisplay.textContent = `/ ${formatDuration(audio.duration) || "0:00"}`;
            
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
            
                        // DELETE BUTTON
                        const cellDelete = document.createElement('td');
                        const deleteButton = document.createElement('button');
                        deleteButton.textContent = "Delete";
                        deleteButton.classList.add("btn", "btn-danger");
                        deleteButton.onclick = function () {
                            deleteAudio(audio.id);
                        };
                        cellDelete.appendChild(deleteButton);
            
                        row.appendChild(cellIndex);
                        row.appendChild(cellTitle);
                        row.appendChild(cellArtist);
                        row.appendChild(cellDuration);
                        row.appendChild(cellPlayer);
                        row.appendChild(cellPlay);
                        row.appendChild(cellDelete);
                        audioList.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching audio files:', error);
                });
            }
            
            // Delete Audio File
            function deleteAudio(audioId) {
               axios.delete(`${API_BASE_URL}/audio/${audioId}`)
               .then(response => {
                  alert(response.data.message);
                  document.getElementById(`audio-row-${audioId}`).remove();
               })
               .catch(error => {
                  console.error("Error deleting audio:", error.response ? error.response.data : error);
                  alert("Error deleting audio.");
               });
            }
            // Load Audio Player
            function loadAudio(audioUrl, seekBar, currentTimeDisplay, totalTimeDisplay, playButton) {
                const audio = new Audio(audioUrl);
                
                audio.addEventListener("loadedmetadata", function () {
                    if (!isNaN(audio.duration)) {
                        seekBar.max = Math.floor(audio.duration * 1000);
                        totalTimeDisplay.textContent = `/ ${formatDuration(audio.duration * 1000)}`;
                    } else {
                        totalTimeDisplay.textContent = "/ 0:00";
                    }
                });
            
                audio.play();
                playButton.textContent = "Pause";
            
                audio.ontimeupdate = function () {
                    seekBar.value = Math.floor(audio.currentTime * 1000);
                    currentTimeDisplay.textContent = formatDuration(audio.currentTime * 1000);
                };
            
                seekBar.addEventListener('input', function () {
                    audio.currentTime = seekBar.value / 1000;
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
            
            // Fetch Audio Files on Load
            window.onload = fetchAudioFiles;
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