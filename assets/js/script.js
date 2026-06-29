// assets/js/script.js

// === Modal Functions ===
function openModal(modalId) {
    document.getElementById(modalId).classList.add('active');
}

function closeModal(modalId) {
    document.getElementById(modalId).classList.remove('active');
}

// === Confirm Delete ===
function confirmDelete(url) {
    if (confirm('Yakin ingin menghapus data ini?')) {
        window.location.href = url;
    }
}

// === Auto dismiss notification ===
document.addEventListener('DOMContentLoaded', function() {
    const notif = document.querySelector('.notification');
    if (notif) {
        setTimeout(() => {
            notif.style.transition = 'opacity 0.5s';
            notif.style.opacity = '0';
            setTimeout(() => notif.remove(), 500);
        }, 4000);
    }
});

// === Toggle Password Visibility ===
function togglePassword(fieldId, btn) {
    const field = document.getElementById(fieldId);
    if (field.type === 'password') {
        field.type = 'text';
        btn.textContent = '🙈';
    } else {
        field.type = 'password';
        btn.textContent = '👁';
    }
}

// === Format Rupiah ===
function formatRupiah(angka) {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        minimumFractionDigits: 0,
        maximumFractionDigits: 0
    }).format(angka);
}

// === Calculate Sisa Tagihan ===
function hitungSisa() {
    const total = parseFloat(document.getElementById('total_tagihan')?.value?.replace(/\./g, '') || 0);
    const dibayar = parseFloat(document.getElementById('dibayar')?.value?.replace(/\./g, '') || 0);
    const sisa = Math.max(0, total - dibayar);
    const sisaField = document.getElementById('sisa_tagihan');
    if (sisaField) {
        sisaField.value = sisa.toLocaleString('id-ID');
    }
    const statusField = document.getElementById('status');
    if (statusField) {
        if (sisa <= 0) statusField.value = 'Lunas';
        else if (dibayar > 0) statusField.value = 'Cicilan';
        else statusField.value = 'Belum Bayar';
    }
}