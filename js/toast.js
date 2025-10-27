document.addEventListener('DOMContentLoaded', function () {
    const loginForm = document.getElementById('login_proses');
    const toast = document.getElementById('toast');
    const toastMessage = document.getElementById('toast-message');

    loginForm.addEventListener('submit', function (event) {
        event.preventDefault();

        // Simpan data formulir dalam variabel FormData
        const formData = new FormData(loginForm);

        // Kirim data ke proses_login.php menggunakan AJAX
        fetch('login.php', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            // Tampilkan pesan toast sesuai dengan hasil login
            if (data.status === 'success') {
                showToast(data.message, 'alert-success');
            } else {
                showToast(data.message, 'alert-danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    function showToast(message, alertClass) {
        toastMessage.innerText = message;
        toast.classList.add(alertClass);
        toast.classList.remove('fade');

        setTimeout(function () {
            toast.classList.add('fade');
        }, 3000);
    }
});
