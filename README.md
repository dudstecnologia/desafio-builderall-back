## E-Commerce Builderall - Back-end

Prova:
Desenvolva um sistema ecommerce simples utilizando boas práticas, preferencialmente
vindas de metodologias como SOLID, KISS (bônus TDD/BDD).

Tecnologias:

* Frontend: SPA em Vue 2.5/3
* Backend: Api Laravel

Demonstração:
https://youtu.be/qWuhB6DEaNY

## Clone o projeto
```
git clone https://github.com/dudstecnologia/desafio-builderall-back.git
```

## Acesse a pasta do projeto clonado
```
cd desafio-builderall-back
```

## Criar o arquivo de variáveis baseado no modelo
```
cp .env.example .env
```

## Instalar as dependências do projeto
```
composer install
```

## Gerar a chave da aplicação
Esta chave é usada pelo serviço de criptografia da aplicação
```
php artisan key:generate
```

## Gerar a chave do JWT
Esta chave é usada para assinar os tokens de autenticação
```
php artisan jwt:secret
```

## Configurar o banco de dados
Neste momento, crie manualmente um banco de dados MySQL no seu servidor.

Após criar o banco, abra o arquivo '.env' que está na raiz do projeto e faça a alteração nas variáveis que são responsáveis pelo Banco de Dados elas são identificadas pelo prefixo 'DB_'.

Altere para que elas fiquem de acordo com o seu ambiente.
```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```
Caso esteja utilizando Laragon, Xampp ou WampServer, provavelmente só vai ser preciso alterar o nome do banco.

## Executar as migrations para preparar o banco de dados
Após o comando abaixo, o seu banco de dados vai ter as tabelas necessárias para o funcionamento da API. A flag --seed serve para popular o usuário default para acesso a área restrita do sistema.
```
php artisan migrate --seed
```

## Criar o link simbólico para que as imagens dos produtos sejam acesíveis pela Web
```
php artisan storage:link
```

## Iniciar a aplicação em modo de desenvolvimento
Se a porta 8000 não estiver ocupada, ela será usada nesse modo. Logo o endereço da API será: http://127.0.0.1:8000/api
```
php artisan serve
```

## Credenciais para acesso a área restrita
* email = admin@email.com
* senha = password
