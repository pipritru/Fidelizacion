import React, { useState } from "react";
import { useNavigate } from "react-router-dom";
import { ToastContainer, toast, Bounce } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";
import api from "../services/api";
import "./Log_Sing.css";
import logo from "../assets/chuleteria_log.png";

export default function Prueba() {
  const navigate = useNavigate();
  const [activeTab, setActiveTab] = useState<"login" | "register">("login");
  const [loginInputs, setLoginInputs] = useState({ username: "", password: "" });

  // Función para login
  const handleLogin = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await api.post("/api/users/login", loginInputs);

      setTimeout(() => navigate("/home"), 1000);
    } catch (err: any) {
      if (err.response) {
        toast.error(err.response.data.message || "Usuario o contraseña incorrectos", {
          position: "bottom-left",
          autoClose: 3000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          progress: undefined,
          theme: "light",
          transition: Bounce,
        });
      } else {
        toast.error("Error de conexión", {
          position: "bottom-left",
          autoClose: 3000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          progress: undefined,
          theme: "light",
          transition: Bounce,
        });
      }
    }
  };
  const [registerInputs, setRegisterInputs] = useState({ first_name: "", last_name: "", username: "", password: "", email: "" });

  // Esta función se ejecuta cuando el usuario envía el formulario de registro
  const handleRegister = async (e: React.FormEvent) => {
    e.preventDefault();
    try {
      const res = await api.post("/api/users/register", registerInputs);
      toast.success("¡Registro exitoso!", {
        position: "bottom-left",
        autoClose: 3000,
        hideProgressBar: false,
        closeOnClick: true,
        pauseOnHover: true,
        draggable: true,
        progress: undefined,
        theme: "light",
        transition: Bounce,
      });
      setRegisterInputs({ first_name: "", last_name: "", username: "", password: "", email: "" });
      setTimeout(() => setActiveTab("login"), 1000);
    } catch (err: any) {
      if (err.response) {
        toast.error(err.response.data.message || "Error de registro", {
          position: "bottom-left",
          autoClose: 3000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          progress: undefined,
          theme: "light",
          transition: Bounce,
        });
      } else {
        toast.error("Error de conexión", {
          position: "bottom-left",
          autoClose: 3000,
          hideProgressBar: false,
          closeOnClick: true,
          pauseOnHover: true,
          draggable: true,
          progress: undefined,
          theme: "light",
          transition: Bounce,
        });
      }
    }
  };

  return (
    <div className="prueba-bg">
      <ToastContainer />
      <div className="prueba-cuadro-global">
        <div className="split-img">
          <img src={logo} alt="logo chuleteria" />
        </div>
        <div className="split-form">
          <div className="tab-buttons">
            <button
              className={activeTab === "login" ? "tab-btn active" : "tab-btn"}
              onClick={() => {
                setActiveTab("login");
                setLoginInputs({ username: "", password: "" });
              }}
            >
              Login
            </button>
            <button
              className={activeTab === "register" ? "tab-btn active" : "tab-btn"}
              onClick={() => {
                setActiveTab("register");
                setRegisterInputs({ first_name: "", last_name: "", username: "", password: "", email: "" });
              }}
            >
              Sign Up
            </button>
          </div>
          {activeTab === "login" ? (
            <form className="formulario" onSubmit={handleLogin}>
              <input
                type="text"
                placeholder="Nombre del usuario"
                required
                value={loginInputs.username}
                onChange={e => setLoginInputs({ ...loginInputs, username: e.target.value })}
              />
              <input
                type="password"
                placeholder="Contraseña"
                required
                value={loginInputs.password}
                onChange={e => setLoginInputs({ ...loginInputs, password: e.target.value })}
              />
              <button type="submit" className="form-btn">Entrar</button>
            </form>
          ) : (
            <form className="formulario" onSubmit={handleRegister}>
              <input
                type="text"
                placeholder="Primer nombre"
                required
                value={registerInputs.first_name}
                onChange={e => setRegisterInputs({ ...registerInputs, first_name: e.target.value })}
              />
              <input
                type="text"
                placeholder="Apellido"
                required
                value={registerInputs.last_name}
                onChange={e => setRegisterInputs({ ...registerInputs, last_name: e.target.value })}
              />
              <input
                type="text"
                placeholder="Nombre de usuario"
                required
                value={registerInputs.username}
                onChange={e => setRegisterInputs({ ...registerInputs, username: e.target.value })}
              />
              <input
                type="password"
                placeholder="Contraseña"
                required
                value={registerInputs.password}
                onChange={e => setRegisterInputs({ ...registerInputs, password: e.target.value })}
              />
              <input
                type="email"
                placeholder="Correo electrónico"
                required
                value={registerInputs.email}
                onChange={e => setRegisterInputs({ ...registerInputs, email: e.target.value })}
              />
              <button type="submit" className="form-btn">Registrarse</button>
            </form>
          )}
        </div>
      </div>
    </div>
  );
}