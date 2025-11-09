let indice = 0;
mostrarSlides();

function mostrarSlides() {
    const slides = document.querySelectorAll(".slides .imagens");
    slides.forEach(slide => slide.style.display = "none");
    indice++;
    if (indice > slides.length) indice = 1;
    slides[indice - 1].style.display = "block";
    setTimeout(mostrarSlides, 3000); // muda a cada 3s
}
