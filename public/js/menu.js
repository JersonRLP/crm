document.querySelectorAll('.load-view').forEach(link => {
    link.addEventListener('click', function (e) {
        e.preventDefault();
        let url = this.getAttribute('href');

        // Si el clic viene del breadcrumb, solo recargar contenido sin modificar active
        if (this.closest('.breadcrumb')) {
            console.log("Click en breadcrumb - solo recargar contenido");
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('content').innerHTML = data;


                    // Extraer título desde la nueva vista si tiene <h3 class="page-title">
                    let tempDiv = document.createElement('div');
                    tempDiv.innerHTML = data;
                    let newTitle = tempDiv.querySelector("h3.page-title")?.innerText || "Dashboard"; // Forzamos "Dashboard" si no hay título
                    document.querySelector("h3.page-title").innerText = newTitle;
                    document.querySelector("li.breadcrumb-item.active").innerText = newTitle;

                    // **Activar el elemento Dashboard sin depender de href**
                    document.querySelectorAll('#sidebar-menu ul li').forEach(li => li.classList.remove('active'));
                    document.querySelectorAll('#sidebar-menu ul li').forEach(li => {
                        if (li.innerText.includes("Dashboard")) {
                            li.classList.add('active');
                        }
                    });
                })
                .catch(error => console.error("Error al cargar la vista:", error));
            return; // Salir y evitar cambios innecesarios
        }

        // Remover 'active' de todos los elementos del sidebar
        document.querySelectorAll('#sidebar-menu ul li').forEach(li => li.classList.remove('active'));

        // Agregar 'active' solo al padre del enlace actual (excepto breadcrumb)
        this.parentElement.classList.add('active');

        // Cargar la vista normalmente
        fetch(url)
            .then(response => response.text())
            .then(data => {
                document.getElementById('content').innerHTML = data;

                // Extraer título desde la nueva vista si tiene <h3 class="page-title">
                let tempDiv = document.createElement('div');
                tempDiv.innerHTML = data;
                let newTitle = tempDiv.querySelector("h3.page-title")?.innerText || this.innerText.trim();

                let pageTitle = document.querySelector("h3.page-title");
                let breadcrumb = document.querySelector("li.breadcrumb-item.active");

                if (pageTitle) pageTitle.innerText = newTitle;
                if (breadcrumb) breadcrumb.innerText = newTitle;

                if (this.dataset.modulo === 'cliente') {
                    init_clientes(); // inicializa DataTable y llama a listarDatos()
                }
            })
            .catch(error => console.error("Error al cargar la vista:", error));
    });
});


