<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Library Book Catalog</title>
  <link rel="stylesheet" href="catalogue.css">
</head>
<body>
  <header>
    <h1>Browse Our Upcoming Books</h1>
  </header>

  <section id="catalog">
    
    <div class="book" data-author="" data-summary="">
      <img src="books1" alt="Book Cover 1">
      <h2>Loading...</h2>
      <button class="prebook-btn">Pre-book</button>
    </div>
    <div class="book" data-author="" data-summary="">
      <img src="books1" alt="Book Cover 2">
      <h2>Loading...</h2>
      <button class="prebook-btn">Pre-book</button>
    </div>
    <div class="book" data-author="" data-summary="">
      <img src="books1" alt="Book Cover 3">
      <h2>Loading...</h2>
      <button class="prebook-btn">Pre-book</button>
    </div>
    <div class="book" data-author="" data-summary="">
      <img src="books1" alt="Book Cover 4">
      <h2>Loading...</h2>
      <button class="prebook-btn">Pre-book</button>
    </div>
    <div class="book" data-author="" data-summary="">
      <img src="books1" alt="Book Cover 5">
      <h2>Loading...</h2>
      <button class="prebook-btn">Pre-book</button>
    </div>
  </section>

  <div id="bookModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <h2 id="modalTitle">Book Title</h2>
      <p id="modalAuthor">Author</p>
      <p id="modalSummary">Summary</p>
    </div>
  </div>

  <a href="community.html" class="floating-btn">Join Community</a>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const catalog = document.getElementById('catalog');

      // Fetch data from the backend and populate book details
      fetch('fetch_books.php')
        .then(response => response.json())
        .then(books => {
          books.forEach((book, index) => {
            const bookElement = document.querySelectorAll('.book')[index];
            if (bookElement) {
              bookElement.querySelector('h2').textContent = book.title;
              bookElement.dataset.author = book.author;
              bookElement.dataset.summary = book.summary;

              bookElement.querySelector('.prebook-btn').onclick = () => preBook(book.title);
            }
          });
        })
        .catch(error => console.error('Error fetching books:', error));

      const modal = document.getElementById('bookModal');
      const modalTitle = document.getElementById('modalTitle');
      const modalAuthor = document.getElementById('modalAuthor');
      const modalSummary = document.getElementById('modalSummary');
      const closeModal = document.getElementsByClassName('close')[0];

      
      document.querySelectorAll('.book').forEach(book => {
        book.addEventListener('click', () => {
          modal.style.display = 'block';
          modalTitle.textContent = book.querySelector('h2').textContent;
          modalAuthor.textContent = `by ${book.dataset.author}`;
          modalSummary.textContent = book.dataset.summary;
        });
      });

      
      closeModal.onclick = function () {
        modal.style.display = 'none';
      };

      window.onclick = function (event) {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      };
    });

    function preBook(bookTitle) {
      // Redirect to prebook.php with the book title as a URL parameter
      window.location.href = `prebook.php?title=${encodeURIComponent(bookTitle)}`;
    }
  </script>

</body>
</html>
