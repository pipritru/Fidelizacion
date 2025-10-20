// Componente UI-only para añadir/editar usuarios
import React from "react";
import "./FormUsuario.css";

type Mode = "add" | "edit";

interface Props {
  mode?: Mode;
  initialValues?: {
    nombre?: string;
    email?: string;
    titulo?: string;
    tipo?: string;
    id?: number | string;
  };
  onClose: () => void;
  onSave?: (user: any) => void; // UI-only: devuelve el objeto al padre
}

export default function FormUsuario({ mode = "add", initialValues = {}, onClose, onSave }: Props) {
  const title = mode === "add" ? "Añadir nuevo usuario" : "Editar usuario";

  const [nombre, setNombre] = React.useState(initialValues.nombre || "");
  const [email, setEmail] = React.useState(initialValues.email || "");
  const [titulo, setTitulo] = React.useState(initialValues.titulo || "");
  const [tipo, setTipo] = React.useState(initialValues.tipo || "User");

  function handleSave() {
    const payload = { id: initialValues.id || undefined, name: nombre, username: nombre, email, titulo, role: tipo };
    if (onSave) onSave(payload);
    onClose();
  }

  return (
    <div className="fu-backdrop">
      <div className="fu-modal">
        <header className="fu-header">
          <h3>{title}</h3>
          <button className="fu-close" onClick={onClose} aria-label="Cerrar">×</button>
        </header>

        <div className="fu-body">
          <label>
            Nombre completo
            <input value={nombre} onChange={e => setNombre(e.target.value)} placeholder="Nombre completo" />
          </label>

          <label>
            Email
            <input value={email} onChange={e => setEmail(e.target.value)} placeholder="email@ejemplo.com" />
          </label>

          <div className="fu-row">
            <label>
              Título
              <input value={titulo} onChange={e => setTitulo(e.target.value)} placeholder="Título" />
            </label>

            <label>
              Tipo de usuario
              <select value={tipo} onChange={e => setTipo(e.target.value)}>
                <option>User</option>
                <option>Admin</option>
                <option>Guest</option>
              </select>
            </label>
          </div>
        </div>

        <footer className="fu-footer">
          <button className="fu-cancel" onClick={onClose}>Cancelar</button>
          <button className="fu-submit" onClick={handleSave}>{mode === "add" ? "Agregar usuario" : "Guardar cambios"}</button>
        </footer>
      </div>
    </div>
  );
}