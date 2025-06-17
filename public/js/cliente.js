
let tablaClientes;
function init_clientes() {
    if (!$.fn.DataTable.isDataTable('#tabla-clientes')) {
        tablaClientes = $('#tabla-clientes').DataTable({
            responsive: true,
            paging: true,
            searching: true,
            info: true,
            ordering: false,
            pageLength: 5,
            lengthMenu: [5, 10, 25, 50],
            language: {
                lengthMenu: "Mostrar _MENU_ registros por página",
                zeroRecords: "No se encontraron resultados",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty: "No hay registros disponibles",
                search: "Buscar:",
                paginate: {
                    next: "Siguiente",
                    previous: "Anterior"
                }
            },
            dom: '<"row mb-3"<"col-sm-6"l><"col-sm-6 text-end"f>>' +
                    'rt' +
                    '<"row mt-3"<"col-sm-6"i><"col-sm-6 text-end"p>>',
            columnDefs: [
                { className: "text-center", targets: "_all" }
            ]
        });
    }

    listarDatos();
}



function listarDatos() {
    $.ajax({
        url: "/crm/public/apiCliente?action=list",
        method: "GET",
        dataType: "json",
        success: function (data) {
            let contador = 1;
            let rows = data.reverse().map(function (item) {
                return [
                    contador++,
                    item.nom_cli,
                    item.empresa,
                    item.correo_cli,
                    item.telefono_cli,
                    item.estado_cli,
                    `
                    <div class="actions">
                        <a href="#" class="btn btn-sm bg-success-light me-2">
                            <i class="fe fe-pencil"></i>
                        </a>
                        <a href="#" class="btn btn-sm bg-danger-light">
                            <i class="fe fe-trash"></i>
                        </a>
                    </div>
                    `,
                ];
            });

            tablaClientes.clear().rows.add(rows).draw();
        },
        error: function (xhr, status, error) {
            console.error("Error al listar los datos:", error);
        },
    });
}

