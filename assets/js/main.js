// Simple 3D tilt effect for cards using mouse position
document.addEventListener('mousemove', function (e) {
    const cards = document.querySelectorAll('.card-3d-inner');
    const { innerWidth, innerHeight } = window;
    const rotateY = ((e.clientX / innerWidth) - 0.5) * 10; // -5 to 5
    const rotateX = ((e.clientY / innerHeight) - 0.5) * -10; // -5 to 5

    cards.forEach(card => {
        card.style.transform = `rotateY(${rotateY}deg) rotateX(${rotateX}deg) translateY(-4px)`;
    });
});

// Fade in elements with .fade-in-up class when DOM is ready
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.fade-in-up').forEach((el, index) => {
        el.style.animationDelay = `${index * 80}ms`;
    });
});


