// ======= 主题切换 =======
(function () {
    // 创建主题切换按钮区域
    const themeKey = "theme";
    function setTheme(mode) {
      document.body.classList.remove("light", "dark", "auto");
      document.body.classList.add(mode);
      localStorage.setItem(themeKey, mode);
    }
    function getTheme() {
      return localStorage.getItem(themeKey) || "auto";
    }
    // 构建主题切换按钮
    let themeHtml = `
      <div class="fixed--theme">
        <span class="${getTheme() === "dark" ? "is-active" : ""}" data-action-value="dark" title="暗色">
          🌙
        </span>
        <span class="${getTheme() === "light" ? "is-active" : ""}" data-action-value="light" title="亮色">
          ☀️
        </span>
        <span class="${getTheme() === "auto" ? "is-active" : ""}" data-action-value="auto" title="跟随系统">
          🖥️
        </span>
      </div>
    `;
    document.body.insertAdjacentHTML("beforeend", themeHtml);
    document.querySelectorAll(".fixed--theme span").forEach(function (el) {
      el.addEventListener("click", function () {
        if (!el.classList.contains("is-active")) {
          document.querySelectorAll(".fixed--theme span").forEach(s => s.classList.remove("is-active"));
          setTheme(el.dataset.actionValue);
          el.classList.add("is-active");
        }
      });
    });
    // 初始应用主题
    setTheme(getTheme());
  })();
  // ======= 返回顶部按钮与吸顶导航 =======
  (function () {
    // 返回顶部按钮（你需要在页面加一个 .backToTop 按钮）
    const backBtn = document.querySelector(".backToTop");
    if (backBtn) {
      window.addEventListener("scroll", function () {
        if ((window.scrollY || window.pageYOffset) > 200) {
          backBtn.classList.add("is-active");
        } else {
          backBtn.classList.remove("is-active");
        }
      });
      backBtn.addEventListener("click", function () {
        window.scrollTo({
          top: 0,
          behavior: "smooth"
        });
      });
    }
    // 吸顶导航（假设头部有 .site--header）
    const header = document.querySelector(".site--header");
    window.addEventListener("scroll", function () {
      if (window.scrollY > 10) {
        header && header.classList.add("is-active");
      } else {
        header && header.classList.remove("is-active");
      }
    });
  })();