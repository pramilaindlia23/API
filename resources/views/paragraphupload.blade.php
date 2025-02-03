<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paragraph Upload</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
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
    <h1 class="text-center mb-4">Upload Text</h1>

    <!-- Paragraph Upload Form in a Bootstrap Card -->
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-header bg-primary text-white text-center">
            <h4>Upload New Text</h4>
        </div>
        <div class="card-body">
            <form id="upload-form">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Text</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Upload Text</button>
            </form>
        </div>
    </div>

    <!-- Message Display -->
    <div id="message" class="mt-3"></div>

    <!-- Uploaded Paragraphs List -->
    <div class="container mt-5">
        <h2 class="bg-success text-center">Text List</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Content</th>
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
          <h5 class="modal-title" id="editParagraphModalLabel">Edit Text</h5>
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
  
              <button type="submit" class="btn btn-primary w-100">Update Text</button>
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
    const form = document.getElementById('upload-form');
    const messageDiv = document.getElementById('message');
    const paragraphListDiv = document.getElementById('paragraph-list');
    const editForm = document.getElementById('edit-form');
    const editParagraphModal = new bootstrap.Modal(document.getElementById('editParagraphModal'));

   
    function loadParagraphs() {
        fetch('http://127.0.0.1:8000/api/paragraphs')  
            .then(response => response.json())
            .then(paragraphs => {
                let paragraphsHtml = '';
                paragraphs.forEach(paragraph => {
                    paragraphsHtml += `
                        <tr id="paragraph-${paragraph.id}">
                            <td>${paragraph.title}</td>
                            <td>${paragraph.content}</td>
                            <td>
                                <button class="btn btn-warning btn-sm" onclick="editParagraph(${paragraph.id})">Edit</button>
                                <button class="btn btn-danger btn-sm" onclick="deleteParagraph(${paragraph.id})">Delete</button>
                            </td>
                        </tr>
                    `;
                });
                paragraphListDiv.innerHTML = paragraphsHtml;  
            })
            .catch(error => {
                console.error('Error loading paragraphs:', error);
            });
    }

    //  upload new paragraph //
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        fetch('api/paragraph', {  
            method: 'POST',
            body: formData, 
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                messageDiv.innerHTML = `<div class="alert alert-success">${data.message}</div>`;
                loadParagraphs();  
            } else {
                messageDiv.innerHTML = `<div class="alert alert-danger">Error: ${data.errors}</div>`;
            }
        })
        .catch(error => {
            messageDiv.innerHTML = `<div class="alert alert-danger">An error occurred: ${error.message}</div>`;
        });
    });

    //  the edit form in a modal //
    function editParagraph(id) {
        fetch(`api/paragraphs/${id}`)
            .then(response => response.json())
            .then(paragraph => {
                document.getElementById('edit-paragraph-id').value = paragraph.id;
                document.getElementById('edit-title').value = paragraph.title;
                document.getElementById('edit-content').value = paragraph.content;
                editParagraphModal.show();  
            })
            .catch(error => {
                console.error('Error fetching paragraph for edit:', error);
            });
    }

    //  editing a paragraph //
    editForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const paragraphId = document.getElementById('edit-paragraph-id').value;
        const updatedTitle = document.getElementById('edit-title').value;
        const updatedContent = document.getElementById('edit-content').value;

        fetch(`api/paragraphs/${paragraphId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                title: updatedTitle,
                content: updatedContent,
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                editParagraphModal.hide(); 
                loadParagraphs();  
            }
        })
        .catch(error => {
            console.error('Error updating paragraph:', error);
        });
    });

    // Function to delete a paragraph
    function deleteParagraph(id) {
        if (confirm('Are you sure you want to delete this paragraph?')) {
            fetch(`http://127.0.0.1:8000/api/paragraphs/${id}`, {
                method: 'DELETE',  
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    loadParagraphs(); 
                }
            })
            .catch(error => {
                console.error('Error deleting paragraph:', error);
            });
        }
    }

    // Initial load of paragraphs when page is loaded
    document.addEventListener('DOMContentLoaded', function() {
        loadParagraphs();
    });
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
