# endereco
Salvar listagem xls de ceps e salvar os respectivos endereços.

# Instalação
Clone o repositório para sua máquina local:

    git clone https://github.com/pradovolnei/endereco

Instale as dependências do projeto usando o Composer:

    composer install

Renomeie o arquivo .env.example para .env:

Abra o arquivo .env e configure as variáveis de ambiente, como as credenciais do banco de dados, a URL do aplicativo, etc.

Execute as migrações do banco de dados para criar as tabelas necessárias:

    php artisan migrate

Inicie o servidor de desenvolvimento:

    php artisan serve

Abra um navegador da web e acesse a URL fornecida pelo servidor de desenvolvimento.

# OBS
Por algum motivo, meu composer local não estava permitindo a instalação de bibliotecas para ler arquivos xls.
Por isso eu fiz essa carga em PHP puro.





