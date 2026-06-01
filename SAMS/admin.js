// ✅ SAMS - ADMIN JAVASCRIPT
// Ganap na pareho sa style ng iba

// Dark Mode - Naaalala kahit lumipat ng page o mag-refresh
const darkToggle = document.getElementById('darkmode');

// I-check kung naka-dark mode na dati
if(localStorage.getItem('darkMode') === 'enabled') {
    document.body.classList.add('dark-mode');
    if(darkToggle) darkToggle.checked = true;
}

// Toggle kapag pinindot
if(darkToggle) {
    darkToggle.addEventListener('change', function() {
        if(this.checked) {
            document.body.classList.add('dark-mode');
            localStorage.setItem('darkMode', 'enabled');
        } else {
            document.body.classList.remove('dark-mode');
            localStorage.setItem('darkMode', 'disabled');
        }
    });
}

// ✅ Confirm Logout
function confirmLogout() {
    if(confirm('⚠️ Are you sure you want to log out of your account?')) {
        window.location.href = 'logout.php';
    }
}

// ✅ Smooth Transition
document.body.style.transition = 'all 0.3s ease';