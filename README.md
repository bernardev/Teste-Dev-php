# API de Cadastro de Clientes (Laravel 10 + PHP 8)

## Desenvolvido por Eduardo Bernardes

Este projeto foi desenvolvido como parte do processo seletivo para a vaga de Desenvolvedor de Software Full Stack - Pleno na AllStrategy, atendendo a todos os requisitos funcionais, técnicos e diferenciais solicitados.

A API realiza o cadastro, listagem, atualização e exclusão de clientes, com validações específicas como CPF válido e único, e-mail único e integração automática com a BrasilAPI para preenchimento de endereço via CEP.

O projeto foi desenvolvido com foco em boas práticas, organização de código, segurança, testes automatizados de integração e otimização de desempenho com cache.

---

## Tecnologias utilizadas

- PHP 8.2
- Laravel 10.x
- MySQL 8.x
- Docker (PHP + Nginx + MySQL)
- BrasilAPI (validação de CEP)
- PHPUnit (testes de integração)
- Cache via `Cache::remember()` e `Cache::forget()`

---

## Instalação e configuração

### Clonando o projeto (fork realizado conforme instruções da vaga)
```bash
git clone https://github.com/seu-usuario/Teste-Dev-php.git
cd Teste-Dev-php
```

### Subindo o ambiente com Docker
```bash
docker-compose up -d
```

### Acessando o container da aplicação
```bash
docker exec -it laravel_app bash
```

### Instalando as dependências
```bash
apt-get update && apt-get install -y unzip git zip curl
curl -sS https://getcomposer.org/installer | php
mv composer.phar /usr/local/bin/composer
composer --version
composer install
```

### Configurando o arquivo `.env`
```bash
cp .env.example .env
```

No `.env`, ajuste as variáveis de banco:
```
DB_CONNECTION=mysql
DB_HOST=laravel_mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=admin
```

### Gerando a key da aplicação
```bash
php artisan key:generate
```

### Rodando as migrations
```bash
php artisan migrate
```

### Acessando a aplicação no navegador
```
http://localhost:8000
```

---

## Como utilizar a API

### Criar um cliente (POST `/api/clients`)
Exemplo de JSON para cadastro:
```json
{
  "full_name": "Eduardo Bernardes",
  "cpf": "39053344705",
  "email": "eduardo.bernardes@example.com",
  "phone": "41999887766",
  "cep": "81280-350"
}
```
> ⚠️ O endereço (logradouro, bairro, cidade, estado) é preenchido automaticamente pela BrasilAPI.

---

### Listar clientes (GET `/api/clients`)

#### Filtros disponíveis via query string:
- `full_name`
- `cpf`
- `cep`
- `per_page` (para paginação)

Exemplo:
```
GET /api/clients?full_name=Eduardo
GET /api/clients?full_name=Eduardo&per_page=5
```

---

### Editar cliente (PUT ou PATCH `/api/clients/{id}`)
Exemplo para atualizar o e-mail:
```json
{
  "email": "novo.email@example.com"
}
```
> A API permite atualização parcial (`PATCH`) ou total (`PUT`).

---

### Excluir cliente (DELETE `/api/clients/{id}`)
Deleta um cliente pelo ID.

---

## Requisitos atendidos (conforme orientado na descrição do projeto)

- ✅ Cadastro completo de cliente com CPF validado e único.
- ✅ E-mail validado e único.
- ✅ Validação automática de endereço via CEP (BrasilAPI).
- ✅ Listagem com filtros por nome, CPF e CEP.
- ✅ Paginação configurável.
- ✅ Edição (PUT e PATCH) com atualização parcial ou total.
- ✅ Exclusão de cliente.
- ✅ Mensagens de resposta padronizadas e tratamento de erros.

---

## Testes automatizados

- Abordagem escolhida: **Testes de integração**.
- Motivo de ter escolhido: Fluxos completos de CRUD, garantindo a validação entre controllers, repositories, banco de dados e respostas da API.
- Ferramenta: PHPUnit.
- Como rodar os testes:
```bash
php artisan test
```

### Testes cobrem:
- Criação de cliente com sucesso.
- Bloqueio de CPF e e-mail duplicados.
- Validação de CPF e CEP inválidos.
- Atualização de cliente (PUT/PATCH).
- Exclusão de cliente.
- Paginação e filtros na listagem.

---

## Cache e otimização de desempenho

- Cache implementado na listagem de clientes (`GET /api/clients`) utilizando `Cache::remember()`.
- Chave de cache gerada dinamicamente com base nos filtros aplicados.
- Cache invalidado automaticamente ao criar, atualizar ou excluir clientes, garantindo sempre a consistência das informações.

---

## Considerações importantes

- Frontend: A entrega foi exclusivamente da API back-end, conforme solicitado na vaga. Não há camada de front-end, pois o escopo definido foi a API REST.
- Escolha por testes de integração: Optou-se pelos testes de integração em vez de testes unitários puros para validar corretamente os fluxos completos entre todas as camadas (controllers, validations, repositories, banco de dados e responses), garantindo uma cobertura prática e eficaz.

---

## Sobre mim

Projeto desenvolvido por Eduardo Bernardes como parte do processo seletivo para Desenvolvedor de Software Full Stack - Pleno.  
Focado em boas práticas, clareza de código, segurança e eficiência.

---

## Licença

Este projeto foi desenvolvido exclusivamente para fins de avaliação técnica.