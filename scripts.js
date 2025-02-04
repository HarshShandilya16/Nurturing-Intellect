document.getElementById('contact-form').addEventListener('submit', function(event) {
  event.preventDefault();
  alert('Thank you for your message! We will get back to you soon.');
  document.getElementById('contact-form').reset();
});

document.getElementById('login-btn').addEventListener('click', function() {
  document.getElementById('login-form').style.display = 'block';
});

document.getElementById('signup-btn').addEventListener('click', function() {
  document.getElementById('signup-form').style.display = 'block';
});

document.querySelectorAll('.close').forEach(function(element) {
  element.addEventListener('click', function() {
      element.parentElement.parentElement.style.display = 'none';
  });
});

window.addEventListener('click', function(event) {
  if (event.target.classList.contains('modal')) {
      event.target.style.display = 'none';
  }
});

document.getElementById('login').addEventListener('submit', function(event) {
  event.preventDefault();
  alert('Login successful!');
  document.getElementById('login-form').style.display = 'none';
});

document.getElementById('signup').addEventListener('submit', function(event) {
  event.preventDefault();
  alert('Signup successful!');
  document.getElementById('signup-form').style.display = 'none';
});
