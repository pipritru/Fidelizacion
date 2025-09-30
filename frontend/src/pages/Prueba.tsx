import React from "react";
import "./prueba.css";
import logo from "../assets/chuleteria_log.png";

export default function Prueba() {
  return (
    <div className="prueba-bg">
      <div className="prueba-container">
        <img src={logo} alt="logo chuleteria" style={{ width: 120, marginBottom: 24 }} />
        <h1 className="prueba-title">Página de Prueba</h1>
        <p className="prueba-text">Esta es una página de prueba para verificar la configuración del proyecto.</p>
      </div>
    </div>
  );
}