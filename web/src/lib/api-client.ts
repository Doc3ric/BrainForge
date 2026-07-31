import axios from 'axios';

export const apiClient = axios.create({
  baseURL: process.env.NEXT_PUBLIC_API_URL + '/api/v1',
  withCredentials: true, // required for Sanctum cookie auth
  headers: {
    'Accept': 'application/json',
    'Content-Type': 'application/json',
  },
});

apiClient.interceptors.response.use((response) => {
  // If the request was a mutative action (POST, PUT, PATCH, DELETE)
  // and it was successful, we proactively invalidate gamification cache.
  const method = response.config.method?.toLowerCase();
  if (method && ['post', 'put', 'patch', 'delete'].includes(method)) {
    // We defer the import to avoid circular dependencies if needed, or import directly
    import('./query-client').then(({ queryClient }) => {
      import('../services/gamification.service').then(({ gamificationKeys }) => {
        queryClient.invalidateQueries({ queryKey: gamificationKeys.all });
      });
    });
  }
  return response;
});
