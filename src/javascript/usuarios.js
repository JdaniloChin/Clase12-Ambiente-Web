document.addEventListener('DOMContentLoaded', function() {
    const btnsEditar = document.querySelectorAll('.btnEditar');
    const modalElement = document.getElementById('modalFormulario');
    const modal = new bootstrap.Modal(modalElement);
    const modalTitle = document.getElementById('modalFormularioLabel');
    const form = document.getElementById('formularioUsuarios');
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('confirm');

    cargarUsuarios();

    $(document).on('click', '.btnEditar', function(){
        const id = $(this).data('id');
        const nombre = $(this).data('nombre');
        const usuario = $(this).data('usuario');
        const correo = $(this).data('correo');
        const rol = $(this).data('rol');
        const estado = $(this).data('estado');

        //cambiar titulo del modal
        modalTitle.textContent = 'Editar Usuario';

        //precargar los datos del usuario en la tabla al formulario
        $('#usuario_id').val(id);
        $('#nombre').val(nombre);
        $('#usuario').val(usuario);
        $('#email').val(correo);
        $('#rol').val(rol);
        $('#estado').val(estado);

        //Contraseña por seguridad se queda en blanco
        $('#password').val('');
        $('#confirm').val('');

        //Quitar required para edicion
        passwordInput.removeAttribute('required');
        confirmInput.removeAttribute('required');

        modal.show();

    });

    //Limpiar formulario del modal al cerrarlo, para insertar un nuevo usuario
    $('#modalFormulario').on('hidden.bs.modal', () => {
        form.reset();
        $('#usuario_id').val('');
        modalTitle.textContent = 'Agregar Usuario';
        passwordInput.setAttribute('required','');
        confirmInput.setAttribute('required','');
    });

    function cargarUsuarios(){
        $.get('./api/obtener_usuarios.php', function(data){
            let html='';

            if(data && data.length > 0){
                data.forEach(usuario => {
                    html += `<tr>
                            <td> ${usuario.nombre}</td>
                            <td> ${usuario.usuario}</td>
                            <td> ${usuario.correo}</td>
                            <td> ${usuario.rol}</td>
                            <td> ${usuario.estado}</td>
                            <td>
                                <a href='#'
                                data-id='${usuario.id_usuario}'
                                data-nombre='${usuario.nombre}'
                                data-usuario='${usuario.usuario}'
                                data-correo='${usuario.correo}'
                                data-rol='${usuario.rol}'
                                data-estado='${usuario.estado}'
                                class='btn btn-warning btn-sm btnEditar'>Editar</a>
                                <a href='#' data-id='${usuario.id_usuario}' class='btn btn-danger btn-sm btnEliminar'>Eliminar</a>
                            </td>
                        </tr>`;
                });
            }
            $('#tablaUsuarios tbody').html(html);
        }, 'json');
    }

    //Guardar usuario /ajax
    $('#formularioUsuarios').on('submit', function (e) {
        e.preventDefault();

        const datos = $(this).serialize();

        $.post('./api/procesar_usuario.php', datos, function(respuesta) {
            console.log("valor de respuesta: ", respuesta);
            const alerta = `<div class="alert alert-info alert-dismissible fade show" role="alert">
            ${respuesta}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>`;

            $('.modal-body').prepend(alerta);

            setTimeout(() =>{
                modal.hide();
                cargarUsuarios();
            }, 2500);
        });
    });

    //eliminar usuario
    $(document).on('click', '.btnEliminar', function(e){
        e.preventDefault();
        if(!confirm('¿Está seguro de eliminar este usuario?')) return;
        const id = $(this).data('id');

        $.get(`./api/procesar_usuario.php?eliminar=${id}`, function(respuesta){
            alert(respuesta);
            cargarUsuarios();
        });
    });

})