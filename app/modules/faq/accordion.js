const button = document.querySelectorAll('.accordion-button');
const div = document.querySelectorAll('.accordion-text');

div.forEach((el, i) => {
    button[i].addEventListener('click', function() {
        if(el.classList.contains('overflow-hidden')) {
            el.classList.remove('overflow-hidden');
            el.classList.add('overflow-auto', 'mb-8');
            el.style.maxHeight = '300px';
            button[i].textContent = "Leggi di meno"
        } else {
            el.classList.remove('overflow-auto', 'mb-8');
            el.classList.add('overflow-hidden');
            el.style.maxHeight = '0';
            button[i].textContent = "Leggi di più"
        }
    })
})
