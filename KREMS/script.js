document.querySelector('.new-trip-btn').addEventListener('click', function() {
    const globeWrapper = document.querySelector('.globe-wrapper');
    const blackScreen = document.querySelector('.black-screen');
    const button = document.querySelector('.new-trip-btn');
    button.style.display = 'none';
    globeWrapper.classList.add('expand');
    setTimeout(() => {
        blackScreen.classList.add('active');
    }, 750);
});
