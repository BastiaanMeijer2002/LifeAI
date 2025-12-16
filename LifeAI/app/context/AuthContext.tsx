// app/context/AuthContext.tsx
import React, { createContext, useContext, useState, useEffect, ReactNode } from 'react';
import { useRouter } from 'expo-router';
import { getItem } from '../utils/storage';
import { refreshToken as refreshTokenHelper, decodeJwt, logout as logoutHelper } from '../utils/auth';

interface AuthContextValue {
  user: any | null;
  loading: boolean;
  refreshToken: () => Promise<void>;
  logout: () => void;
  setUser: (user: any) => void;
}

const AuthContext = createContext<AuthContextValue | undefined>(undefined);

export function AuthProvider({ children }: { children: ReactNode }) {
  const [user, setUser] = useState<any | null>(null);
  const [loading, setLoading] = useState(true);
  const router = useRouter();

  useEffect(() => {
    (async () => {
      const token = await getItem('accessToken'); // use storage.tsx
      if (token) {
        const userInfo = decodeJwt(token);
        setUser(userInfo);
      }
      setLoading(false);
    })();
  }, []);

  const refreshToken = async () => {
    const newUser = await refreshTokenHelper();
    if (newUser) setUser(newUser);
  };

  const logout = () => {
    logoutHelper();
    setUser(null);
    router.replace('/login');
  };

  return (
    <AuthContext.Provider value={{ user, loading, refreshToken, logout, setUser }}>
      {children}
    </AuthContext.Provider>
  );
}

export function useAuth() {
  const context = useContext(AuthContext);
  if (!context) {
    throw new Error('useAuth must be used within AuthProvider');
  }
  return context;
}
