import { useMutation, useQuery, useQueryClient } from '@tanstack/react-query';
import { authService } from '@/services/auth.service';
import { useAuthStore } from '@/stores/auth.store';
import toast from 'react-hot-toast';

export const useAuth = () => {
  const queryClient = useQueryClient();
  const setAuthenticated = useAuthStore((state) => state.setAuthenticated);

  const loginMutation = useMutation({
    mutationFn: authService.login.bind(authService),
    onSuccess: () => {
      setAuthenticated(true);
      queryClient.invalidateQueries({ queryKey: ['authUser'] });
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
      setAuthenticated(false);
      queryClient.clear();
      toast.success('Logged out successfully');
    },
    onError: () => {
      toast.error('Logout failed');
    },
  });

  const { data: userResponse, isLoading: isUserLoading } = useQuery({
    queryKey: ['authUser'],
    queryFn: async () => {
      try {
        const res = await authService.getCurrentUser();
        setAuthenticated(true);
        return res;
      } catch (error) {
        setAuthenticated(false);
        throw error;
      }
    },
    retry: false,
    refetchOnWindowFocus: false,
  });

  return {
    loginMutation,
    registerMutation,
    logoutMutation,
    user: userResponse?.data ?? null,
    isUserLoading
  };
};
