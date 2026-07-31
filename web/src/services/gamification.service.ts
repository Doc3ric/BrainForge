import { apiClient } from '../lib/api-client';
import { QueryClient } from '@tanstack/react-query';

export const gamificationKeys = {
  all: ['gamification'] as const,
  dailyGoals: () => [...gamificationKeys.all, 'daily-goals'] as const,
  streak: () => [...gamificationKeys.all, 'streak'] as const,
  achievements: () => [...gamificationKeys.all, 'achievements'] as const,
  progress: () => [...gamificationKeys.all, 'progress'] as const,
  xpHistory: () => [...gamificationKeys.all, 'xp-history'] as const,
};

export const invalidateGamification = (queryClient: QueryClient) => {
  return queryClient.invalidateQueries({ queryKey: gamificationKeys.all });
};

export const getDailyGoals = async () => {
  const { data } = await apiClient.get('/daily-goals');
  return data.data;
};

export const getStreak = async () => {
  const { data } = await apiClient.get('/streaks');
  return data.data;
};

export const getAchievements = async () => {
  const { data } = await apiClient.get('/achievements');
  return data.data;
};

export const getProgress = async () => {
  const { data } = await apiClient.get('/progress');
  return data.data;
};

export const getXpHistory = async () => {
  const { data } = await apiClient.get('/xp/history');
  return data.data;
};
