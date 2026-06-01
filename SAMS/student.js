const toggle = document.getElementById('darkmode');
const body = document.body;
const sidebar = document.getElementById('sidebar');

// ✅ LOAD DARK MODE SETTING
if(localStorage.getItem('darkMode') === 'enabled'){
    body.classList.add('dark-mode');
    toggle.checked = true;
} else {
    body.classList.remove('dark-mode');
    toggle.checked = false;
}

// ✅ TOGGLE FUNCTIONALITY
toggle.addEventListener('change', () => {
    if(toggle.checked){
        body.classList.add('dark-mode');
        localStorage.setItem('darkMode', 'enabled');
    } else {
        body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', 'disabled');
    }
});

// ✅ LOGOUT FUNCTION
function confirmLogout() {
    if(confirm("Are you sure you want to logout?")) {
        window.location.href = "logout.php";
    }
}

// ✅ OPTIONAL: MENU TOGGLE FOR MOBILE
 function toggleSidebar() {
    sidebar.classList.toggle('active');
}