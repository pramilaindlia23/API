<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paragraph Upload</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">
    <h1 class="text-center mb-4">Upload Paragraph</h1>

    <!-- Paragraph Upload Form in a Bootstrap Card -->
    <div class="card shadow-sm mx-auto" style="max-width: 500px;">
        <div class="card-header bg-primary text-white text-center">
            <h4>Upload New Paragraph</h4>
        </div>
        <div class="card-body">
            <form id="upload-form">
                @csrf
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>

                <div class="mb-3">
                    <label for="content" class="form-label">Paragraph</label>
                    <textarea class="form-control" id="content" name="content" rows="5" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary w-100">Upload Paragraph</button>
            </form>
        </div>
    </div>

    <!-- Message Display -->
    <div id="message" class="mt-3"></div>

    <!-- Uploaded Paragraphs List -->
    <h2 class="mt-5">Uploaded Paragraphs</h2>
    <div id="paragraphs-list" class="mt-3">
        
    </div>
</div>

<!-- Bootstrap 5 JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

<script>
    const form = document.getElementById('upload-form');
    const messageDiv = document.getElementById('message');
    const paragraphsListDiv = document.getElementById('paragraphs-list');

    function loadParagraphs() {
        fetch('/api/paragraphs')
            .then(response => response.json())
            .then(paragraphs => {
                let paragraphsHtml = '';
                paragraphs.forEach(paragraph => {
                    paragraphsHtml += `
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5 class="card-title">${paragraph.title}</h5>
                                <p class="card-text">${paragraph.content}</p>
                            </div>
                        </div>
                    `;
                });
                paragraphsListDiv.innerHTML = paragraphsHtml;
            })
            .catch(error => {
                console.error('Error loading paragraphs:', error);
            });
    }

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(form);
        fetch('/api/paragraph', {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
            },
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

    loadParagraphs();
</script>

</body>
</html>
