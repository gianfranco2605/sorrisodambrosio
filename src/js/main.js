document.addEventListener("DOMContentLoaded", function () {
  // Your JavaScript code here
  document.querySelectorAll(".ml3").forEach((textWrapper) => {
    var textWrapper = document.querySelector(".ml3");
    textWrapper.innerHTML = textWrapper.textContent.replace(
      /\S/g,
      "<span class='letter'>$&</span>"
    );

    anime
      .timeline({ loop: false })
      .add({
        targets: ".ml3 .letter",
        opacity: [0, 1],
        easing: "easeInOutQuad",
        duration: 1000,
        delay: (el, i) => 150 * (i + 1),
      })
      .add({
        targets: ".ml3",
        opacity: 1,
        duration: 1000,
        easing: "easeOutExpo",
        delay: 1000,
      });
  });
  // Function to check card visibility and add class
  function checkCardVisibility(cards, className, verticalOnly = false) {
    cards.forEach((card, index) => {
      const cardPosition = card.getBoundingClientRect().top;
      const windowHeight = window.innerHeight;
      const visibleThreshold = 1.5; // Adjust this value as needed

      // Calculate the threshold position
      const threshold = windowHeight * visibleThreshold;

      // If a significant portion of the card is within the viewport
      if (!verticalOnly || card.classList.contains("cardY")) {
        if (cardPosition < threshold) {
          setTimeout(() => {
            card.classList.add(className);
          }, index * 400); // Adjust the delay as needed
        }
      }
    });
  }

  // Vertically
  const cardsY = document.querySelectorAll(".cardY");
  checkCardVisibility(cardsY, "showY", true); // Only consider vertical cards

  // Horizontally
  const cardsX = document.querySelectorAll(".cardX");
  checkCardVisibility(cardsX, "showX"); // Regular check for horizontal cards

  // Check again as the user scrolls
  window.addEventListener("scroll", function () {
    checkCardVisibility(cardsY, "showY", true); // Only consider vertical cards
    checkCardVisibility(cardsX, "showX"); // Regular check for horizontal cards
  });
});
