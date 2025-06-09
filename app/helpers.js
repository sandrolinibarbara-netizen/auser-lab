function showTooltip(icon) {
    const id = document.getElementById('tooltip-' + icon.id);
    id.classList.remove('d-none');
}
function hideTooltip(icon) {
    const id = document.getElementById('tooltip-' + icon.id);
    id.classList.add('d-none');
}

function disableZoom() {
    zoomPw.value = '';
    zoomPw.setAttribute('disabled', 'disabled');
    zoomMeeting.value = '';
    zoomMeeting.setAttribute('disabled', 'disabled');
    zoomBox.style.backgroundColor = '#e9ecef';
    document.getElementById('zoom-title').style.color = '#6c757d';
    document.getElementById('crea-zoom').classList.add('d-none');

}
function disableLink() {
    streamLink.value = '';
    streamLink.setAttribute('disabled', 'disabled');
    linkBox.style.backgroundColor = '#e9ecef';
    document.getElementById('link-title').style.color = '#6c757d';
    document.getElementById('crea-link').classList.add('d-none');
}
function disableVideo() {
    document.getElementById('video-fileInput').setAttribute('disabled', 'disabled');
    videoBox.style.backgroundColor = '#e9ecef';
    document.getElementById('video-title').style.color = '#6c757d';
    document.getElementById('genera-video').classList.add('d-none');
}
function enableZoom() {
    zoomPw.removeAttribute('disabled');
    zoomMeeting.removeAttribute('disabled');
    document.getElementById('zoom-title').style.color = '#000';
    zoomBox.style.backgroundColor = '#fff';
    document.getElementById('crea-zoom').classList.remove('d-none');
}
function enableLink() {
    streamLink.removeAttribute('disabled');
    document.getElementById('link-title').style.color = '#000';
    linkBox.style.backgroundColor = '#fff';
    document.getElementById('crea-link').classList.remove('d-none');
}
function enableVideo() {
    document.getElementById('video-fileInput').removeAttribute('disabled');
    document.getElementById('video-title').style.color = '#000';
    videoBox.style.backgroundColor = '#fff';
    document.getElementById('genera-video').classList.remove('d-none');
}
