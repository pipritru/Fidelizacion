import React from "react";
import { FaTrash } from "react-icons/fa";
import { RiFileEditFill } from "react-icons/ri";
import "./UsuarioCard.css";

export default function UsuarioCard({ username, email, rol, estado, variant = "vertical" }) {
  const containerClass = variant === "simple" ? "usuario-card-simple" : "usuario-card-vertical";

  return (
    <div className="usuario-card-wrapper">
      <div className={containerClass}>
      <div className="usuario-avatar">
        <img src="/avatar.png" alt="avatar" />
      </div>

      {/* estado en flujo, debajo del avatar */}
      <div className={`usuario-estado-badge ${estado === "Activo" ? "activo" : "inactivo"}`}>
        {estado}
      </div>

      <div className="usuario-info">
        <div className="usuario-nombre-rol">
          <span className="usuario-username">{username}</span>
          <span className="usuario-rol">{rol}</span>
        </div>
  <div className="usuario-email">{email}</div>
      </div>

      {/* iconos de acción al final, solo iconos sin fondo por defecto */}
      <div className="usuario-acciones">
        <button className="accion-icon editar" title="Editar">
          <RiFileEditFill />
        </button>
        <button className="accion-icon eliminar" title="Eliminar">
          <FaTrash />
        </button>
      </div>
      </div>
    </div>
  );
} 