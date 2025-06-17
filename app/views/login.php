<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Login form</title>
    <link
      rel="stylesheet"
      href="https://fonts.googleapis.com/css?family=Mukta:300,600,700"
    />
    <link rel="stylesheet" href="/crm/public/css/login.css" />
  </head>
  <body>
    <!-- partial:index.partial.html -->
    <div class="container">
      <div class="title">
        <div class="brand">SYSTEM CRM</div>
        <div class="subtitle">¡Por favor! Inicia sesión para continuar.</div>
      </div>

      <form id="formLogin">
        <div class="row">
          <div class="input-group" id="username">
            <i data-feather="user"></i>
            <input type="text" id="usuario" name="usuario"  placeholder="Username" />
          </div>
        </div>
        <div class="row">
          <div class="input-group">
            <i data-feather="lock"></i>
            <input type="password" id="password" name="password" placeholder="Password" />
          </div>
        </div>
        <div class="row">
          <div class="forgotpassword">
            <a href="#">¿Olvidé la contraseña?</a>
          </div>
        </div>
        <div class="row">
          <button>LOGIN <i data-feather="chevron-right"></i></button>
        </div>
      </form>
      <div class="row">
        <div class="singup">
          ¿No tienes una cuenta?
          <a
            target="_blank"
            href="https://codepen.io/mengsengoeng/pen/GwjOQE?editors=1100"
            >Regístrate</a
          >
        </div>
      </div>
    </div>
    <!-- partial -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.8.0/feather.min.js"></script>
    <script src="/crm/public/js/login.js"></script>
  </body>
</html>
