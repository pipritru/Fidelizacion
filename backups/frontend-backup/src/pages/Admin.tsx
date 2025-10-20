import React, { useState } from "react";
import { FaTachometerAlt, FaUsers, FaBoxOpen, FaChartBar, FaCog } from "react-icons/fa";
import { IoIosCloseCircle } from "react-icons/io";
import { MdOutlineReadMore } from "react-icons/md";
import avatar from "../assets/avatar.png";
import "./admin.css";
import Dashboard from "../components/Dashboard/Dashboard"
import Usuarios from "../components/Usuarios/Usuarios"

export default function Admin() {
  const [showSidebar, setShowSidebar] = useState(true);
  const [activeSection, setActiveSection] = useState("dashboard");


  return (
    <div className="admin-dashboard">
      <div className={`sidebar ${showSidebar ? "expanded" : "collapsed"}`}
        style={{
          width: showSidebar ? "300px" : "90px",
          transition: "width 0.3s cubic-bezier(.4,0,.2,1)",
          background: "#1976d2",
          height: "100vh",
          position: "relative",
          overflow: "hidden"
        }}
      >
        {showSidebar && (
          <div
            style={{
              position: "absolute",
              top: "18px",
              right: "18px",
              zIndex: 2
            }}
          >
            <IoIosCloseCircle
              size={28}
              color="#ff4f4f"
              style={{ cursor: "pointer" }}
              onClick={() => setShowSidebar(false)}
            />
          </div>
        )}
        {showSidebar && (
          <div className="sidebar-profile" style={{ marginTop: "30px" }}>
            <img src={avatar} alt="Perfil" className="sidebar-avatar" />
            <span className="sidebar-name">Admin</span>
          </div>
        )}

        {/* Menú */}
        <ul className={showSidebar ? "sidebar-menu" : "sidebar-menu-icons"}>
            <li onClick={() => setActiveSection("dashboard")}><FaTachometerAlt /> {showSidebar && "Dashboard"}</li>
            <li onClick={() => setActiveSection("usuarios")}><FaUsers /> {showSidebar && "Usuarios"}</li>
            <li><FaBoxOpen /> {showSidebar && "Productos"}</li>
            <li><FaChartBar /> {showSidebar && "Reportes"}</li>
            <li><FaCog /> {showSidebar && "Configuración"}</li>
        </ul>

        {/* Botón para abrir*/}
        {!showSidebar && (
          <div
            style={{
              position: "absolute",
              bottom: "30px",
              left: "50%",
              transform: "translateX(-50%)"
            }}
          >
            <MdOutlineReadMore
              size={32}
              color="#fff"
              style={{ cursor: "pointer" }}
              onClick={() => setShowSidebar(true)}
            />
          </div>
        )}
      </div>
      {/* El resto del contenido principal va aquí */}
      <div className="admin-link-components">
        {activeSection === "dashboard" && <Dashboard />}
        {activeSection === "usuarios" && <Usuarios />}
        {/* Agrega aquí los otros componentes según la sección */}
      </div>
    </div>
  );
}