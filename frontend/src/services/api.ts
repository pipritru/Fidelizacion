import axios from "axios";

const api = axios.create({
  baseURL: "http://localhost:8000", // tu backend Laravel
  withCredentials: true, // importante si usas Sanctum
});

export default api;