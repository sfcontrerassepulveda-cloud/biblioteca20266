document.getElementById('loginForm').addEventListener('submit', function(e) {
  e.preventDefault();

  const userType = document.getElementById('userType').value;
  const username = document.getElementById('username').value;
  const password = document.getElementById('password').value;

  // Datos simulados
  const users = {
    usuario: { username: "user123", password: "1234" },
    bibliotecario: { username: "admin", password: "admin123" }
  };

  if (users[userType].username === username && users[userType].password === password) {
    alert(`Bienvenido, ${userType === 'usuario' ? 'usuario' : 'bibliotecario'}: ${username}`);
    // Redirigir a otra página si se desea
    // window.location.href = userType + ".html";
  } else {
    document.getElementById('errorMsg').innerText = "Usuario o contraseña incorrectos";
  }
});
