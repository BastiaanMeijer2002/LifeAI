import { Tabs } from 'expo-router';
import React from 'react';

import { HapticTab } from '@/components/haptic-tab';
import { Colors } from '@/constants/theme';
import { useColorScheme } from '@/hooks/use-color-scheme';
import { MaterialIcons } from '@expo/vector-icons';

export default function TabLayout() {
  const colorScheme = useColorScheme();

  return (
    <Tabs
      screenOptions={{
        tabBarActiveTintColor: Colors[colorScheme ?? 'light'].tint,
        headerShown: false,
        tabBarButton: HapticTab,
      }}
    >
      <Tabs.Screen
        name="workouts/index"
        options={{
          title: 'Workouts',
          tabBarIcon: ({ color, size }) => (
            <MaterialIcons size={size} name="directions-run" color={color} />
          ),
        }}
      />
      <Tabs.Screen
        name="exercises/index"
        options={{
          title: 'Exercises',
          tabBarIcon: ({ color, size }) => (
            <MaterialIcons size={size} name="fitness-center" color={color} />
          ),
        }}
      />
    </Tabs>
  );
}
