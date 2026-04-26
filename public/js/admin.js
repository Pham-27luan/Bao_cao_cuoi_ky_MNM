// ==========================================
// 1. Hàm hỗ trợ dùng chung
// ==========================================

function openModal(modalId, title) {
    const modal = document.getElementById(modalId);
    const modalTitle = document.getElementById("modalTitle");

    if (modalTitle && title) modalTitle.innerText = title;
    if (modal) modal.classList.add("show");
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) modal.classList.remove("show");
}

function isTicketsPage() {
    return document.body?.dataset?.page === "admin-tickets";
}

// ==========================================
// 2. Quản lý người dùng
// ==========================================

let deleteUserId = null;
let isEditUserMode = false;

function openCreateUserModal() {
    isEditUserMode = false;

    const modalTitle = document.getElementById("userModalTitle");
    const userForm = document.getElementById("userForm");
    const userId = document.getElementById("userId");
    const password = document.getElementById("password");
    const passwordGroup = document.getElementById("passwordGroup");

    if (modalTitle) modalTitle.innerText = "Thêm người dùng mới";
    if (userForm) userForm.reset();
    if (userId) userId.value = "";
    if (password) {
        password.required = true;
        password.value = "";
        password.placeholder = "Nhập mật khẩu (tối thiểu 6 ký tự)";
    }
    if (passwordGroup) passwordGroup.style.display = "";
    if (userForm) userForm.action = "/admin/users/store";

    document.getElementById("userModal")?.classList.add("show");
}

function editUser(id) {
    isEditUserMode = true;

    const modalTitle = document.getElementById("userModalTitle");
    const userForm = document.getElementById("userForm");
    const userId = document.getElementById("userId");
    const password = document.getElementById("password");
    const passwordGroup = document.getElementById("passwordGroup");

    if (modalTitle) modalTitle.innerText = "Sửa thông tin người dùng";
    if (userId) userId.value = id;
    if (password) {
        password.required = false;
        password.value = "";
        password.placeholder = "Bỏ trống nếu không đổi mật khẩu";
    }
    if (passwordGroup) passwordGroup.style.display = "none";
    if (userForm) userForm.action = `/admin/users/update/${id}`;

    fetch(`/admin/users/get/${id}`)
        .then((response) => response.json())
        .then((data) => {
            const hoten = document.getElementById("hoten");
            const phone = document.getElementById("phone");
            const email = document.getElementById("email");
            const role = document.getElementById("role");

            if (hoten) hoten.value = data.hoten;
            if (phone) phone.value = data.phone;
            if (email) email.value = data.email;
            if (role) role.value = data.role;
        })
        .catch((error) =>
            console.error("Lỗi tải dữ liệu người dùng:", error),
        );

    document.getElementById("userModal")?.classList.add("show");
}

function closeUserModal() {
    document.getElementById("userModal")?.classList.remove("show");
}

function filterUsers() {
    const searchText =
        document.getElementById("searchInput")?.value.toLowerCase() || "";
    const roleFilter = document.getElementById("roleFilter")?.value || "";
    const rows = document.querySelectorAll(".user-row");
    let visibleCount = 0;

    rows.forEach((row) => {
        const name = row.getAttribute("data-name") || "";
        const phone = row.getAttribute("data-phone") || "";
        const role = row.getAttribute("data-role") || "";

        const matchesSearch =
            !searchText || name.includes(searchText) || phone.includes(searchText);
        const matchesRole = !roleFilter || role === roleFilter;

        if (matchesSearch && matchesRole) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    const showingSpan = document.getElementById("showingCount");
    if (showingSpan) showingSpan.innerText = visibleCount;
}

function resetUserFilter() {
    const searchInput = document.getElementById("searchInput");
    const roleFilter = document.getElementById("roleFilter");

    if (searchInput) searchInput.value = "";
    if (roleFilter) roleFilter.value = "";

    filterUsers();
}

function showDeleteUserModal(id) {
    deleteUserId = id;
    document.getElementById("deleteModal")?.classList.add("show");
}

function closeDeleteModal() {
    document.getElementById("deleteModal")?.classList.remove("show");
    deleteUserId = null;
}

function confirmDeleteUser() {
    if (deleteUserId) {
        window.location.href = `/admin/users/delete/${deleteUserId}`;
    }
}

// ==========================================
// 3. Quản lý xe
// ==========================================

let deleteBusId = null;
let isEditMode = false;

function openCreateBusModal() {
    isEditMode = false;

    const modalTitle = document.getElementById("modalTitle");
    const busForm = document.getElementById("busForm");
    const busId = document.getElementById("busId");

    if (modalTitle) modalTitle.innerText = "Thêm xe mới";
    if (busForm) busForm.reset();
    if (busId) busId.value = "";
    if (busForm) busForm.action = "/admin/buses/store";

    document.getElementById("busModal")?.classList.add("show");
}

function editBus(id) {
    isEditMode = true;

    const modalTitle = document.getElementById("modalTitle");
    const busForm = document.getElementById("busForm");
    const busId = document.getElementById("busId");

    if (modalTitle) modalTitle.innerText = "Sửa thông tin xe";
    if (busId) busId.value = id;
    if (busForm) busForm.action = `/admin/buses/update/${id}`;

    fetch(`/admin/buses/get/${id}`)
        .then((response) => response.json())
        .then((data) => {
            const biensoxe = document.getElementById("biensoxe");
            const loaixe = document.getElementById("loaixe");
            const soghe = document.getElementById("soghe");
            const nhaxe = document.getElementById("nhaxe");
            const trangthai = document.getElementById("trangthai");

            if (biensoxe) biensoxe.value = data.biensoxe;
            if (loaixe) loaixe.value = data.loaixe;
            if (soghe) soghe.value = data.soghe;
            if (nhaxe) nhaxe.value = data.nhaxe;
            if (trangthai) trangthai.value = data.trangthai;
        })
        .catch((error) => console.error("Lỗi tải dữ liệu xe:", error));

    document.getElementById("busModal")?.classList.add("show");
}

function closeBusModal() {
    document.getElementById("busModal")?.classList.remove("show");
}

function deleteBus(id) {
    deleteBusId = id;
    document.getElementById("deleteBusModal")?.classList.add("show");
}

function closeDeleteBusModal() {
    document.getElementById("deleteBusModal")?.classList.remove("show");
    deleteBusId = null;
}

function confirmDeleteBus() {
    if (deleteBusId) {
        window.location.href = `/admin/buses/delete/${deleteBusId}`;
    }
}

function filterBuses() {
    const searchText =
        document.getElementById("searchInput")?.value.toLowerCase() || "";
    const statusFilter = document.getElementById("statusFilter")?.value || "";
    const rows = document.querySelectorAll(".bus-row");
    let visibleCount = 0;

    rows.forEach((row) => {
        const searchData = row.getAttribute("data-search") || "";
        const status = row.getAttribute("data-status") || "";

        const matchesSearch =
            searchText === "" || searchData.includes(searchText);
        const matchesStatus = statusFilter === "" || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    const showingSpan = document.getElementById("showingCount");
    if (showingSpan) showingSpan.innerText = visibleCount;
}

function resetBusFilter() {
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("statusFilter");

    if (searchInput) searchInput.value = "";
    if (statusFilter) statusFilter.value = "";

    filterBuses();
}

// ==========================================
// 4. Quản lý tuyến
// ==========================================

let deleteRouteId = null;

function openCreateRouteModal() {
    document.getElementById("routeModalTitle").innerText = "Thêm tuyến mới";
    document.getElementById("routeForm").reset();
    document.getElementById("routeId").value = "";
    document.getElementById("routeForm").action = "/admin/routes/store";
    document.getElementById("routeModal").classList.add("show");
}

function editRoute(id) {
    document.getElementById("routeModalTitle").innerText =
        "Sửa thông tin tuyến";
    document.getElementById("routeId").value = id;
    document.getElementById("routeForm").action = `/admin/routes/update/${id}`;

    fetch(`/admin/routes/get/${id}`)
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("tentuyen").value = data.tentuyen || "";
            document.getElementById("diemdi").value = data.diemdi || "";
            document.getElementById("diemden").value = data.diemden || "";
            document.getElementById("khoangcach").value = data.khoangcach || "";
            document.getElementById("thoigian").value =
                data.thoigiandukien || data.thoigian || "";
            document.getElementById("giatien").value = data.giatien || "";
            document.getElementById("trangthai").value =
                data.trangthai || "Đang hoạt động";
            document.getElementById("maxe").value = data.maxe || "";
        })
        .catch((error) => console.error("Lỗi tải dữ liệu tuyến:", error));

    document.getElementById("routeModal").classList.add("show");
}

function closeRouteModal() {
    document.getElementById("routeModal").classList.remove("show");
}

function deleteRoute(id) {
    deleteRouteId = id;
    document.getElementById("deleteRouteModal")?.classList.add("show");
}

function closeDeleteRouteModal() {
    document.getElementById("deleteRouteModal")?.classList.remove("show");
    deleteRouteId = null;
}

function confirmDeleteRoute() {
    if (deleteRouteId) {
        window.location.href = `/admin/routes/delete/${deleteRouteId}`;
    }
}

function filterRoutes() {
    const searchText =
        document.getElementById("searchInput")?.value.toLowerCase() || "";
    const rows = document.querySelectorAll(".route-row");
    let visibleCount = 0;

    rows.forEach((row) => {
        const searchData = row.getAttribute("data-search") || "";

        if (searchText === "" || searchData.includes(searchText)) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    const showingSpan = document.getElementById("showingCount");
    if (showingSpan) showingSpan.innerText = visibleCount;
}

function resetRouteFilter() {
    const searchInput = document.getElementById("searchInput");
    if (searchInput) searchInput.value = "";
    filterRoutes();
}

document.getElementById("routeForm")?.addEventListener("submit", function () {
    console.log("Đang gửi form tuyến...");
});

document
    .getElementById("confirmDeleteRouteBtn")
    ?.addEventListener("click", confirmDeleteRoute);

// ==========================================
// 5. Quản lý chuyến
// ==========================================

let deleteTripId = null;

function openCreateTripModal() {
    document.getElementById("tripModalTitle").innerText = "Thêm chuyến mới";
    document.getElementById("tripForm").reset();
    document.getElementById("tripId").value = "";
    document.getElementById("tripForm").action = "/admin/trips/store";
    syncTripWithRoute();
    document.getElementById("tripModal").classList.add("show");
}

function editTrip(id) {
    document.getElementById("tripModalTitle").innerText =
        "Sửa thông tin chuyến";
    document.getElementById("tripId").value = id;
    document.getElementById("tripForm").action = `/admin/trips/update/${id}`;

    fetch(`/admin/trips/get/${id}`)
        .then((response) => response.json())
        .then((data) => {
            document.getElementById("matuyen").value = data.matuyen || "";
            syncTripWithRoute();
            document.getElementById("maxe").value = data.maxe || "";
            document.getElementById("ngaydi").value = data.ngaydi || "";
            document.getElementById("giodi").value = data.giodi || "";
            document.getElementById("giave").value = data.giave || "";
            syncTripSeatsWithBus(data.ghe_trong || "");
        })
        .catch((error) => console.error("Lỗi tải dữ liệu chuyến:", error));

    document.getElementById("tripModal")?.classList.add("show");
}

function closeTripModal() {
    document.getElementById("tripModal")?.classList.remove("show");
}

function syncTripWithRoute() {
    const routeSelect = document.getElementById("matuyen");
    const busSelect = document.getElementById("maxe");
    const priceInput = document.getElementById("giave");
    if (!routeSelect) return;

    const selectedRoute = routeSelect.options[routeSelect.selectedIndex];
    const routeBusId = selectedRoute?.dataset?.maxe || "";
    const routePrice = selectedRoute?.dataset?.giave || "";

    if (busSelect && routeBusId) {
        busSelect.value = routeBusId;
    }

    if (priceInput) {
        priceInput.value = routePrice;
    }

    syncTripSeatsWithBus();
}

function syncTripSeatsWithBus(currentSeats = "") {
    const busSelect = document.getElementById("maxe");
    const seatInput = document.getElementById("ghe_trong");
    if (!busSelect || !seatInput) return;

    const selectedOption = busSelect.options[busSelect.selectedIndex];
    const totalSeats = Number(selectedOption?.dataset?.soghe || 0);
    const availableSeats = Number(selectedOption?.dataset?.gheTrong || 0);
    const savedSeats = Number(currentSeats || availableSeats || 0);

    seatInput.max = totalSeats || "";
    seatInput.value = totalSeats ? Math.min(savedSeats, totalSeats) : "";
}

function deleteTrip(id) {
    deleteTripId = id;
    document.getElementById("deleteTripModal")?.classList.add("show");
}

function closeDeleteTripModal() {
    document.getElementById("deleteTripModal")?.classList.remove("show");
    deleteTripId = null;
}

function confirmDeleteTrip() {
    if (deleteTripId) {
        window.location.href = `/admin/trips/delete/${deleteTripId}`;
    }
}

function filterTrips() {
    const searchText =
        document.getElementById("searchInput")?.value.toLowerCase() || "";
    const filterDate = document.getElementById("filterDate")?.value || "";
    const filterRoute = document.getElementById("filterRoute")?.value || "";
    const rows = document.querySelectorAll(".trip-row");
    let visibleCount = 0;

    rows.forEach((row) => {
        const searchData = row.getAttribute("data-search") || "";
        const ngay = row.getAttribute("data-ngay") || "";
        const route = row.getAttribute("data-route") || "";

        const matchesSearch =
            searchText === "" || searchData.includes(searchText);
        const matchesDate = filterDate === "" || ngay === filterDate;
        const matchesRoute = filterRoute === "" || route === filterRoute;

        if (matchesSearch && matchesDate && matchesRoute) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    const showingSpan = document.getElementById("showingCount");
    if (showingSpan) showingSpan.innerText = visibleCount;
}

function resetTripFilter() {
    const searchInput = document.getElementById("searchInput");
    const filterDate = document.getElementById("filterDate");
    const filterRoute = document.getElementById("filterRoute");

    if (searchInput) searchInput.value = "";
    if (filterDate) filterDate.value = "";
    if (filterRoute) filterRoute.value = "";

    filterTrips();
}

document
    .getElementById("confirmDeleteTripBtn")
    ?.addEventListener("click", confirmDeleteTrip);

document.getElementById("tripForm")?.addEventListener("submit", function () {
    console.log("Đang gửi form chuyến...");
});

// ==========================================
// 6. Quản lý vé
// ==========================================

let deleteTicketId = null;
let currentTicketId = null;

function getTicketStatusBadge(status) {
    switch (status) {
        case "da_di":
            return '<span class="badge-success">Đã đi</span>';
        case "cho_don":
            return '<span class="badge-warning">Chờ đón</span>';
        default:
            return `<span class="badge-info">${status || "Không xác định"}</span>`;
    }
}

function viewTicket(id) {
    if (!isTicketsPage()) return;

    currentTicketId = id;

    fetch(`/admin/tickets/get/${id}`)
        .then((response) => response.json())
        .then((data) => {
            const detailHtml = `
                <div class="form-group">
                    <label class="form-label">Mã vé:</label>
                    <p class="form-label" style="font-weight: normal;">#V${String(data.mave).padStart(6, "0")}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Khách hàng:</label>
                    <p class="form-label" style="font-weight: normal;">${data.tai_khoan?.hoten || "N/A"}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại:</label>
                    <p class="form-label" style="font-weight: normal;">${data.tai_khoan?.phone || "N/A"}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Số ghế:</label>
                    <p class="form-label" style="font-weight: normal;">${data.ghe?.tenghe || "N/A"}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Ngày đặt:</label>
                    <p class="form-label" style="font-weight: normal;">${new Date(data.ngaydat).toLocaleDateString("vi-VN")}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Hình thức:</label>
                    <p class="form-label" style="font-weight: normal;">${data.hinhthucthanhtoan === "chuyen_khoan" ? "Chuyển khoản" : "Tiền mặt"}</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Giá vé:</label>
                    <p class="form-label" style="font-weight: bold; color: #16a34a;">${new Intl.NumberFormat("vi-VN").format(data.tongsotien)}đ</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Trạng thái:</label>
                    <p class="form-label">${getTicketStatusBadge(data.trangthai)}</p>
                </div>
            `;

            const ticketDetail = document.getElementById("ticketDetail");
            if (ticketDetail) ticketDetail.innerHTML = detailHtml;
        })
        .catch((error) => console.error("Lỗi tải chi tiết vé:", error));

    document.getElementById("ticketModal")?.classList.add("show");
}

function closeTicketModal() {
    document.getElementById("ticketModal")?.classList.remove("show");
}

function updateTicketStatus(id) {
    if (!isTicketsPage()) return;

    const statusTicketId = document.getElementById("statusTicketId");
    const statusForm = document.getElementById("statusForm");

    if (statusTicketId) statusTicketId.value = id;
    if (statusForm) statusForm.action = `/admin/tickets/update-status/${id}`;

    document.getElementById("statusModal")?.classList.add("show");
}

function closeStatusModal() {
    document.getElementById("statusModal")?.classList.remove("show");
}

function deleteTicket(id) {
    if (!isTicketsPage()) return;

    deleteTicketId = id;
    document.getElementById("deleteTicketModal")?.classList.add("show");
}

function closeDeleteTicketModal() {
    document.getElementById("deleteTicketModal")?.classList.remove("show");
    deleteTicketId = null;
}

function confirmDeleteTicket() {
    if (deleteTicketId) {
        window.location.href = `/admin/tickets/delete/${deleteTicketId}`;
    }
}

function printTicket(ticketCode) {
    if (isTicketsPage()) {
        alert("Đang in vé #V" + String(ticketCode).padStart(6, "0"));
        return;
    }

    alert("Đang in vé " + ticketCode);
}

function printCurrentTicket() {
    if (currentTicketId) {
        printTicket(currentTicketId);
    }
}

function filterTickets() {
    if (!isTicketsPage()) return;

    const searchText =
        document.getElementById("searchInput")?.value.toLowerCase() || "";
    const statusFilter = document.getElementById("filterStatus")?.value || "";
    const rows = document.querySelectorAll(".ticket-row");
    let visibleCount = 0;

    rows.forEach((row) => {
        const searchData = row.getAttribute("data-search") || "";
        const status = row.getAttribute("data-status") || "";

        const matchesSearch =
            searchText === "" || searchData.includes(searchText);
        const matchesStatus = statusFilter === "" || status === statusFilter;

        if (matchesSearch && matchesStatus) {
            row.style.display = "";
            visibleCount++;
        } else {
            row.style.display = "none";
        }
    });

    const showingSpan = document.getElementById("showingCount");
    if (showingSpan) showingSpan.innerText = visibleCount;
}

function resetTicketFilter() {
    const searchInput = document.getElementById("searchInput");
    const statusFilter = document.getElementById("filterStatus");

    if (searchInput) searchInput.value = "";
    if (statusFilter) statusFilter.value = "";

    filterTickets();
}

function initTicketsPage() {
    if (!isTicketsPage()) return;

    document
        .getElementById("confirmDeleteTicketBtn")
        ?.addEventListener("click", confirmDeleteTicket);
}

// ==========================================
// 7. Hiển thị / ẩn mật khẩu
// ==========================================

function togglePassword(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return;

    const icon = event.target;
    if (field.type === "password") {
        field.type = "text";
        if (icon) {
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    } else {
        field.type = "password";
        if (icon) {
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        }
    }
}

// ==========================================
// 8. Báo cáo / thao tác giả lập
// ==========================================

function exportReport(type) {
    alert("Xuất báo cáo " + type);
}

function viewTicketDetail(ticketCode) {
    alert("Chi tiết vé: " + ticketCode);
}

function viewPaymentDetail(paymentId) {
    alert("Chi tiết giao dịch: " + paymentId);
}

// ==========================================
// 9. Cài đặt hệ thống
// ==========================================

function saveSettings() {
    alert("Đã lưu cài đặt hệ thống!");
}

function resetSettings() {
    if (confirm("Khôi phục cài đặt mặc định?")) {
        alert("Đã khôi phục cài đặt mặc định");
    }
}

function refreshReport() {
    alert("Đang tải báo cáo mới...");
}

// ==========================================
// 10. CRUD chung
// ==========================================

function editItem(id, module) {
    alert("Chức năng sửa đang phát triển. ID: " + id + " - Module: " + module);
}

function deleteItem(id, module, deleteUrl) {
    if (confirm("Bạn có chắc chắn muốn xóa " + module + " này?")) {
        window.location.href = deleteUrl + "/" + id;
    }
}

// ==========================================
// 11. Khởi tạo khi tải trang
// ==========================================

document.addEventListener("DOMContentLoaded", function () {
    const searchInput = document.getElementById("searchInput");

    if (searchInput) {
        searchInput.addEventListener("keypress", function (e) {
            if (e.key !== "Enter") return;

            const currentUrl = window.location.href;

            if (currentUrl.includes("/admin/users")) {
                filterUsers();
            } else if (currentUrl.includes("/admin/buses")) {
                filterBuses();
            } else if (currentUrl.includes("/admin/routes")) {
                filterRoutes();
            } else if (currentUrl.includes("/admin/trips")) {
                filterTrips();
            } else if (currentUrl.includes("/admin/tickets")) {
                filterTickets();
            }
        });
    }

    const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener("click", confirmDeleteUser);
    }

    const confirmDeleteBusBtn = document.getElementById("confirmDeleteBusBtn");
    if (confirmDeleteBusBtn) {
        confirmDeleteBusBtn.addEventListener("click", confirmDeleteBus);
    }

    const confirmDeleteRouteBtn =
        document.getElementById("confirmDeleteRouteBtn");
    if (confirmDeleteRouteBtn) {
        confirmDeleteRouteBtn.addEventListener("click", confirmDeleteRoute);
    }

    if (window.location.pathname.includes("/admin/routes")) {
        const hasErrors =
            typeof window.routeFormHasErrors !== "undefined"
                ? window.routeFormHasErrors
                : false;
        if (hasErrors) {
            document.getElementById("routeModal")?.classList.add("show");
        }
    }

    if (window.location.pathname.includes("/admin/trips")) {
        document
            .getElementById("matuyen")
            ?.addEventListener("change", () => syncTripWithRoute());

        document
            .getElementById("maxe")
            ?.addEventListener("change", () => syncTripSeatsWithBus());

        const hasErrors =
            typeof window.tripFormHasErrors !== "undefined"
                ? window.tripFormHasErrors
                : false;
        if (hasErrors) {
            document.getElementById("tripModal")?.classList.add("show");
        }
    }

    if (window.location.pathname.includes("/admin/buses")) {
        const hasBusErrors =
            typeof window.busFormHasErrors !== "undefined"
                ? window.busFormHasErrors
                : false;
        if (hasBusErrors) {
            document.getElementById("busModal")?.classList.add("show");
        }
    }

    initTicketsPage();

    window.addEventListener("click", function (e) {
        if (e.target.classList.contains("modal")) {
            e.target.classList.remove("show");
        }
    });
});
