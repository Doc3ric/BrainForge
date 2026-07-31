import { useQuery } from '@tanstack/react-query';
import {
  gamificationKeys,
  getDailyGoals,
  getStreak,
  getAchievements,
  getProgress,
  getXpHistory,
} from '../services/gamification.service';

export const useDailyGoals = () => {
  return useQuery({
    queryKey: gamificationKeys.dailyGoals(),
    queryFn: getDailyGoals,
  });
};

export const useStreak = () => {
  return useQuery({
    queryKey: gamificationKeys.streak(),
    queryFn: getStreak,
  });
};

export const useAchievements = () => {
  return useQuery({
    queryKey: gamificationKeys.achievements(),
    queryFn: getAchievements,
  });
};

export const useProgress = () => {
  return useQuery({
    queryKey: gamificationKeys.progress(),
    queryFn: getProgress,
    staleTime: 60000, // cache for 60 seconds (Amendment 6)
  });
};

export const useXpHistory = () => {
  return useQuery({
    queryKey: gamificationKeys.xpHistory(),
    queryFn: getXpHistory,
  });
};
