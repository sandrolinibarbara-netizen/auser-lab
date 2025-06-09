const button = document.getElementById('accordion-button');
const div = document.getElementById('accordion-text');

button.addEventListener('click', function() {
    if(div.classList.contains('overflow-hidden')) {
        div.classList.remove('overflow-hidden');
        div.classList.add('overflow-auto', 'mb-8');
        div.style.maxHeight = '300px';
        button.textContent = "Leggi di meno"
    } else {
        div.classList.remove('overflow-auto', 'mb-8');
        div.classList.add('overflow-hidden');
        div.style.maxHeight = '0';
        button.textContent = "Leggi di più"
    }
})