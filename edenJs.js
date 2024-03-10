var badModal = document.getElementById("badModal");
var goodModal = document.getElementById("goodModal");
const ratings = document.querySelectorAll('.rating');

ratings.forEach(rating => {
  for (let i = 1; i <= 5; i++) {
    const star = document.createElement('span');
    star.classList.add('star');
    star.innerHTML = '★';
    star.addEventListener('click', () => rateQuestion(rating, i));
    rating.appendChild(star);
  }
});

function rateQuestion(rating, value) {
  const stars = rating.querySelectorAll('.star');
  stars.forEach((star, index) => {
    star.classList.toggle('active', index < value);
  });
}

document.getElementById('quiz-container').addEventListener('submit', function (event) {
  event.preventDefault();
  const values = Array.from(ratings).map(rating => {
    const stars = rating.querySelectorAll('.star');
    const activeStars = Array.from(stars).filter(star => star.classList.contains('active')).length;
    if (activeStars === 0) {
      alert('Veuillez évaluer toutes les questions avant de soumettre.');
      throw new Error('Unrated question found');
    }
    return activeStars;
  });

  const average = values.reduce((acc, val) => acc + val, 0) / values.length;



  if (average >= 3.8) {
    setTimeout(() => {
      window.location.href = 'https://fr.trustpilot.com/evaluate/vizit-demenagement.fr';
    }, 2000);
    goodModal.style.display = "block";
  } else {
    setTimeout(() => {
      document.body.innerHTML = '';
      setTimeout(() => {
        window.close();
      }, 1000);
    }, 3000);
    badModal.style.display = "block";
  }
});
function updateValue(spanId, value) {
  document.getElementById(spanId).textContent = value;
}