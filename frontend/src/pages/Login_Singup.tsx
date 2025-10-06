import React, { useState } from "react";
import "./Log_Sing.css";
import logo from "../assets/chuleteria_log.png";

export default function Prueba() {
  const [activeTab, setActiveTab] = useState<"login" | "register">("login");

  // Esta función se ejecuta cuando el usuario envía el formulario de registro
  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    const form = e.target as HTMLFormElement;
  // Obtenemos los valores de los inputs
  const first_name = (form[0] as HTMLInputElement).value;
  const last_name = (form[1] as HTMLInputElement).value;
  const username = (form[2] as HTMLInputElement).value;
  const password = (form[3] as HTMLInputElement).value;
  const email = (form[4] as HTMLInputElement).value;

    try {
      // Hacemos la petición al backend
      const res = await fetch("http://localhost:8000/api/users/register", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ first_name, last_name, username, password, email }),
      });
      const data = await res.json();
      if (res.ok) {
        alert("¡Registro exitoso!");
        // Aquí puedes limpiar el formulario o redirigir
      } else {
        alert("Error: " + (data.message || JSON.stringify(data)));
      }
    } catch (err) {
      alert("Error de conexión");
    }
  };

  return (
    <div className="prueba-bg">
      <div className="prueba-cuadro-global">
        <div className="split-img">
          <img src={logo} alt="logo chuleteria" />
        </div>
        <div className="split-form">
          <div className="tab-buttons">
            <button
              className={activeTab === "login" ? "tab-btn active" : "tab-btn"}
              onClick={() => setActiveTab("login")}
            >
              Login
            </button>
            <button
              className={activeTab === "register" ? "tab-btn active" : "tab-btn"}
              onClick={() => setActiveTab("register")}
            >
              Sign Up
            </button>
          </div>
          {activeTab === "login" ? (
            <form className="formulario" onSubmit={e => e.preventDefault()}>
              <input type="text" placeholder="Nombre del usuario" required />
              <input type="password" placeholder="Contraseña" required />
              <button type="submit" className="form-btn">Entrar</button>
            </form>
          ) : (
            <form className="formulario" onSubmit={handleRegister}>
              <input type="text" placeholder="Primer nombre" required />
              <input type="text" placeholder="Apellido" required />
              <input type="text" placeholder="Nombre de usuario" required />
              <input type="password" placeholder="Contraseña" required />
              <input type="email" placeholder="Correo electrónico" required />
              <button type="submit" className="form-btn">Registrarse</button>
            </form>
          )}
        </div>
      </div>
    </div>
  );
}