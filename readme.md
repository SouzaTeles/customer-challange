# Sistema de gerenciamento de clientes.

## Stack

**Backend:** PHP 8.5 + MySQL 8.0 + Nginx  
**Frontend:** Vue.js 3

## Como rodar

1. Suba os containers:
```bash
docker-compose up -d
```

2. Crie um usuário:
```bash
docker-compose exec php php scripts/create_user.php "Seu Nome" "seu@email.com" "senha123"
```

3. Acesse:
- Frontend: http://localhost:5173
- API: http://localhost/api

## Rodar testes

```bash
docker-compose exec php ./vendor/bin/phpunit
```

## Rotas da API

**Autenticação:**
- `POST /api/auth/login` - Login (email, password)
- `POST /api/auth/logout` - Logout

**Clientes:** (requer autenticação)
- `GET /api/customers` - Listar todos
- `GET /api/customers/:id` - Buscar por ID
- `POST /api/customers` - Criar (com endereços)
- `PUT /api/customers/:id` - Atualizar (substitui tudo)
- `DELETE /api/customers/:id` - Deletar (cascata nos endereços)

**Exemplo de body para criar/atualizar cliente:**
```json
{
  "name": "Souza Teles",
  "cpf": "12345678901",
  "email": "email@example.com",
  "birthDate": "1990-05-15",
  "rg": "MG-12.345.678",
  "phone": "31999990000",
  "addresses": [
    {
      "street": "Rua das Flores",
      "number": "123",
      "complement": "Apto 101",
      "neighborhood": "Centro",
      "zipCode": "30000-000",
      "city": "Teresópolis",
      "state": "RJ"
    }
  ]
}
```

## Estrutura


- **Controllers**: Recebe requisições HTTP
- **Services**: Regras de negócio
- **Repositories**: Acesso ao banco
- **Models**: Entidades
- **Routes**: Definição de rotas
- **tests**: Testes


## Decisões técnicas

- **Autenticação:** Baseado em sessão (cookies httpOnly)
- **Senhas:** Hash com Argon2ID
- **Relacionamentos:** Customer 1:N Address com ON DELETE CASCADE. Porém, removeria as constraints de relacionamento em produção por questão de desempenho
- **Router próprio:** Sem framework, controle total do ciclo de requisição
- **Injeção de dependência:** Manual via construtores

## A fazer

- Validação de dados (CPF, email, CEP)
- Paginação na listagem
- Rate limiting no login