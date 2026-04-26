// public/js/login.js

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("loginForm");
    const submitBtn = document.getElementById("submitBtn");
    const phoneInput = document.getElementById("phone");
    const passwordInput = document.getElementById("password");
    const togglePassword = document.getElementById("togglePassword");

    // Hiển thị/ẩn mật khẩu
    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const type =
                passwordInput.getAttribute("type") === "password"
                    ? "text"
                    : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    }

    // Xóa class error khi người dùng nhập
    if (phoneInput) {
        phoneInput.addEventListener("input", function () {
            this.classList.remove("error");
            const errorDiv = document.querySelector(".error-message");
            if (errorDiv) errorDiv.style.display = "none";
        });
    }

    if (passwordInput) {
        passwordInput.addEventListener("input", function () {
            this.classList.remove("error");
            const errorDiv = document.querySelector(".error-message");
            if (errorDiv) errorDiv.style.display = "none";
        });
    }

    // Xử lý submit form
    if (form && submitBtn) {
        form.addEventListener("submit", function (e) {
            const phone = phoneInput ? phoneInput.value.trim() : "";
            const password = passwordInput ? passwordInput.value.trim() : "";

            if (!phone) {
                e.preventDefault();
                showError("Vui lòng nhập số điện thoại");
                if (phoneInput) phoneInput.classList.add("error");
                return false;
            }

            if (!password) {
                e.preventDefault();
                showError("Vui lòng nhập mật khẩu");
                if (passwordInput) passwordInput.classList.add("error");
                return false;
            }

            // Hiển thị trạng thái loading
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML =
                '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            submitBtn.dataset.originalText = originalText;

            return true;
        });
    }

    // Hàm hiển thị lỗi
    function showError(message) {
        const existingError = document.querySelector(".error-message");
        if (existingError) existingError.remove();

        const errorDiv = document.createElement("div");
        errorDiv.className = "error-message";
        errorDiv.innerHTML = "<strong>⚠️ Lỗi!</strong> " + message;

        const loginBody = document.querySelector(".login-body");
        if (loginBody) {
            loginBody.insertBefore(errorDiv, loginBody.firstChild);
            errorDiv.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }
});
