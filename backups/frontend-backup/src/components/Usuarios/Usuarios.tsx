import React from "react";
import "./Usuarios.css";
import UsuarioCard from "../UsuarioCard";

export default function Usuarios() {
  const usuarios = [
    { username: "admin", email: "admin@correo.com", estado: "Verificado", rol: "Administrador" },
    { username: "user1", email: "user1@correo.com", estado: "No verificado", rol: "Usuario" },
    { username: "user2", email: "user2@correo.com", estado: "Verificado", rol: "Usuario" },
  ];

  return (
    <div className="usuarios-container">
      <div className="usuarios-header">
        <h2>Usuarios</h2>
        <button className="usuarios-btn">+ Nuevo usuario</button>
      </div>
      <div className="usuarios-toolbar">
        <div>
          <span className="usuarios-label">Mostrar</span>
          <select className="usuarios-select">
            <option>10</option>
            <option>20</option>
            <option>50</option>
          </select>
          <span className="usuarios-label">registros</span>
        </div>
        <input
          type="text"
          className="usuarios-search"
          placeholder="Buscar usuario..."
        />
      </div>
      <hr className="usuarios-divider" />
      <div className="usuarios-lista" style={{ display: "flex", flexWrap: "wrap" }}>
        {usuarios.map((u, idx) => (
          <UsuarioCard
            key={idx}
            username={u.username}
            email={u.email}
            estado={u.estado}
            rol={u.rol}
          />
        ))}
      </div>
    </div>
  );
}