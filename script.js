function acceptCookies() {
    document.getElementById('cookie-banner').style.display = 'none';
    localStorage.setItem('cookieConsent', 'accepted');
}
function rejectCookies() {
    document.getElementById('cookie-banner').style.display = 'none';
    localStorage.setItem('cookieConsent', 'rejected');
}
function customizeCookies() {
    alert('Customization options coming soon!');
}
window.onload = function() {
    if(localStorage.getItem('cookieConsent')) {
        document.getElementById('cookie-banner').style.display = 'none';
    }
};