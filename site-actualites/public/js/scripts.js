document.addEventListener('DOMContentLoaded', function () {
    // Options pour l'Intersection Observer
    const options = {
        root: null, // Utilise la fenêtre comme la zone d'observation
        rootMargin: '0px', // Pas de marges supplémentaires
        threshold: 0.5 // Déclenche l'observation lorsque 50% de l'élément est visible
    };

    // Fonction de callback pour l'Intersection Observer
    const handleIntersection = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible'); // Ajoute une classe pour afficher l'élément
                observer.unobserve(entry.target); // Arrête l'observation une fois l'élément affiché
            }
        });
    };

    // Création de l'Intersection Observer
    const observer = new IntersectionObserver(handleIntersection, options);

    // Sélectionne tous les éléments avec la classe "article" à observer
    const articles = document.querySelectorAll('.article');

    // Ajoute chaque élément à l'Intersection Observer
    articles.forEach(article => {
        observer.observe(article);
    });
});