# Projeto PerfectPay

## Instalação e Configuração

### 1. Clone o Repositório
Clone o repositório para o seu ambiente local usando o comando:

```
git clone https://gitlab.com/taylanatreu/perfectpay.git
```

### 2. Navegue até a Raiz do Projeto
Após clonar o repositório, navegue até a raiz do projeto:

```
cd nome-do-repositorio
```

### 3. Instale as Dependências
Instale as dependências do projeto com o Composer:

```
composer install
```

### 4. Configuração do Ambiente
Crie um arquivo .env na raiz do projeto (se ainda não existir) e adicione as variáveis de ambiente necessárias. Certifique-se de incluir o seu token do Asaas:

```
ASAAS_TOKEN=seu_token_aqui
```

### 5. Gere a Chave de Aplicação
Gere a chave de aplicação do Laravel:

```
php artisan key:generate
```

### 6. Configure o Banco de Dados
Caso utilize um banco de dados, configure a conexão no arquivo .env:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nome_do_banco
DB_USERNAME=usuario
DB_PASSWORD=senha
```

### 7. Execute as Migrations (Se Aplicável)
Se o projeto usa banco de dados, execute as migrations para configurar o esquema do banco de dados:

```
php artisan migrate
```

### 8. Inicie o Servidor de Desenvolvimento
Inicie o servidor embutido do Laravel:

```
php artisan serve
```

O servidor estará disponível em http://localhost:8000.

### 9. Rodar Testes
Para rodar os testes do projeto, utilize:

```
vendor/bin/phpunit
```