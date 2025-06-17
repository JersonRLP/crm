feather.replace();

document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('formLogin');

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        const usuario = document.getElementById('usuario').value.trim();
        const password = document.getElementById('password').value.trim();

        if (!usuario || !password) {
            alert('Por favor, complete todos los campos.');
            return;
        }

        try {
            const response = await fetch('/crm/public/apiLogin.php?action=login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ usuario, password })
            });

            const data = await response.json();

            if (data.success) {
                // Redirigimos manualmente
                window.location.href = '/crm/app/views/dashboard.php';
            } else if (data.error) {
                alert(data.error);
            } else {
                alert('Ocurrió un error inesperado.');
            }
        } catch (err) {
            console.error('Error en la petición:', err);
            alert('Error de conexión. Intenta nuevamente.');
        }
    });
});

