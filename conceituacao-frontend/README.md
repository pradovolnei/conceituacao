# 🎨 Documentação do Frontend

Esta documentação detalha as instruções para configurar e executar a aplicação **frontend** no ambiente de desenvolvimento local.  
A aplicação foi desenvolvida usando **Vue.js** e o gerenciador de pacotes **npm**.

---

## ✅ Pré-requisitos

Antes de iniciar, certifique-se de que você tenha instalado em sua máquina:

- **Node.js** (versão recomendada: LTS)  
- **npm** (ou **Yarn**, opcional)  

---

## ⚙️ Configuração do Projeto

### 1. Instalar as dependências
```bash
npm install
```

### 2. Criar o arquivo de ambiente
Na raiz da pasta `conceituacao-frontend`, crie um arquivo `.env`:

```bash
touch .env
```

### 3. Configurar a URL da API
Abra o arquivo `.env` e adicione a seguinte variável:

```env
VITE_API_URL=http://localhost:8000
```

> Substitua pelo endereço local ou remoto da sua API.

### 4. Iniciar o Servidor de Desenvolvimento
```bash
npm run dev
```

A aplicação estará disponível em:  
👉 [http://localhost:5173](http://localhost:5173) *(ou outra porta caso 5173 já esteja em uso)*

---

## 🔗 Integração com a API
A aplicação fará requisições para a API configurada na variável `VITE_API_URL`.

---

## 📝 Observações
- Recomenda-se o uso do **Node.js LTS** para evitar problemas de compatibilidade.  
- Caso utilize **Yarn**, basta substituir os comandos `npm` pelos equivalentes do Yarn.  
