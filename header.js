(function () {
  fetch('./header/header_status.php', {
    method: 'GET',
    credentials: 'include'
  })
    .then(res => {
      if (!res.ok) {
        throw new Error("尚未登入");
      }
      return res.json();
    })
    .then(data => {
      const userInfo = document.getElementById('user-info');
      const authLink = document.getElementById('auth-link');
      const dropdown = document.getElementById('user-dropdown-menu');

      if (data.loggedIn) {
        userInfo.textContent = `👤 ${data.name}`;
        authLink.textContent = '🚪 登出';
        authLink.href = '/PLAYONE/header/logout.php';
        userInfo.style.display = 'inline-block';
        userInfo.style.cursor = 'pointer';
        dropdown.style.display = 'none';

        // 若是管理員，新增「球場管理」項目（避免重複插入）
        if (data.is_admin === 1 && !dropdown.querySelector('.admin-manage-link')) {
          const manageLi = document.createElement('a');
          manageLi.href = '/PLAYONE/manage_courts.html';
          manageLi.textContent = '✅ 球場管理';
          manageLi.className = 'dropdown-item admin-manage-link';
          dropdown.appendChild(manageLi);
        }

        // 下拉選單控制
        let hideTimeout = null;
        function showDropdown() {
          if (hideTimeout) clearTimeout(hideTimeout);
          dropdown.style.display = 'block';
        }
        function hideDropdown() {
          hideTimeout = setTimeout(() => {
            dropdown.style.display = 'none';
          }, 180);
        }
        userInfo.onclick = function (e) {
          e.stopPropagation();
          dropdown.style.display =
            dropdown.style.display === 'block' ? 'none' : 'block';
        };
        dropdown.onmouseleave = hideDropdown;
        dropdown.onmouseenter = function () {
          if (hideTimeout) clearTimeout(hideTimeout);
        };
        document.addEventListener('click', function (e) {
          if (dropdown.style.display === 'block') {
            dropdown.style.display = 'none';
          }
        });
      } else {
        // 未登入狀態
        userInfo.textContent = '';
        authLink.textContent = '登入';
        authLink.href = '/PLAYONE/login.html';
        userInfo.style.display = 'none';
        if (dropdown) dropdown.style.display = 'none';

        // 移除管理員連結（避免非管理員看到）
        const adminLink = dropdown ? dropdown.querySelector('.admin-manage-link') : null;
        if (adminLink) adminLink.remove();
      }

      // 將 is_admin 狀態設到 window，供 manage_courts.html 判斷
      window.is_admin = (data.is_admin === 1 || data.is_admin === "1"); // 修正：同時判斷數字和字串
    })
    .catch(error => {
      console.warn("未登入或錯誤：", error);
      const authLink = document.getElementById('auth-link');
      const userInfo = document.getElementById('user-info');
      userInfo.textContent = '';
      authLink.textContent = '登入';
      authLink.href = '/PLAYONE/login.html';
      userInfo.style.display = 'none';
      const dropdown = document.getElementById('user-dropdown-menu');
      if (dropdown) dropdown.style.display = 'none';
    });
})();

// 🔸 漢堡列互動
document.addEventListener('DOMContentLoaded', function () {
  var hamburger = document.getElementById('hamburger-btn');
  var nav = document.getElementById('main-nav');
  function updateHamburgerDisplay() {
    if (window.innerWidth <= 480) {
      hamburger.style.display = 'flex';
      // 修正：初始時 nav 應該根據 active 狀態顯示/隱藏
      nav.style.display = nav.classList.contains('active') ? 'flex' : 'none';
    } else {
      hamburger.style.display = 'none';
      nav.style.display = 'flex';
      nav.classList.remove('active');
    }
  }
  updateHamburgerDisplay();
  window.addEventListener('resize', updateHamburgerDisplay);

  if (hamburger && nav) {
    hamburger.addEventListener('click', function (e) {
      nav.classList.toggle('active');
      nav.style.display = nav.classList.contains('active') ? 'flex' : 'none';
      e.stopPropagation();
    });
    document.addEventListener('click', function (e) {
      if (
        window.innerWidth <= 480 &&
        nav.classList.contains('active') &&
        !nav.contains(e.target) &&
        !hamburger.contains(e.target)
      ) {
        nav.classList.remove('active');
        nav.style.display = 'none';
      }
    });
  }
});
