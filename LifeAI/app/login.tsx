import React from 'react';
import { View, Text, Button } from 'react-native';

const KEYCLOAK_DOMAIN = 'http://localhost:8080';
const REALM = 'lifeai';
const CLIENT_ID = 'frontend';

export default function LoginScreen() {
  const handleLogin = () => {
    const redirectUri = window.location.origin + '/workouts';
    const authUrl =
      `${KEYCLOAK_DOMAIN}/realms/${REALM}/protocol/openid-connect/auth` +
      `?client_id=${CLIENT_ID}` +
      `&redirect_uri=${encodeURIComponent(redirectUri)}` +
      `&response_type=code` +
      `&scope=openid profile email`;
    window.location.href = authUrl;
  };

  return (
    <View style={{ flex: 1, justifyContent: 'center', alignItems: 'center' }}>
      <Text style={{ fontSize: 24, marginBottom: 20 }}>Login with Keycloak</Text>
      <Button title="Login" onPress={handleLogin} />
    </View>
  );
}
