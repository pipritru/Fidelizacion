import axios from "axios";

const api = axios.create({
  baseURL: "http://127.0.0.1:8000", // tu backend Laravel
  withCredentials: true, // importante si usas Sanctum
});

export default api;