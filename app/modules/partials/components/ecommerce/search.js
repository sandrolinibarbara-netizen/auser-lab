const root = document.getElementById('root').getAttribute('value');
const form = document.getElementById('form');
const formOnDemand = document.getElementById('form-on-demand')
const addToCartButton = document.getElementById('add-cart-button')
const registerFreeItem = document.getElementById('register-free')
const grid = document.getElementById('courses-events-grid');

const find = window.location.search;
const params = new URLSearchParams(find);
const type = params.get("tag");
const id = params.get("id");

if(form) form.addEventListener('submit', search);
if(formOnDemand) formOnDemand.addEventListener('submit', searchOnDemand);


if(addToCartButton) {
    addToCartButton.addEventListener('click', function(e) {
        console.log(e.target.value)

        $.ajax({
            type: 'POST',
            data: {action: 'addToCart', id: e.target.value},
            url: root + 'app/controllers/EcommerceController.php',
            success: function(data) {
                const parsed = JSON.parse(data);
                console.log(parsed.cart)
                const itemType = parsed.course.split('-')[0];
                const id = parsed.course.split('-')[1];
                Swal.fire({
                    icon: 'success',
                    text: 'Elemento aggiunto al carrello.',
                    showConfirmButton: false
                })
                let url;
                if(itemType === 'c') {
                    url = root + 'home/shop?shop=course&id=' + id
                } else if(itemType === 'e') {
                    url = root + 'home/shop?shop=event&id=' + id
                }

                setTimeout(() => window.location.assign(url), 2000)
            }
        })
    })
}

if(registerFreeItem) {
    registerFreeItem.addEventListener('click', function(e) {
        console.log(e.target.value)

        $.ajax({
            type: 'POST',
            data: {action: 'registerFreeItem', id: e.target.value},
            url: root + 'app/controllers/EcommerceController.php',
            success: function() {
                registerFreeItem.setAttribute('disabled', 'disabled')
                registerFreeItem.textContent = 'Ti sei iscritto a questo evento con successo!'
            }
        })
    })
}
function search(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    $('#courses-events-grid').empty();
    load();
    e.submitter.disabled = false;
}
function searchOnDemand(e) {
    e.preventDefault();
    e.submitter.disabled = true;
    $('#courses-events-grid').empty();
    loadOnDemand();
    e.submitter.disabled = false;
}
function load() {
    const param = $('#search').val();
    $.ajax({
        data: {'param': param, 'action': 'getSearch'},
        type: 'POST',
        url: root + 'app/controllers/EcommerceController.php',
        success: function(data) {
            const parsed = JSON.parse(data)
            console.log(parsed.data);
            generateItems(parsed)
        },
        error: function(error) {
            console.log(error)
        }
    })
}
function loadOnDemand() {
    const param = $('#search').val();
    $.ajax({
        data: {'param': param, 'action': 'getOnDemand'},
        type: 'POST',
        url: root + 'app/controllers/EcommerceController.php',
        success: function(data) {
            const parsed = JSON.parse(data)
            console.log(parsed.data);
            generateOnDemand(parsed)
        },
        error: function(error) {
            console.log(error)
        }
    })
}
function loadTag() {

    let url;
    let action;

    switch(type) {
        case 'category':
            url = root + 'app/controllers/CategoryController.php';
            action = 'getAssociatedCategories';
            break;
        case 'speaker':
            url = root + 'app/controllers/SpeakerController.php';
            action = 'getAssociatedSpeakers';
            break;
        case 'teacher':
            url = root + 'app/controllers/UserController.php';
            action = 'getAssociatedTeachers';
            break;
    }

    const data = {
        action: action,
        id: id
    }

    $.ajax({
        data: data,
        type: 'POST',
        url: url,
        success: function(data) {
            const parsed = JSON.parse(data)
            console.log(parsed.data);
            generateItems(parsed)
        },
        error: function(error) {
            console.log(error)
        }
    })
}
function generateItems(parsed) {
    parsed.data.forEach(el => {

        const idEvent = + el.categoria === 1 ? '?shop=course&id=' + el.id : '?shop=event&id=' + el.id
        const url = root + 'home/shop' + idEvent;
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.classList.add('text-decoration-none')

        const col = document.createElement('div');
        col.classList.add('col-3');
        const card = document.createElement('div');
        card.classList.add('card', 'h-600px');
        const body = document.createElement('div');
        body.classList.add('card-body', 'd-flex', 'flex-column');
        const topContainer = document.createElement('div');
        topContainer.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');
        if(el.categoria === 1) {
            const course = document.createElement('p');
            course.textContent = 'Corso';
            course.classList.add('bg-light-bg', 'rounded-pill', 'fs-5', 'text-black', 'px-2', 'py-1', 'mb-0', 'd-inline-block');
            topContainer.append(course);
        }
        if(el.categoria === 2) {
            const event = document.createElement('p');
            event.textContent = 'Evento';
            event.classList.add('bg-light-bg', 'rounded-pill', 'fs-5', 'text-black', 'px-2', 'py-1', 'mb-0', 'd-inline-block');
            topContainer.append(event);
        }
        const iconsContainer = document.createElement('div');
        iconsContainer.classList.add('d-flex', 'align-items-center', 'justify-content-end', 'gap-2');
        if(el.remoto === 1) {
            const screen = document.createElement('i');
            screen.classList.add('ki-outline', 'ki-screen', 'bg-light-bg', 'rounded-circle', 'fs-5', 'text-black', 'p-2');
            iconsContainer.append(screen);
        }
        if(el.presenza === 1) {
            const presence = document.createElement('i');
            presence.classList.add('ki-outline', 'ki-profile-user', 'bg-light-bg', 'rounded-circle', 'fs-5', 'text-black', 'p-2');
            iconsContainer.append(presence)
        }
        const imgContainer = document.createElement('div');
        imgContainer.classList.add('pb-4');
        const img = document.createElement('img');
        img.classList.add('w-100', 'h-200px','rounded', 'object-fit-cover');
        img.setAttribute('src', root + 'app/assets/uploaded-files/heros-images/' + el.pic);
        const title = document.createElement('h3');
        title.classList.add('text-truncate');
        title.textContent = el.categoria === 1 ? el.corso : el.diretta
        const when = document.createElement('p');
        when.classList.add('mb-0');
        when.textContent = el.data_inizio === '01/01/3000' ? 'Corso on-demand' : el.categoria === 1 ? el.data_inizio + ' - ' + el.data_fine : el.data_inizio + ', ' + el.orario_inizio
        const separatorOne = document.createElement('div');
        separatorOne.classList.add('separator', 'my-4');
        const availability = document.createElement('p');
        availability.classList.add('mb-1');
        const posti = el.posti === 0 ? 'Esauriti' : el.posti
        availability.textContent = el.data_inizio === '01/01/3000' ? '' : 'Posti: ' + posti
        const length = document.createElement('p');
        length.classList.add('mb-1');
        length.textContent = el.categoria === 1 ? 'Lezioni: ' + el.lezioni : 'Durata: ' + el.durata + (el.durata === 1 ? ' ora' : ' ore')

        let modeType;
        switch(el.presenza) {
            case 0:
                modeType = 'Da remoto';
                break;
            case 1:
                if(el.remoto === 0) {
                    modeType = 'In presenza';
                    break;
                } else {
                    modeType = 'Mista';
                    break;
                }
            case 2:
                modeType = 'Da definire'
        }

        const mode = document.createElement('p');
        mode.classList.add('mb-1');
        mode.textContent = 'Modalità: ' + modeType

        const speakers = document.createElement('p');
        speakers.classList.add('mb-1');
        speakers.textContent = el.categoria === 1 ? 'Insegnanti: ' + el.insegnanti.join(", ") : 'Relatori: ' + el.relatori.join(", ")
        const priceContainer = document.createElement('div');
        priceContainer.classList.add('mt-auto');
        const separatorTwo = document.createElement('div');
        separatorTwo.classList.add('separator', 'my-4')
        const price = document.createElement('p');
        price.classList.add('w-100', 'text-end', 'fs-2', 'fw-bold')
        price.textContent = 'Contributo: ' + (el.importo === 0 || !el.importo ? 'gratuito' : '€' + el.importo + ',00')

        topContainer.append(iconsContainer);
        imgContainer.append(img);
        priceContainer.append(separatorTwo, price);
        body.append(topContainer, imgContainer, title, when, separatorOne, availability, length, mode, speakers, priceContainer);
        card.append(body);
        link.append(card);
        col.append(link);
        grid.append(col);
    })
}
function generateOnDemand(parsed) {
    parsed.data.forEach(el => {

        const idEvent = + el.categoria === 1 ? '?shop=course&id=' + el.id : '?shop=event&id=' + el.id
        const url = root + 'home/shop' + idEvent;
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.classList.add('text-decoration-none')

        const col = document.createElement('div');
        col.classList.add('col-3');
        const card = document.createElement('div');
        card.classList.add('card', 'h-600px');
        const body = document.createElement('div');
        body.classList.add('card-body', 'd-flex', 'flex-column');
        const topContainer = document.createElement('div');
        topContainer.classList.add('d-flex', 'justify-content-between', 'align-items-center', 'mb-2');
        if(el.categoria === 1) {
            const course = document.createElement('p');
            course.textContent = 'Corso';
            course.classList.add('bg-light-bg', 'rounded-pill', 'fs-5', 'text-black', 'px-2', 'py-1', 'mb-0', 'd-inline-block');
            topContainer.append(course);
        }
        if(el.categoria === 2) {
            const event = document.createElement('p');
            event.textContent = 'Evento';
            event.classList.add('bg-light-bg', 'rounded-pill', 'fs-5', 'text-black', 'px-2', 'py-1', 'mb-0', 'd-inline-block');
            topContainer.append(event);
        }
        const iconsContainer = document.createElement('div');
        iconsContainer.classList.add('d-flex', 'align-items-center', 'justify-content-end', 'gap-2');
        if(el.remoto === 1) {
            const screen = document.createElement('i');
            screen.classList.add('ki-outline', 'ki-screen', 'bg-light-bg', 'rounded-circle', 'fs-5', 'text-black', 'p-2');
            iconsContainer.append(screen);
        }
        if(el.presenza === 1) {
            const presence = document.createElement('i');
            presence.classList.add('ki-outline', 'ki-profile-user', 'bg-light-bg', 'rounded-circle', 'fs-5', 'text-black', 'p-2');
            iconsContainer.append(presence);
        }
        const imgContainer = document.createElement('div');
        imgContainer.classList.add('pb-4');
        const img = document.createElement('img');
        img.classList.add('w-100', 'h-200px','rounded', 'object-fit-cover');
        img.setAttribute('src', root + 'app/assets/uploaded-files/heros-images/' + el.pic);
        const title = document.createElement('h3');
        title.classList.add('text-truncate');
        title.textContent = el.categoria === 1 ? el.corso : el.diretta;
        const when = document.createElement('p');
        when.classList.add('mb-0');
        when.textContent = el.categoria === 1 ? 'Corso on-demand' : 'Evento on-demand';

        const separatorOne = document.createElement('div');
        separatorOne.classList.add('separator', 'my-4');
        const length = document.createElement('p');
        length.classList.add('mb-1');
        length.textContent = el.categoria === 1 ? 'Lezioni: ' + el.lezioni : '';

        const modeType = 'Da remoto';

        const mode = document.createElement('p');
        mode.classList.add('mb-1');
        mode.textContent = 'Modalità: ' + modeType;

        const speakers = document.createElement('p');
        speakers.classList.add('mb-1');
        speakers.textContent = el.categoria === 1 ? 'Insegnanti: ' + el.insegnanti.join(", ") : 'Relatori: ' + el.relatori.join(", ")
        const priceContainer = document.createElement('div');
        priceContainer.classList.add('mt-auto');
        const separatorTwo = document.createElement('div');
        separatorTwo.classList.add('separator', 'my-4')
        const price = document.createElement('p');
        price.classList.add('w-100', 'text-end', 'fs-2', 'fw-bold')
        price.textContent = 'Contributo: ' + (el.importo === 0 || !el.importo ? 'gratuito' : '€' + el.importo + ',00')

        topContainer.append(iconsContainer);
        imgContainer.append(img);
        priceContainer.append(separatorTwo, price);
        body.append(topContainer, imgContainer, title, when, separatorOne, length, mode, speakers, priceContainer);
        card.append(body);
        link.append(card);
        col.append(link);
        grid.append(col);
    })
}