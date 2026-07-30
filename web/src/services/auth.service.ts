import { apiClient } from '@/lib/api-client';
import { User } from '@/stores/auth.store';

interface AuthResponse {
  data: User;
  meta: unknown;
  links: unknown;
}

export const authService = {
  async getCsrfToken() {
    await apiClient.get('/sanctum/csrf-cookie');
  },

  async login(credentials: Record<string, unknown>): Promise<AuthResponse> {
    await this.getCsrfToken();
    const response = await apiClient.post<AuthResponse>('/api/v1/auth/login', credentials);
    return response.data;
  },

  async register(data: Record<string, unknown>): Promise<AuthResponse> {
    await this.getCsrfToken();
    const response = await apiClient.post<AuthResponse>('/api/v1/auth/register', data);
    return response.data;
  },

  async logout(): Promise<void> {
    await apiClient.post('/api/v1/auth/logout');
  },

  async getCurrentUser(): Promise<AuthResponse> {
    const response = await apiClient.get<AuthResponse>('/api/v1/auth/me');
    return response.data;
  }
};
