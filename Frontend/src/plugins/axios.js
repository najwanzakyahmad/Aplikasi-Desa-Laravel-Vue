// src/plugins/axios.js
import axios from "axios";
import Cookies from "js-cookie";

const axiosInstance = axios.create({
  baseURL: import.meta.env.VITE_API_URL,
  headers: {
    Accept: "application/json",
    // Content-Type biasanya di-set otomatis oleh axios.
    // Kalau perlu multipart/json, set di call masing-masing.
  },
  withCredentials: false, // ubah ke true kalau butuh cookie cross-site
});

axiosInstance.interceptors.request.use((config) => {
  const token = Cookies.get("token");
  if (token) {
    config.headers.Authorization = `Bearer ${token}`;
  }
  return config;
});

export default axiosInstance;
