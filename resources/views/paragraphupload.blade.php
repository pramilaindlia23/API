<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event</title>
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
            <div class="container d-flex justify-content-center align-items-center min-vh-100">
              <div class="card shadow-lg border-0 rounded-4" style="max-width: 600px; width: 100%;">
                <div class="card-header bg-gradient-primary text-white text-center py-3 rounded-top">
                  <h3 class="fw-bold mb-0">Upload New Event</h3>
                </div>
                <div class="card-body p-4">
                  <form id="upload-form">
                    @csrf
                    <div class="row g-3">
                      <div class="col-12">
                        <label for="title" class="form-label fw-bold">Title</label>
                        <input type="text" class="form-control" id="title" name="title" required>
                      </div>
                      <div class="col-12">
                        <label for="content" class="form-label fw-bold">Event Content</label>
                        <textarea class="form-control" id="content" name="content" rows="4" required></textarea>
                      </div>
                      <div class="col-12">
                        <label for="date" class="form-label fw-bold">Event Date</label>
                        <input type="date" class="form-control" id="date" name="date" required>
                      </div>
                      <div class="col-12">
                        <label for="time" class="form-label fw-bold">Event Time</label>
                        <input type="time" class="form-control" id="time" name="time" required>
                      </div>
                      <div class="col-12">
                        <label for="location" class="form-label fw-bold">Event Location</label>
                        <input type="text" class="form-control" id="location" name="location" required>
                      </div>
                      <div class="col-12 d-grid mt-3">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm">Upload Event</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <!-- Message Display -->
            <div id="message" class="mt-3"></div>
            <!-- Uploaded Paragraphs List -->
            <div class="container mt-5">
              <h2 class="bg-success text-center">Event List</h2>
              <table class="table table-bordered">
                <thead>
                  <tr>
                    <th>Title</th>
                    <th>Content</th>
                    <th>Event Date</th>
                    <th>Event Time</th>
                    <th>Event Location</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="paragraph-list">
                  <!-- Paragraphs will be listed here -->
                </tbody>
              </table>
            </div>
          </div>
          <div class="modal fade" id="editParagraphModal" tabindex="-1" aria-labelledby="editParagraphModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="editParagraphModalLabel">Edit Event</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form id="edit-form">
                    <input type="hidden" id="edit-paragraph-id">
                    <div class="mb-3">
                      <label for="edit-title" class="form-label">Title</label>
                      <input type="text" class="form-control" id="edit-title" required>
                    </div>
                    <div class="mb-3">
                      <label for="edit-content" class="form-label">Content</label>
                      <textarea class="form-control" id="edit-content" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Event</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    </div>
    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
      document.addEventListener("DOMContentLoaded", function () {
          document.getElementById('upload-form').addEventListener('submit', function (e) {
              e.preventDefault();
      
             
              const title = document.getElementById('title')?.value.trim();
              const content = document.querySelector('textarea#content')?.value.trim();  
              const date = document.getElementById('date')?.value.trim();
              const time = document.getElementById('time')?.value.trim();
              const location = document.getElementById('location')?.value.trim();
      
              console.log("Title:", title);
              console.log("Content:", content);
              console.log("Date:", date);
              console.log("Time:", time);
              console.log("Location:", location);
      
              if (!title || !content || !date || !time || !location) {
                  alert('Please fill out all fields before submitting.');
                  return;
              }
      
              const formData = { title, content, date, time, location };
      
              fetch('/api/paragraphs', {
          method: 'POST',
          headers: {
              'Accept': 'application/json',
              'Content-Type': 'application/json'
          },
          body: JSON.stringify(formData)
      })
      .then(response => response.json())
      .then(data => {
          console.log('Response:', data); 
          if (data.errors) {
              console.error("Validation Errors:", data.errors);
              alert("Validation Errors: " + JSON.stringify(data.errors));
          } else if (data.message) {
              alert('Event added successfully!');
              loadParagraphs();
          }
      })
      .catch(error => console.error('Error uploading event:', error));
          });
      });
      
          // Fetch and display upcoming events
          function loadParagraphs() {
          let paragraphListDiv = document.getElementById('paragraph-list');
      
          if (!paragraphListDiv) {
              console.error('Error: #paragraph-list not found in DOM');
              return;
          }
      
          fetch('api/paragraphs')
              .then(response => response.json())
              .then(events => {
                  let eventsHtml = '';
                  events.forEach(event => {
                      eventsHtml += `
                          <tr>
                              <td>${event.title}</td>
                              <td>${event.content}</td>
                              <td>${event.date}</td>
                              <td>${event.time}</td>
                              <td>${event.location}</td>
                              <td>
                                  <button class="btn btn-warning btn-sm" onclick="editParagraph(${event.id})">Edit</button>
                                  <button class="btn btn-danger btn-sm" onclick="deleteParagraph(${event.id})">Delete</button>
                              </td>
                          </tr>
                      `;
                  });
                  paragraphListDiv.innerHTML = eventsHtml;
              })
              .catch(error => console.error('Error loading events:', error));
      }
          // Edit an event
          function editParagraph(id) {
              fetch(`api/paragraphs/${id}`)
                  .then(response => response.json())
                  .then(event => {
                      document.getElementById('edit-paragraph-id').value = event.id;
                      document.getElementById('edit-title').value = event.title;
                      document.getElementById('edit-content').value = event.content;
                      document.getElementById('edit-date').value = event.date;
                      document.getElementById('edit-time').value = event.time;
                      document.getElementById('edit-location').value = event.location;
      
                      new bootstrap.Modal(document.getElementById('editParagraphModal')).show();
                  })
                  .catch(error => console.error('Error fetching event:', error));
          }
      
          // Delete an event
          function deleteParagraph(id) {
              if (confirm('Are you sure you want to delete this event?')) {
                  fetch(`api/paragraphs/${id}`, {
                      method: 'DELETE',
                  })
                  .then(response => response.json())
                  .then(data => {
                      if (data.message) {
                          alert(data.message);
                          loadParagraphs();
                      }
                  })
                  .catch(error => console.error('Error deleting event:', error));
              }
          }
      
          // Load events when page loads
          document.addEventListener('DOMContentLoaded', loadParagraphs);
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