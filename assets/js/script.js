// Menambahkan fokus ke input username saat halaman dimuat
window.onload = function() {
    document.getElementById('username').focus();
};

// Menampilkan pesan error jika ada
if (document.querySelector('.alert')) {
    document.querySelector('.alert').style.display = 'block';
}
