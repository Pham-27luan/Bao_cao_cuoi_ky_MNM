document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("registerForm");
    const submitBtn = document.getElementById("submitBtn");

    const phoneInput = document.getElementById("phone");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");
    const confirmInput = document.getElementById("password_confirmation");

    const togglePassword = document.getElementById("togglePassword");
    const toggleConfirmPassword = document.getElementById("toggleConfirmPassword");

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener("click", function () {
            const type = passwordInput.getAttribute("type") === "password" ? "text" : "password";
            passwordInput.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    }

    if (toggleConfirmPassword && confirmInput) {
        toggleConfirmPassword.addEventListener("click", function () {
            const type = confirmInput.getAttribute("type") === "password" ? "text" : "password";
            confirmInput.setAttribute("type", type);
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    }

    const clearErrorOnInput = (input) => {
        if (!input) return;
        input.addEventListener("input", function () {
            this.classList.remove("error");
            const errorDiv = document.querySelector(".error-message");
            if (errorDiv && errorDiv.style.display !== "none") {
                errorDiv.style.display = "none";
            }
        });
    };

    clearErrorOnInput(phoneInput);
    clearErrorOnInput(emailInput);
    clearErrorOnInput(passwordInput);
    clearErrorOnInput(confirmInput);

    if (form && submitBtn) {
        form.addEventListener("submit", function (e) {
            const phone = phoneInput ? phoneInput.value.trim() : "";
            const email = emailInput ? emailInput.value.trim() : "";
            const password = passwordInput ? passwordInput.value : "";
            const confirmPassword = confirmInput ? confirmInput.value : "";

            if (!phone) {
                e.preventDefault();
                showError("Vui lòng nhập số điện thoại");
                if (phoneInput) phoneInput.classList.add("error");
                return false;
            }

            const phoneRegex = /^0\d{9}$/;
            if (!phoneRegex.test(phone)) {
                e.preventDefault();
                showError("Số điện thoại không hợp lệ (bắt đầu bằng 0 và có 10 số)");
                if (phoneInput) phoneInput.classList.add("error");
                return false;
            }

            if (email && !isValidEmail(email)) {
                e.preventDefault();
                showError("Email không hợp lệ");
                if (emailInput) emailInput.classList.add("error");
                return false;
            }

            if (!password) {
                e.preventDefault();
                showError("Vui lòng nhập mật khẩu");
                if (passwordInput) passwordInput.classList.add("error");
                return false;
            }

            if (password.length < 6) {
                e.preventDefault();
                showError("Mật khẩu phải có ít nhất 6 ký tự");
                if (passwordInput) passwordInput.classList.add("error");
                return false;
            }

            if (password !== confirmPassword) {
                e.preventDefault();
                showError("Mật khẩu xác nhận không khớp");
                if (confirmInput) confirmInput.classList.add("error");
                return false;
            }

            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
            submitBtn.dataset.originalText = originalText;

            return true;
        });
    }

    function showError(message) {
        const existingError = document.querySelector(".error-message");
        if (existingError) existingError.remove();

        const errorDiv = document.createElement("div");
        errorDiv.className = "error-message";
        errorDiv.innerHTML = "<strong>⚠️ Lỗi!</strong> " + message;

        const registerBody = document.querySelector(".register-body");
        if (registerBody) {
            registerBody.insertBefore(errorDiv, registerBody.firstChild);
            errorDiv.scrollIntoView({ behavior: "smooth", block: "center" });
        }
    }

    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@([^\s@.,]+\.)+[^\s@.,]{2,}$/;
        return emailRegex.test(email);
    }
});

