import { BrowserRouter, Routes, Route } from "react-router-dom";
import Login from "./pages/Login_Singup";
import Home from "./pages/app";
import Dashboard from "./pages/Dashboard";
import Admin from "./pages/admin";


function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<Login />} />
        <Route path="/home" element={< Home />} />
        <Route path="/dashboard" element={< Dashboard />} />
        <Route path="/admin" element={< Admin />} />

      </Routes>
    </BrowserRouter>
  );
}

export default App;
