import React, { useState } from "react";
import "./Login.css"; // Opcional, para estilos

export default function LoginRegisterPage() {
  // Estado para saber qué formulario mostrar
  const [showLogin, setShowLogin] = useState(true);

  // Estados para los campos de login
  const [loginEmail, setLoginEmail] = useState("");
  const [loginPassword, setLoginPassword] = useState("");

  // Estados para los campos de registro
  const [registerEmail, setRegisterEmail] = useState("");
  const [registerPassword, setRegisterPassword] = useState("");
  const [registerName, setRegisterName] = useState("");

  // Función que se ejecuta al enviar el formulario de login
  const handleLogin = (e: React.FormEvent) => {
    e.preventDefault();
    // Aquí iría la lógica para autenticar al usuario
    alert(`Login:\nEmail: ${loginEmail}\nPassword: ${loginPassword}`);
  };

  // Función que se ejecuta al enviar el formulario de registro
  const handleRegister = (e: React.FormEvent) => {
    e.preventDefault();
    // Aquí iría la lógica para registrar al usuario
    alert(`Registro:\nNombre: ${registerName}\nEmail: ${registerEmail}\nPassword: ${registerPassword}`);
  };

  return (
    <div className="login-container">
      <div className="switch-buttons">
        {/* Botón para mostrar el formulario de login */}
        <button
          onClick={() => setShowLogin(true)}
          className={showLogin ? "active" : ""}
        >
          Iniciar Sesión
        </button>
        {/* Botón para mostrar el formulario de registro */}
        <button
          onClick={() => setShowLogin(false)}
          className={!showLogin ? "active" : ""}
        >
          Registrarse
        </button>
      </div>

      {/* Formulario de Login */}
      {showLogin && (
        <form className="login-form" onSubmit={handleLogin}>
          <h2>Iniciar Sesión</h2>
          <input
            type="email"
            placeholder="Correo electrónico"
            value={loginEmail}
            onChange={e => setLoginEmail(e.target.value)}
            required
          />
          <input
            type="password"
            placeholder="Contraseña"
            value={loginPassword}
            onChange={e => setLoginPassword(e.target.value)}
            required
          />
          <button type="submit">Entrar</button>
        </form>
      )}

      {/* Formulario de Registro */}
      {!showLogin && (
        <form className="login-form" onSubmit={handleRegister}>
          <h2>Registrarse</h2>
          <input
            type="text"
            placeholder="Nombre"
            value={registerName}
            onChange={e => setRegisterName(e.target.value)}
            required
          />
          <input
            type="email"
            placeholder="Correo electrónico"
            value={registerEmail}
            onChange={e => setRegisterEmail(e.target.value)}
            required
          />
          <input
            type="password"
            placeholder="Contraseña"
            value={registerPassword}
            onChange={e => setRegisterPassword(e.target.value)}
            required
          />
          <button type="submit">Crear cuenta</button>
        </form>
      )}
    </div>
  );
}
