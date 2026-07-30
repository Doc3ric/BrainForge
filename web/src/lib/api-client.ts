import axios from 'axios';

export const apiClient = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL + '/api/v1',
  withCredentials: true, // required for Sanctum cookie auth
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});
