## Admin painel

#### ⏳ Ponto de partida

Configure o arquivo `.env` na raiz do projeto, use como base o `.env.copy`

### ✋ Requisitos

**Node:**

- NodeJS 20.x

### 🛠️ Desenvolvimento
**Docker:**

```bash
# Subir container docker 
$ docker compose up -d

# accessa o container node
$ docker exec -it frontend-admin-nuxt sh

# Instalar dependencias
$ npm install

# Subir servidor em localhost:9020
$ npm run dev
```

**Node:**
```bash
# Instalar dependencias
$ npm install

# Subir servidor em localhost:9020
$ npm run dev
```

### 🛠 Tecnologias

- [Nuxt.js docs](https://nuxtjs.org)
- [Vue.js](https://vuejs.org/v2/guide/)
- [Sass](https://sass-lang.com/)
- [TypeScript](typescriptlang.org/)

