import { useState, useEffect } from "react";
import "./Usuarios.css";
import UsuarioCard from "../UsuarioCard";
import FormUsuario from "../formularios/FormUsuario";
import api from "../../services/api";

export default function Usuarios() {
  const [usuarios, setUsuarios] = useState<any[] | null>(null);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState<string | null>(null);

  const [modalOpen, setModalOpen] = useState(false);
  const [modalMode, setModalMode] = useState("add");
  const [initialValues, setInitialValues] = useState<any>({});

  function openAdd() {
    setInitialValues({});
    setModalMode("add");
    setModalOpen(true);
  }

  function openEdit(u: any) {
    setInitialValues({ nombre: u.username, email: u.email, titulo: "", tipo: u.rol });
    setModalMode("edit");
    setModalOpen(true);
  }

  useEffect(() => {
    let mounted = true;
    setLoading(true);
    api.get("/api/users")
      .then(res => {
        if (!mounted) return;
        // mostrar en consola para depuración
        console.log("/api/users response:", res.data);
        const data = res.data?.data || res.data?.users || res.data || [];
        setUsuarios(Array.isArray(data) ? data : []);
      })
      .catch(err => {
        console.error("Error fetching users:", err);
        if (!mounted) return;
        setError("No se pudieron cargar los usuarios del servidor");
        setUsuarios([]);
      })
      .finally(() => {
        if (mounted) setLoading(false);
      });

    return () => { mounted = false; };
  }, []);

  return (
    <div className="usuarios-container">
      <div className="usuarios-header">
        <h2>Usuarios</h2>
        <button className="usuarios-btn" onClick={openAdd}>+ Nuevo usuario</button>
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
        {loading && <div style={{ padding: 24 }}>Cargando usuarios...</div>}
        {error && <div style={{ padding: 24, color: "#b00020" }}>{error}</div>}
        {!loading && usuarios && usuarios.length === 0 && <div style={{ padding: 24 }}>No hay usuarios para mostrar.</div>}
        {!loading && usuarios && usuarios.map((u, idx) => {
          console.debug('usuario item:', u);
          // obtener email desde varios posibles lugares
          const email = u.email
            || u.person?.email
            || (u.persons && u.persons.email)
            || (u.profile && u.profile.email)
            || (u.contact && u.contact.email)
            || (Array.isArray(u.emails) && u.emails[0])
            || "-";

          const username = u.username || u.name || u.first_name || "-";
          const rol = u.rol || u.role || (u.roles && u.roles[0] && u.roles[0].name) || "User";
          const estado = u.verified_at || u.verified ? "Verificado" : (u.estado || "No verificado");

          return (
            <UsuarioCard
              key={u.id || idx}
              username={username}
              email={email}
              estado={estado}
              rol={rol}
              onEdit={() => openEdit(u)}
            />
          );
        })}
      </div>

      {modalOpen && (
        <FormUsuario
          mode={modalMode as any}
          initialValues={initialValues}
          onClose={() => setModalOpen(false)}
          onSave={(u: any) => {
            // actualizar localmente la lista de usuarios (UI-only)
            setUsuarios(prev => {
              if (!prev) return [u];
              // si existe id, reemplazar
              if (u.id) {
                return prev.map(p => (p.id === u.id ? { ...p, ...u } : p));
              }
              // asignar id temporal
              const tempId = (prev.length ? Math.max(...prev.map(x => x.id || 0)) + 1 : 1);
              return [{ ...u, id: tempId }, ...prev];
            });
          }}
        />
      )}
    </div>
  );
}