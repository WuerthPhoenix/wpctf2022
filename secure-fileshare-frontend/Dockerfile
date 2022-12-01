# build stage
FROM rockylinux:8 as build-stage
RUN dnf -y module enable nodejs:14
RUN dnf -y install npm
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# production stage
FROM nginx:stable-alpine as production-stage
COPY --from=build-stage /app/dist /usr/share/nginx/html
COPY conf/nginx-api-proxy.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
CMD ["nginx", "-g", "daemon off;"]