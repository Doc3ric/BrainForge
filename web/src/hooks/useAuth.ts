import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { authService } from '@/services/auth.service';
import { useAuthStore } from '@/stores/auth.store';
import toast from 'react-hot-toast';

export const useAuth = () => {
  const queryClient = useQueryClient();
  const setAuth = useAuthStore((state) => state.setAuth);
  const clearAuth = useAuthStore((state) => state.clearAuth);
  const setLoading = useAuthStore((state) => state.setLoading);

  const loginMutation = useMutation({
    mutationFn: authService.login.bind(authService),
    onSuccess: (response: { data: import('@/stores/auth.store').User }) => {
      setAuth(response.data);
      toast.success('Logged in successfully!');
    },
    onError: (error: unknown) => {
      const err = error as { response?: { data?: { message?: string } } };
      toast.error(err?.response?.data?.message || 'Login failed');
    },
  });

  const registerMutation = useMutation({
    mutationFn: authService.register.bind(authService),
    onSuccess: () => {
      toast.success('Registration successful! Please log in.');
    },
    onError: (error: unknown) => {
      const err = error as { response?: { data?: { message?: string } } };
      toast.error(err?.response?.data?.message || 'Registration failed');
    },
  });

  const logoutMutation = useMutation({
    mutationFn: authService.logout.bind(authService),
    onSuccess: () => {
      clearAuth();
      queryClient.clear();
      toast.success('Logged out successfully');
    },
    onError: () => {
      toast.error('Logout failed');
    },
  });

  // Query to fetch current user on initial load
  const { data: userResponse, isLoading: isUserLoading } = useQuery({
    queryKey: ['authUser'],
    queryFn: async () => {
      setLoading(true);
      try {
        const res = await authService.getCurrentUser();
        setAuth(res.data);
        return res;
      } catch (error) {
        clearAuth();
        throw error;
      } finally {
        setLoading(false);
      }
    },
    retry: false, // Don't retry if unauthenticated
    refetchOnWindowFocus: false,
  });

  return {
    loginMutation,
    registerMutation,
    logoutMutation,
    userResponse,
    isUserLoading
  };
};
