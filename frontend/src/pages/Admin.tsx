import React, { useState } from "react";
import { FaTachometerAlt, FaUsers, FaBoxOpen, FaChartBar, FaCog } from "react-icons/fa";
import { IoIosCloseCircle } from "react-icons/io";
import "./admin.css";

export default function Admin() {
  const [showSidebar, setShowSidebar] = useState(true);

  return (
    <div className="admin-dashboard">
      {!showSidebar && (
        <button className="open-sidebar-btn" onClick={() => setShowSidebar(true)}>
          Abrir Sidebar
        </button>
      )}
      {showSidebar && (
        <div className="sidebar" style={{ position: "relative" }}>
          <div className="sidebar-close-container">
            <IoIosCloseCircle
              className="close-sidebar-icon"
              size={40}
              color="#ff4f4f"
              onClick={() => setShowSidebar(false)}
              style={{ cursor: "pointer" }}
            />
          </div>
          <div className="sidebar-profile">
            <img src="/avatar.png" alt="Perfil" className="sidebar-avatar" />
            <span className="sidebar-name">Admin</span>
          </div>
          <ul className="sidebar-menu">
            <li><FaTachometerAlt /> Dashboard</li>
            <li><FaUsers /> Usuarios</li>
            <li><FaBoxOpen /> Productos</li>
            <li><FaChartBar /> Reportes</li>
            <li><FaCog /> Configuración</li>
          </ul>
        </div>
      )}
    </div>
  
  );
}