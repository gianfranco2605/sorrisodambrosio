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
});
