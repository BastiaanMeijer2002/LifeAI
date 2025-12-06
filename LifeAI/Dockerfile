# ---------- Development ----------
FROM node:20-alpine AS dev

WORKDIR /app

RUN npm install -g expo-cli

COPY package*.json ./
RUN npm install

COPY . .

EXPOSE 19000 19001 19002 8081

CMD ["npm", "run", "web"]

# ---------- Production Web ----------
FROM node:20-alpine AS builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .

RUN npm run build:web

FROM nginx:alpine

RUN rm -rf /usr/share/nginx/html/*

COPY --from=builder /app/web-build /usr/share/nginx/html

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
