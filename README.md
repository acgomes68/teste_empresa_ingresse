# teste-ingresse
API RESTful desenvolvida em PHP/Laravel 5.6 e SQLite para teste na empresa Ingresse. Tecnologias utilizadas e suas respectivas versões:

- Docker version 18.06.0-ce, build 0ffa825
- docker-compose version 1.8.0, build unknown
- nginx version: nginx/1.12.2
- PHP 7.1.17 (cli) (built: May 3 2018 17:39:19) ( NTS )
- Composer version 1.6.5 2018-05-04 11:44:59
- Laravel 5.6
- SQLite
- Supervisor 3.3.3
- curl 7.61.0 (x86_64-alpine-linux-musl) libcurl/7.61.0 LibreSSL/2.6.5 zlib/1.2.11 libssh2/1.8.0


# Descrição
API composta pelas ações de CRUD de um cadastro de usuários. São chamadas REST com comunicação via Json desenvolvidas em PHP 7.1 através do framework Laravel 5.6. Como é uma API que tem como fundamento a comunicação com a base de dados e o retorno Json, a mesma não possui views, apenas as chamadas HTTP com os verbos GET, POST, PUT e DELETE. Foi utilizado o SQLite para armazenamento dos dados.

Além da aplicação, foram criados vários testes automatizados que são executados pelo PHPUnit e, ao final, gerado um relatório de cobertura de testes (Code Coverage) no formato HTML.


# Instalação
Criar pasta de trabalho (Ex.: teste-ingresse) e acessar a mesma:

mkdir teste-ingresse

cd teste-ingresse


Ativar o git e baixar o repositório:

git init

git pull https://github.com/acgomes68/teste-ingresse.git


Utilização de container Docker. Caso não tenha o Docker instalado, baixar e instalar a versão de acordo com seu sistema operacional em:

https://www.docker.com/community-edition#/download


Usar o Docker Compose que pode ser encontrado no link abaixo:

https://docs.docker.com/compose/install/


Voltando ao terminal na pasta de trabalho, executar:

docker pull acgomes68/webserver

Isso irá baixar a imagem com a infra necessária que está hospedada no Docker Hub em:

https://hub.docker.com/r/acgomes68/webserver/


# Execução
Ainda no terminal, executar o shell script "run_ingresse.sh":

sh run_ingresse.sh

O script irá instalar as dependências do projeto e subirá o container. Após a execução, abrir o navegador e apontar para o endpoint abaixo:

http://localhost:8080/api/users

Será exibido o conteúdo referente ao verbo HTTP GET que lista todos os usuários cadastrados na base em formato JSON.

# Testes
Foram realizados testes manuais e automáticos. Os primeiros foram utilizados para dar apoio rápido ao desenvolvimento e os últimos foram criados para execução com o PHPUnit. Junto com os testes também foi gerado um relatório de cobertura que será abordado mais adiante.

# Teste manual da API
Os testes manuais são básicos e serviram apenas para verificar o acesso ao endpoint, as validações, formatos e parâmetros de entrada e saída. Os testes manuais foram realizados através do Postman:

https://www.getpostman.com/apps

Os verbos HTTP testados foram os seguintes:

GET - Retorna todos os usuários

http://localhost:8080/api/users

GET - Lista um usuário específico

http://localhost:8080/api/users/{id}

onde:

{id} - ID único do usuário criado no momento da inclusão

Validação

- Id: Id existente na base de dados


POST - Adiciona um usuário

http://localhost:8080/api/users

Parâmetros de entrada em formato JSON. Exemplo:

{

	"name": "Tiago Campos",
	"email": "tiago.campos@email.com",
	"password": "123456"

}

Validação
- Nome: Obrigatório com máximo de 255 caracteres
- Email: Obrigatório, formato válido com máximo de 255 caracteres e único na base
- Senha: obrigatório com mínimo de 6 caracteres (convertido para um hash antes de adicionar a base de dados)


PUT - Atualiza dados de um usuário específico

http://localhost:8080/api/users/{id}

onde: {id} - ID único do usuário criado no momento da inclusão

Parâmetros de entrada em formato JSON. Exemplo:

{

	"name": "Antonio Gomes",
	"email": "antonio.gomes@email.com",
	"password": "123456"

}

Validação
- Id: Id existente na base de dados
- Nome: Obrigatório com máximo de 255 caracteres
- Email: Obrigatório, formato válido com máximo de 255 caracteres e único na base
- Senha: obrigatório com mínimo de 6 caracteres (convertido para um hash antes de adicionar a base de dados)


DELETE - Exclui um usuário específico

http://localhost:8080/api/users/{id}

onde: {id} - ID único do usuário criado no momento da inclusão

Validação
- Id: Id existente na base de dados


# Teste automático da API
Os testes foram criados segundo o padrão do PHPUnit visto que o mesmo já está presente no framework para essa função. Foi criado um arquivo de classe para cada verbo HTTP e em cada um deles seus respectivos métodos com testes específicos. Os arquivos de teste estão em:

app/tests/Unit/User

app/tests/Feature/User


para executar os testes basta ir no terminal na pasta app/ e executar:

vendor/bin/phpunit

Isso fará com que todos os testes sejam executados.


Para executar o teste de uma classe ou arquivo:

vendor/bin/phpunit --filter <nome_classe> <path_arquivo>

Ex.:

vendor/bin/phpunit --filter UserModelUnitTest tests/Unit/User/UserModelUnitTest.php



Para executar o teste de um método de uma classe ou arquivo:

vendor/bin/phpunit --filter <nome_metodo> <nome_classe> <path_arquivo>

Ex.:

vendor/bin/phpunit --filter testDelete UserModelUnitTest tests/Unit/User/UserModelUnitTest.php


O relatório de cobertura de testes foi gerado em:

app/tests/cover/

Basta dar um duplo clique no arquivo index.html que o mesmo será aberto no navegador onde há um quadro de resumo com a possibilidade de se aprofundar em cada dos testes realizados clicando nos respectivos links.

Caso exista a necessidade de se gerar novos testes, para atualizar o teste de cobertura, basta executar dentro da pasta app/:

vendor/bin/phpunit --whitelist /app --coverage-html tests/cover/

Com isso, os testes serão novamente aplicados e uma nova versão do relatório de cobertura será gerado no mesmo local.

Para os testes contínuos de integração que são aplicados diretamente a cada push do código no repositório o próprio Laravel sugere algumas alternativas em:

https://laravel.com/docs/5.6/dusk#continuous-integration


Para o caso do Travis CI que testa inclusive as práticas e padrões utilizados na codificação PHP e Javascript:

https://www.fastfwd.com/continuous-integration-laravel-travis-ci/





