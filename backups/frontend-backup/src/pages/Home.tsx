import React from "react";
import { useEffect } from "react";
import { ToastContainer, toast, Bounce } from "react-toastify";
import "react-toastify/dist/ReactToastify.css";

export default function Inicio() {
  useEffect(() => {
    toast.success("¡Bienvenido!", {
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
  }, []);
  return (
    <div>
      <ToastContainer />
      <h1>Página de inicio</h1>
      <p>Bienvenido al sistema de fidelización.</p>
    </div>
  );
}
