// modal-table.js - Modifikasi

/**
 * Fungsi untuk membuka modal edit dengan pengecekan tipe
 * @param {string} url - URL endpoint
 * @param {string} type - Jenis data (bibit/pakan/panen)
 */
function openEditModal(url, type) {
    // Tampilkan logging untuk debugging
    console.log(`Membuka modal edit untuk ${type} dengan URL: ${url}`);

    // Tampilkan loading indicator
    document.body.style.cursor = "wait";

    fetch(url, {
        headers: {
            Accept: "text/html",
            "X-Requested-With": "XMLHttpRequest",
        },
    })
        .then((response) => {
            console.log("Status respons:", response.status);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.text();
        })
        .then((html) => {
            console.log("HTML diterima, panjang:", html.length);

            // Periksa jika response kosong atau tidak valid
            if (!html || html.trim() === "") {
                throw new Error("Response kosong dari server");
            }

            // Hapus modal yang mungkin masih ada
            const existingModal = document.getElementById("editModalOverlay");
            if (existingModal) existingModal.remove();

            // Buat modal overlay dengan efek shadow yang lebih baik
            const modalOverlay = document.createElement("div");
            modalOverlay.className =
                "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50";
            modalOverlay.id = "editModalOverlay";

            // Konten modal dengan shadow yang lebih menonjol
            const modalContent = document.createElement("div");
            modalContent.className =
                "bg-white rounded-lg p-6 w-full max-w-md mx-4 shadow-2xl transform transition-all duration-300 scale-95 opacity-0";
            modalContent.innerHTML = html;
            modalOverlay.appendChild(modalContent);

            // Tambahkan modal ke body, bukan ke container tertentu
            document.body.appendChild(modalOverlay);
            console.log("Modal ditambahkan ke body");

            // Animasi muncul dengan shadow
            setTimeout(() => {
                modalContent.style.transform = "scale(1)";
                modalContent.style.opacity = "1";
                modalContent.style.boxShadow =
                    "0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04)";
            }, 10);
            // Blok scroll
            document.body.style.overflow = "hidden";

            // Event listeners
            modalOverlay.addEventListener("click", function (e) {
                if (e.target === this) closeEditModal();
            });

            const handleEscape = (e) => {
                if (e.key === "Escape") closeEditModal();
            };
            document.addEventListener("keydown", handleEscape);

            // Fokus ke input pertama
            const firstInput = modalContent.querySelector(
                "input, select, textarea"
            );
            if (firstInput) firstInput.focus();
        })
        .catch((error) => {
            console.error("Error saat memuat modal:", error);
            alert(`Gagal memuat form ${type}. Silakan coba lagi.`);
        })
        .finally(() => {
            document.body.style.cursor = "";
        });
}

/**
 * Fungsi untuk menutup modal edit
 */
function closeEditModal() {
    const modal = document.getElementById("editModalOverlay");
    const modalContent = modal?.querySelector("div");

    if (modal) {
        // Animasi
        modal.style.opacity = "0";
        if (modalContent) modalContent.style.transform = "scale(0.95)";

        setTimeout(() => {
            document.body.style.overflow = "";
            modal.remove();
        }, 200);
    }
}

/**
 * Handle submit form edit via AJAX
 * @param {HTMLFormElement} form - Form element
 * @param {string} type - Jenis data (bibit/pakan/panen)
 */
function submitEditForm(form, type) {
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;

    submitBtn.innerHTML = "Menyimpan...";
    submitBtn.disabled = true;

    fetch(form.action, {
        method: "POST",
        body: new FormData(form),
        headers: {
            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')
                .content,
            "X-HTTP-Method-Override": "PUT",
            Accept: "application/json",
        },
    })
        .then((response) => {
            if (!response.ok)
                return response.json().then((err) => {
                    throw err;
                });
            return response.json();
        })
        .then((data) => {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                closeEditModal();
                window.location.reload();
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;

            if (error.errors) {
                alert("Error: " + Object.values(error.errors).join("\n"));
            } else {
                alert(`Terjadi kesalahan saat menyimpan data ${type}`);
            }
        });
}

// Export fungsi - Pastikan fungsi tersedia secara global
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;
window.submitEditForm = submitEditForm;
