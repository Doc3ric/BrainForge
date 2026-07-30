'use client';

import { useEffect } from 'react';
import { useRouter, usePathname } from 'next/navigation';
import { useAuthStore } from '@/stores/auth.store';
import { useAuth } from '@/hooks/useAuth';

const PUBLIC_ROUTES = ['/login', '/register', '/'];

export default function AuthProvider({ children }: { children: React.ReactNode }) {
  const router = useRouter();
  const pathname = usePathname();
  useAuth(); // Triggers the /auth/me fetch on mount
  const isAuthenticated = useAuthStore((state) => state.isAuthenticated);
  const isLoading = useAuthStore((state) => state.isLoading);

  useEffect(() => {
    if (!isLoading) {
      if (!isAuthenticated && !PUBLIC_ROUTES.includes(pathname)) {
        // Unauthenticated user trying to access protected route
        router.push('/login');
      } else if (isAuthenticated && (pathname === '/login' || pathname === '/register')) {
        // Authenticated user trying to access auth pages
        router.push('/dashboard'); // Placeholder for future phase
      }
    }
  }, [isAuthenticated, isLoading, pathname, router]);

  if (isLoading) {
    return <div className="flex h-screen items-center justify-center">Loading session...</div>;
  }

  return <>{children}</>;
}
