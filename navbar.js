document.addEventListener("DOMContentLoaded", function() {
    // 1. Highlight active link based on current page filename
    const path = window.location.pathname;
    const page = path.split("/").pop() || "index.html";
    
    // Highlight active link
    const pageIdMap = {
        'index.html': 'nav-index',
        'about.html': 'nav-about',
        'catalogue.html': 'nav-catalogue',
        'services.html': 'nav-services',
        'contact.html': 'nav-contact',
        'cart.php': 'nav-cart'
    };
    
    const activeId = pageIdMap[page];
    if (activeId) {
        const activeLink = document.getElementById(activeId);
        if (activeLink) activeLink.classList.add("active");
    }

    // 2. Fetch session data to update cart count and auth links
    fetch("get_session.php")
        .then(response => response.json())
        .then(data => {
            // Update cart count
            const cartLink = document.getElementById("nav-cart");
            if (cartLink) {
                cartLink.textContent = `Cart (${data.cart_count})`;
            }

            // Append auth links
            const navMenu = document.getElementById("navMenu");
            if (navMenu) {
                if (data.logged_in) {
                    // Create Dashboard/Orders link
                    const dashboardLi = document.createElement("li");
                    if (data.role === 'admin') {
                        dashboardLi.innerHTML = `<a href="admin_dashboard.php" id="nav-dashboard">Admin Dashboard</a>`;
                    } else {
                        dashboardLi.innerHTML = `<a href="my_orders.php" id="nav-orders">My Orders</a>`;
                    }
                    navMenu.appendChild(dashboardLi);

                    // Create Logout link
                    const logoutLi = document.createElement("li");
                    logoutLi.innerHTML = `<a href="logout.php" class="logout-btn">Logout (${data.username})</a>`;
                    navMenu.appendChild(logoutLi);
                } else {
                    // Create Login link
                    const loginLi = document.createElement("li");
                    loginLi.innerHTML = `<a href="login.php" id="nav-login">Login</a>`;
                    navMenu.appendChild(loginLi);

                    // Create Register link
                    const registerLi = document.createElement("li");
                    registerLi.innerHTML = `<a href="register.php" id="nav-register">Register</a>`;
                    navMenu.appendChild(registerLi);
                }
                
                // Highlight dynamic elements if currently active
                if (page === 'admin_dashboard.php' && document.getElementById("nav-dashboard")) {
                    document.getElementById("nav-dashboard").classList.add("active");
                }
                if (page === 'my_orders.php' && document.getElementById("nav-orders")) {
                    document.getElementById("nav-orders").classList.add("active");
                }
                if (page === 'login.php' && document.getElementById("nav-login")) {
                    document.getElementById("nav-login").classList.add("active");
                }
                if (page === 'register.php' && document.getElementById("nav-register")) {
                    document.getElementById("nav-register").classList.add("active");
                }
            }
        })
        .catch(err => console.error("Error updating navigation menu:", err));
});
