$(document).ready(function () {

  function cargarUsuarios() {
    $.ajax({
      url: 'getUsers.php',
      type: 'GET',
      dataType: 'json',
      success: function (usuarios) {
        let rows = '';
        usuarios.forEach(function (usuario) {
          rows += `<tr>
                    <td>${usuario.nombreCompleto}</td>
                    <td>${usuario.correo}</td>
                    <td>${usuario.fechaNacimiento}</td>
                    <td>
                      <button class="btn btn-warning btnEditar" data-id="${usuario.id}" data-nombre="${usuario.nombreCompleto}" data-correo="${usuario.correo}" data-fecha="${usuario.fechaNacimiento}">Editar</button>
                      <button class="btn btn-danger btnEliminar" data-id="${usuario.id}">Eliminar</button>
                    </td>
                  </tr>`;
        });
        $('#tablaUsuarios tbody').html(rows);
      }
    });
  }

  cargarUsuarios();

  $('#btnEnviar').on('click', function () {
    let nombre = $('#nombreCompleto').val().trim();
    let correo = $('#correo').val().trim();
    let contrasena = $('#contrasena').val();
    let fecha = $('#fechaNacimiento').val();
    let usuarioId = $('#usuarioId').val();

    const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (nombre === '' || correo === '' || fecha === '') {
      alert('Todos los campos son obligatorios.');
      return;
    }

    if (!correoValido.test(correo)) {
      alert('El correo no tiene un formato válido.');
      return;
    }

    const fechaNacimiento = new Date(fecha);
    const hoy = new Date();
    const edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
    const mes = hoy.getMonth() - fechaNacimiento.getMonth();
    const dia = hoy.getDate() - fechaNacimiento.getDate();

    if (fechaNacimiento > hoy || (edad < 12 || (edad === 12 && (mes < 0 || (mes === 0 && dia < 0))))) {
      alert('Debes tener al menos 12 años y la fecha no debe ser futura.');
      return;
    }

    if (usuarioId === '') {

      if (contrasena === '' || contrasena.length < 6) {
        alert('La contraseña debe tener al menos 6 caracteres.');
        return;
      }

      let formData = $('#frmUsuario').serialize();
      $.ajax({
        url: 'functions.php',
        type: 'POST',
        data: formData,
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') {
            alert('Usuario registrado correctamente');
            $('#frmUsuario')[0].reset();
            $('#usuarioId').val('');
            cargarUsuarios();
          } else {
            alert('Error: ' + response.message);
          }
        }
      });
    } else {

      // Actualizar usuario
      $.ajax({
        url: 'updateUser.php',
        type: 'POST',
        data: {
          usuarioId: usuarioId,
          nombreCompleto: nombre,
          correo: correo,
          fechaNacimiento: fecha
        },
        dataType: 'json',
        success: function (response) {
          if (response.status === 'success') {
            alert('Usuario actualizado');
            $('#frmUsuario')[0].reset();
            $('#usuarioId').val('');
            $('#contrasena').val(''); 
            cargarUsuarios();
          } else {
            alert('Error: ' + response.message);
          }
        }
      });
    }
  });

  // Elimina usurio
  $(document).on('click', '.btnEliminar', function () {
    let usuarioId = $(this).data('id');
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
      $.ajax({
        url: 'deleteUser.php',
        type: 'POST',
        data: { id: usuarioId },
        success: function (response) {
          if (response.status === 'success') {
            cargarUsuarios();
          } else {
            alert('Error: ' + response.message);
          }
        }
      });
    }
  });

  // Editar usuario
  $(document).on('click', '.btnEditar', function () {
    $('#usuarioId').val($(this).data('id'));
    $('#nombreCompleto').val($(this).data('nombre'));
    $('#correo').val($(this).data('correo'));
    $('#fechaNacimiento').val($(this).data('fecha'));
    $('#contrasena').val('');
  });
});
