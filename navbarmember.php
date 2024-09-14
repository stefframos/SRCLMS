<link rel="stylesheet" type="text/css" href="css/style.css">
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
<nav class="membersidenavbarre">
  <header>
    <div class="membersidenavbarre-image-text">
      <span class="membersidenavbarre-image">
        <img src="logo.png" alt="">
      </span>

      <div class="membersidenavbarre-text logo-text">
        <span class="membersidenavbarre-name">SRC LMS</span>
        <span class="membersidenavbarre-profession">Student</span>
      </div>
    </div>

    <i class='bx bx-chevron-right membersidenavbarre-toggle'></i>
  </header>

  <div class="membersidenavbarre-menu-bar">
    <div class="membersidenavbarre-menu">

      <li class="membersidenavbarre-search-box">
       
      </li>

      <ul class="membersidenavbarre-menu-links">
        <li class="membersidenavbarre-nav-link">
          <a href="member_home.php">
            <i class='bx bx-home-alt membersidenavbarre-icon'></i>
            <span class="membersidenavbarre-text membersidenavbarre-nav-text">Dashboard</span>
          </a>
        </li>

        <li class="membersidenavbarre-nav-link">
          <a href="search_books.php">
            <i class='bx bx-bar-chart-alt-2 membersidenavbarre-icon'></i>
            <span class="membersidenavbarre-text membersidenavbarre-nav-text">Search Books</span>
          </a>
        </li>

        <li class="membersidenavbarre-nav-link">
          <a href="member_borrowed.php">
            <i class='bx bx-bell membersidenavbarre-icon'></i>
            <span class="membersidenavbarre-text membersidenavbarre-nav-text">Borrowed Books</span>
          </a>
        </li>


      </ul>
    </div>

    <div class="membersidenavbarre-bottom-content">
      <li class="">
        <a href="member_logout.php">
          <i class='bx bx-log-out membersidenavbarre-icon'></i>
          <span class="membersidenavbarre-text membersidenavbarre-nav-text">Logout</span>
        </a>
      </li>

      <li class="membersidenavbarre-mode">
        <div class="membersidenavbarre-sun-moon">
          <i class='bx bx-moon membersidenavbarre-icon membersidenavbarre-moon'></i>
          <i class='bx bx-sun membersidenavbarre-icon membersidenavbarre-sun'></i>
        </div>
        <span class="membersidenavbarre-mode-text membersidenavbarre-text">Dark mode</span>

        <div class="membersidenavbarre-toggle-switch">
          <span class="membersidenavbarre-switch"></span>
        </div>
      </li>

    </div>
  </div>

</nav>

<script>
    const body = document.querySelector('body'),
      membersidenavbarre = body.querySelector('.membersidenavbarre'),
      membersidenavbarreToggle = body.querySelector(".membersidenavbarre-toggle"),
      membersidenavbarreSearchBtn = body.querySelector(".membersidenavbarre-search-box"),
      membersidenavbarreModeSwitch = body.querySelector(".membersidenavbarre-toggle-switch"),
      membersidenavbarreModeText = body.querySelector(".membersidenavbarre-mode-text");
    membersidenavbarreToggle.addEventListener("click", () => {
      membersidenavbarre.classList.toggle("close");
    })
    membersidenavbarreSearchBtn.addEventListener("click", () => {
      membersidenavbarre.classList.remove("close");
    })
    membersidenavbarreModeSwitch.addEventListener("click", () => {
      body.classList.toggle("dark");
      if (body.classList.contains("dark")) {
        membersidenavbarreModeText.innerText = "Light mode";
      } else {
        membersidenavbarreModeText.innerText = "Dark mode";
      }
    });
  </script>
