let slideIndex = 0;

function showSlides() {
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");
    console.log("Nombre de slides détectées :", slides.length);

    // Masquer toutes les slides
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }


    // Désactiver tous les points
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    // Incrémentation de l'index
    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    // Activer le point correspondant
    dots[slideIndex - 1].className += " active";

    // Réinitialise l'index si on dépasse le nombre d'images
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    // Affiche la slide active
    slides[slideIndex - 1].style.display = "block";
    console.log("Affiche la slide numéro :", slideIndex);

    // Change l'image toutes les 5 secondes
    setTimeout(showSlides, 5000);
}

function currentSlide(n) {
    let slides = document.getElementsByClassName("slide");
    let dots = document.getElementsByClassName("dot");

    // Réinitialise le slideIndex à la valeur choisie
    slideIndex = n;

    // Masquer toutes les slides
    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    // Désactiver tous les points
    for (let i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" active", "");
    }

    // Afficher la slide et activer le point correspondant
    slides[slideIndex - 1].style.display = "block";
    dots[slideIndex - 1].className += " active";
}


// Lancer le diaporama
showSlides();



