</div> <!-- container end -->

<footer class="text-center py-4 mt-5 bg-light border-top light-mode">
  <p class="mb-0">&copy; <?php echo date('Y'); ?> Arty-U. All rights reserved.</p>
</footer>

<!-- 🌗 Dark Mode Toggle Switch -->
<div id="themeToggle" class="theme-switch">
  <div class="switch-circle"></div>
  <span class="switch-icon">🌙</span>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/main.js"></script>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const body = document.body;
  const toggle = document.getElementById("themeToggle");
  const icon = toggle.querySelector(".switch-icon");
  const navbar = document.querySelector("nav.navbar");
  const footer = document.querySelector("footer");
  const outlineButtons = document.querySelectorAll(".btn-outline-dark, .btn-outline-light");

  // Load saved theme
  const savedTheme = localStorage.getItem("theme");
  if (savedTheme === "dark-mode") {
    enableDarkMode();
  } else {
    enableLightMode();
  }

  toggle.addEventListener("click", () => {
    if (body.classList.contains("dark-mode")) {
      enableLightMode();
      localStorage.setItem("theme", "light-mode");
    } else {
      enableDarkMode();
      localStorage.setItem("theme", "dark-mode");
    }
  });

  function enableDarkMode() {
    body.classList.replace("light-mode", "dark-mode");
    navbar.classList.remove("navbar-light", "bg-light");
    navbar.classList.add("navbar-dark", "bg-dark");
    footer.classList.add("dark-mode");
    footer.classList.remove("light-mode");
    icon.textContent = "☀️";
    toggle.classList.add("active");

    // Switch buttons
    outlineButtons.forEach(btn => {
      btn.classList.remove("btn-outline-dark");
      btn.classList.add("btn-outline-light");
    });
  }

  function enableLightMode() {
    body.classList.replace("dark-mode", "light-mode");
    navbar.classList.remove("navbar-dark", "bg-dark");
    navbar.classList.add("navbar-light", "bg-light");
    footer.classList.add("light-mode");
    footer.classList.remove("dark-mode");
    icon.textContent = "🌙";
    toggle.classList.remove("active");

    // Switch buttons
    outlineButtons.forEach(btn => {
      btn.classList.remove("btn-outline-light");
      btn.classList.add("btn-outline-dark");
    });
  }
});
</script>

<style>
/* ===== 🌙 DARK MODE BASE COLORS ===== */
:root {
  --bg-light: #f8f9fa;
  --text-light: #212529;
  --card-light: #ffffff;

  --bg-dark: #121212;
  --text-dark: #f1f1f1;
  --card-dark: #1e1e1e;
}

/* ===== BODY & TEXT ===== */
body.light-mode {
  background-color: var(--bg-light);
  color: var(--text-light);
  transition: background-color 0.4s, color 0.4s;
}
body.dark-mode {
  background-color: var(--bg-dark);
  color: var(--text-dark);
  transition: background-color 0.4s, color 0.4s;
}

/* ===== FOOTER ===== */
footer.light-mode {
  background-color: #f8f9fa !important;
  color: #212529 !important;
  border-top: 1px solid #dee2e6;
}
footer.dark-mode {
  background-color: #1e1e1e !important;
  color: #e9ecef !important;
  border-top: 1px solid #333;
}

/* ===== CARDS ===== */
.card {
  background-color: var(--card-light);
  color: var(--text-light);
  transition: background-color 0.4s, color 0.4s, transform 0.3s, box-shadow 0.3s;
}
body.dark-mode .card {
  background-color: var(--card-dark);
  color: var(--text-dark);
  border-color: #333;
}

/* ===== DARK MODE SWITCH ===== */
.theme-switch {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 60px;
  height: 30px;
  background: #ddd;
  border-radius: 50px;
  cursor: pointer;
  transition: background 0.3s ease;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 8px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}
.theme-switch.active {
  background: #333;
}
.switch-circle {
  position: absolute;
  width: 26px;
  height: 26px;
  background: #fff;
  border-radius: 50%;
  top: 2px;
  left: 2px;
  transition: all 0.3s ease;
}
.theme-switch.active .switch-circle {
  left: 32px;
  background: #f1f1f1;
}
.switch-icon {
  position: relative;
  z-index: 2;
  font-size: 16px;
  user-select: none;
  transition: transform 0.3s ease;
}
</style>
</body>
</html>
