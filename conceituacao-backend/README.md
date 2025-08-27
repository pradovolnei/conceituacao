# 📘 Documentação da API

Esta documentação fornece as instruções necessárias para configurar e executar a API do projeto no ambiente de desenvolvimento local.  
A API foi desenvolvida usando o **Laravel 11**.

---

## ✅ Pré-requisitos

Antes de iniciar, certifique-se de que os seguintes requisitos estão instalados em sua máquina:

- **PHP**: Versão **8.2** ou superior  
- **Composer**: Gerenciador de dependências do PHP  
- **MySQL** (ou outro banco de dados compatível)  

> ⚠️ É necessário configurar o banco de dados no arquivo `.env`.

---

## ⚙️ Configuração do Projeto

### 1. Instalar as dependências
```bash
composer install
```

### 2. Copiar o arquivo de ambiente
```bash
cp .env.example .env
```

### 3. Gerar a chave da aplicação
```bash
php artisan key:generate
```

### 4. Configurar o Banco de Dados
Edite o arquivo `.env` e ajuste as credenciais do banco.  
Exemplo para MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nomedoseubanco
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Executar as Migrações
```bash
php artisan migrate
```

### 6. Configurar o Laravel Sanctum e CORS
Adicione no `.env`:

```env
SANCTUM_STATEFUL_DOMAINS=localhost:5173,127.0.0.1:5173
APP_CORS_ALLOWED_ORIGINS=http://localhost:5173,http://127.0.0.1:5173
```

### 7. Iniciar o Servidor
```bash
php artisan serve
```

A API estará disponível em:  
👉 [http://localhost:8000](http://localhost:8000)

---

## 📡 Endpoints da API

- **POST** `/login` → Autentica um usuário e retorna um token de sessão  
- **POST** `/register` → Cria um novo usuário  
- **POST** `/logout` → Desconecta o usuário autenticado  
- **GET** `/user` → Retorna os dados do usuário autenticado e seus perfis  

> 🔐 Endpoints adicionais (como `/profiles` e `/users`) requerem autenticação e permissão de administrador.

---

## 📝 Observações
- Certifique-se de configurar corretamente o **CORS** e o **Sanctum** para permitir a comunicação com o frontend.  
- Este projeto segue a estrutura padrão do **Laravel 11**.
