// TRIGGERS THE APPEARANCE OF ARTICLES WHEN SCROLLING DOWN
document.addEventListener('DOMContentLoaded', function () {
    // Intersection Observer's options
    const options = {
        root: null, // Using window as observation zone
        rootMargin: '0px', // No extra margins
        threshold: 0.2 // Trigger observation when 50% of the element is visible
    };

    // Callback fonction for the Intersection Observer
    const handleIntersection = (entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible'); // Add a class to display the element
                observer.unobserve(entry.target); // Stop observing once the element is displayed
            }
        });
    };

    // Creation of the Intersection Observer
    const observer = new IntersectionObserver(handleIntersection, options);

    // Select all elements with the class 'article' to observe
    const articles = document.querySelectorAll('.article');

    // Add each element to the Intersection Observer
    articles.forEach(article => {
        observer.observe(article);
    });
});

// FIXES THE BRAND WHEN SCROLLING DOWN
document.addEventListener("DOMContentLoaded", function () {
    var brand = document.querySelector(".initial-brand");
    var initialTop = brand.offsetTop;

    window.addEventListener("scroll", function () {
        if (window.pageYOffset > initialTop) {
            brand.style.position = "fixed";
            brand.style.top = "0";
        } else {
            brand.style.position = "static";
            brand.style.top = "auto";
        }
    });
});