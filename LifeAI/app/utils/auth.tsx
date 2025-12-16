// app/utils/auth.ts
import { setItem, getItem, removeItem } from './storage';

const KEYCLOAK_DOMAIN = 'http://localhost:8080';
const REALM = 'lifeai';
const CLIENT_ID = 'frontend';

/**
 * Decode JWT payload
 */
export function decodeJwt(token: string) {
  try {
    return JSON.parse(atob(token.split('.')[1]));
  } catch {
    return null;
  }
}

/**
 * Refresh the access token using the refresh token
 */
export async function refreshToken(): Promise<any | null> {
  try {
    const refresh_token = await getItem('refreshToken');
    if (!refresh_token) return null;

    const tokenResponse = await fetch(
      `${KEYCLOAK_DOMAIN}/realms/${REALM}/protocol/openid-connect/token`,
      {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({
          grant_type: 'refresh_token',
          client_id: CLIENT_ID,
          refresh_token,
        }).toString(),
      }
    ).then(res => res.json());

    if (!tokenResponse.access_token) return null;

    await setItem('accessToken', tokenResponse.access_token);
    if (tokenResponse.refresh_token) {
      await setItem('refreshToken', tokenResponse.refresh_token);
    }

    const userInfo = decodeJwt(tokenResponse.access_token);
    await setItem('user', JSON.stringify(userInfo));

    return userInfo;
  } catch (err) {
    console.error('Failed to refresh token:', err);
    return null;
  }
}

/**
 * Logout user
 */
export async function logout() {
  await removeItem('accessToken');
  await removeItem('refreshToken');
  await removeItem('user');
}

/**
 * Save access token, refresh token, and user info
 */
export async function saveAuthTokens(accessToken: string, refreshToken: string) {
  await setItem('accessToken', accessToken);
  await setItem('refreshToken', refreshToken);
  const userInfo = decodeJwt(accessToken);
  if (userInfo) {
    await setItem('user', JSON.stringify(userInfo));
  }
}
