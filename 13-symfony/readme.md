# API com Symfony

## Rodando a aplicação

Instalar as dependências do projeto:

```terminal
composer install
```

Criar arquivo das variáveis de ambiente e definir as credênciais de acesso para um Banco MySQL:

```terminal
cp .env.example .env
```

Criar a Base de dados:

```terminal
php bin/console list doctrine:database:create
```

Rodar as _migrations_:

```terminal
php bin/console doctrine:migrations:migrate
```

Rodar as _fixtures_:

```terminal
php bin/console doctrine:fixtures:load
```

Levantar o servidor local:

```terminal
php -S localhost:8080 -t public
```

### Índice

1. [Configurando o ambiente](#1-configurando-o-ambiente)  
  1.1. Criando um projeto  
  1.2. Estrutura de arquivos  
  1.3. A primeira rota  
  1.4. Lendo dados da requisição  
2. [Entidades](#2-entidades)  
3. [Usando um ORM](#3-usando-um-orm)  
  3.1. CLI  
  3.2. Mapeando entidades e _migrations_  
  3.3. Criando um registro e _entity manager_  
  3.4. Recuperando registros e _repository_  
  3.4.1. Listando todos médicos  
  3.4.2. Recuperando um médico específico  
  3.4.3. Atualizando um médico  
  3.5. Factory  
  3.5.1. Abstraindo para um método  
4. [Configurando MySQL](#4-configurando-mysql)  
5. [Relacionamentos e uma nova entidade](#5-relacionamentos-e-uma-nova-entidade)  
  5.1. Gerando código pela CLI  
  5.2. Criando uma nova entidade  
  5.3. Definindo o relacionamento entre entidades  
  5.4. CRUD de Especialidade  
  5.5. Interface `JsonSerializable`  
  5.6. Corrigindo CRUD de médicos  
6. [Rotas com sub-recursos](#6-rotas-com-sub-recursos)  
  6.1. Criando um _repository_ para médicos  
7. [Aproveitando Código e `BaseController`](#7-aproveitando-código-e-`basecontroller`)  
  7.1 Interface para _factory_  
  7.2 Método abstrato  
8. [Melhorando as respostas](#8-melhorando-as-respostas)  
  8.1 Ordenação e filtro de resultados  
    8.1.1 Ordenando resultados  
    8.1.2 Filtrando resultados  
    8.1.3 Abstraindo a extração de dados da _request_  
  8.2 Paginando os resultados  
  8.3 Retornando informação extra e linkando recursos  
9. [Autenticação e proteção de rotas](#9-autenticação-e-proteção-de-rotas)  
  9.1 Pacotes necessários  
  9.2 Entidade `User`  
    9.2.1 _Fixtures_ do Doctrine  
  9.3 _Login_  
  9.4 Autenticador  
10. [Tratando erros](#10-tratando-erros)  
  10.1 Eventos do Symfony

## 1 Configurando o ambiente

### 1.1 Criando um projeto

O primeiro passo é se certificar de que todas as ferramentas/softwares necessários estão instalados: o mínimo é o PHP 7.2.5 (ou maior) e o composer (pela facilidade para criar o projeto e gerenciar as dependências; além disso, é necessário que algumas [extensões](https://symfony.com/doc/current/setup.html) do PHP estejam habilitadas, mas em geral isso ocorre na instalação do PHP e você não precisa se preocupar com isso.

Isso pronto, vá até o diretório que pretende desenvolver o projeto e rode o comando abaixo trocando `my_project_name` pelo nome que você quiser para seu projeto:

```terminal
composer create-project symfony/skeleton my_project_name
```

será criado um projeto Symfony mais enxuto (_commit_ [ba2e574](https://github.com/brnocesar/alura/commit/ba2e574d16554a9aa589558ae191f9b4e9471cf8)), sem os pacotes e arquivos necessários para uma aplicação web completa, como _views_ e outras coisas.

### 1.2 Estrutura de arquivos

Entrando no diretório do projeto podemos ver suas pastas:

- `bin`: contém toda lógica da aplicação
- `config`: arquivos de configuração
- `src`:
  - `Controller`:
- `resources`: fica toda a parte visualizada pelo usuário
- `routes`: armazena as rotas da aplicação

### 1.3 A primeira rota

Diferente do Laravel ou Lumen, aqui não temos uma pasta `/routes` na raiz do projeto com arquivos específicos para definição de rotas. Então por hora vamos criar a rota de "olá Mundo!" através de _anottations_ diretamente nos métodos dos _controller_.

Anters disso, vamos levantar um servidor local definindo a pasta `/public` como _target_ e acessar a _home_ do projeto:

```terminal
php -S localhost:8000 -t public
```

Criamos um _controller_ na pasta `/src/Controller` e dentro dele o método que retornara uma resposta de "OLá Mundo!". Acima do método adicionamos a _annotation_ definindo o recurso que deve ser acessado e o método. 

Porém isso ainda não é suficiente para que esse recurso seja disponibilizado através de uma rota. Como se trata de uma instalação mais enxuta do Symfony, precisamos instalar um pacote que adiciona a capacidade do projeto de lidar com as _annotations_, para isso rode o comando:

```terminal
composer require annotaion
```

Não precisamos realizar nenhuma outra configuração, pois já existe um _plugin_ chamado (ver o nome) que faz isso. A implementação dessa primeira rota pode ser vista no _commit_ [560635f](https://github.com/brnocesar/alura/commit/560635fd6b64d2ec28f5076f189dbf2a24c74508).

### 1.4 Lendo dados da requisição

Para acessar dados da requisição dentro do método de um _controller_ podemos fazer uma injeção de dependência para que ele receba um objeto do tipo `Symfony\Component\HttpFoundation\Request`, de forma muito similar ao Laravel, e assim passamos a ter acesso aos parâmetros enviados na requisição e outra informações úteis.

Além disso, como estramos trabalhando com uma API, é uma boa prática (regra?) que os métodos mapeados para rotas retornem um objeto do tipo resposta (`use Symfony\Component\HttpFoundation\Response`).

[↑ voltar ao topo](#índice)

## 2 Entidades

Vamos criar uma classe para representar a primeira entidade da aplicação, a de Medicos, e por questão de organização vamos manter essas classes na pasta `/src/Entity`. Após criar a entidade "Medico" definimos seus atributos (nome e CRM) e métodos acessores. Partimos agora para criar seu _controller_, onde vamos escrever a lógica do CRUD.

Dentro de `MedicoController` criamos o método `store()`, que será responsável por receber a requisição com dados para criar um novo registro do tipo médico, e já definimos a rota com o verbo `POST`.

No Laravel podemos acessar todos os parâmetros da requisição através de um único método do objeto que representa a _request_ (`all()`), independente de ter vindo na URL ou no _body_. Com a classe `Request` usada por padrão no Symfony precisamos usar métodos específicos para cada tipo de parâmetro. Por exemplo, para os parâmetros enviados no corpo da requisição usamos `getContent()` e na URL `query->all()`.

Podemos usar um cliente para realizar requisições enviando os atributos `nome` e `crm` para a rota criada. Usando o método `getContent()` podemos obter os parâmetro enviados na requisição, mas esse método retorna uma _string_ em JSON. Para acessar os atributos desse JSON devemos "passar" essa _string_ pela função `json_decode()` do PHP, que vai retornar um objeto padrão. A partir disso somos capazes de instânciar um objeto do tipo Medico e setar os valores de seus atributos através dos métodos acessores. Ao final podemos retornar uma resposta contendo este objeto criado.

```php
<?php

namespace App\Controller;

use App\Entity\Medico;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController
{
    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $dadoJson = json_decode($request->getContent());

        $medico = (new Medico())->setCrm($dadoJson->crm)->setNome($dadoJson->nome);

        return new JsonResponse($medico);
    }
}
```

[↑ voltar ao topo](#índice)

## 3 Usando um ORM

Vamos instalar e configurar um ORM (_Object Relational Mapping_) que servirá como camada de abstração para acesso ao Banco de Dados, permitindo que nos concentremos apenas na lógica que deve ser implementada sem nos preocuparmos com comandos SQL.

Um ORM mapeia entidades da aplicação para tabelas em um bancos de Dados relacional. O ORM padrão do Symfony é o **Doctrine**, para adicionar o pacote do Doctrine ao projeto você deve rodar o seguinte comando:

```terminal
composer require symfony/orm-pack
```

Agora podemos começar a pensar sobre o que é necessário configurar para que o banco seja criado ou como nossa aplicação vai se conectar a ele. As informações referentes à conexão com o banco de dados ficam no arquivo das "variáveis de ambiente", o `.env` localizado na raiz do projeto. Dentro desse arquivo existe uma váriavel chamada `DATABASE_URL` e instruções sobre como definir ser valor de acordo com o SGDB escolhido.

Por hora vamos configurar um banco SQLite, então basta copiar a _string_ de exemplo para este banco:

```env
DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db
```

Nesta _string_ temos a informação de que o banco utilizado é o SQLite e o caminho para seu arquivo. Verificando a pasta `/var` percebemos que ainda não há um arquivo chamado `data.db`, pois o banco ainda não foi criado. Para fazer isso usaremos a CLI disponível para projetos Symfony.

### 3.1 CLI

Assim como o Laravel possui o **Artisan** aqui também temos um CLI disponível, para acessar a lista de comandos dispiníveis rode o comando:

```terminal
php bin/console list
```

note que entre os comandos do Doctrine existe um comando responsável por criar o Banco de Dados:

```terminal
php bin/console list doctrine:database:create
```

### 3.2 Mapeando entidades e _migrations_

Agora que já temos um banco podemos começar a pensar em como mapear as entidades para tabelas e a forma padrão como isso é feito é através de _annotations_. Basta adicionar as anotações indicando a classe que representa a tabela, e os campos com suas propriedades, a chave primária e etc:

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Medico
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    public $id;
    /**
     * @ORM\Column(type="string")
     */
    public $crm;
    /**
     * @ORM\Column(type="string")
     */
    public $nome;

    ...
}
```

Apenas esse mapeamento não é suficiente para criar as tabelas no Banco de Dados, isso é feito através das _migrations_. Ao rodar o comando:

```terminal
php bin/console list doctrine:migrations:diff
```

caso exista uma diferença entre as tabelas existentes no Banco e as entidades mapeadas na aplicação, será gerado um arquivo de _migration_ que ao ser executado atualiza o Banco de Dados. Na saída do comando acima é apresentado um outro que permite executar apenas a _migration_ criada, algo como:

```terminal
php bin/console doctrine:migrations:execute --up 'DoctrineMigrations\\Version20201016224132'
```

Uma outra opção é executar todas as _migrations_ de uma só vez, para isso temos o comando:

```terminal
php bin/console doctrine:migrations:migrate
```

[↑ voltar ao topo](#índice)

### 3.3 Criando um registro e _entity manager_

Para realizar operações no Banco como criar, atualizar e deletar registros através do _controller_, podemos fazer uso de um "gerenciador de entidades" (_entity manager_), que é a camada de abstração para essas ações.

Para ter acesso a este objeto basta realizar a injeção de dependência da classe `EntityManagerInterface` no construtor do _controller_, assim o Symfony se encarrega de nos entregar uma instância dessa classe.

Com ela podemos "observar" o objeto do tipo Medico que foi criado com o método `persist()` e após definir seus atributos podemos mandar executar a operação de persistência no Banco com o `flush()`.

```php
<?php

namespace App\Controller;

use App\Entity\Medico;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicoController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $factory)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $dadoJson = json_decode($request->getContent());

        $medico = new Medico();
        $this->entityManager->persist($medico);

        $medico->setCrm($dadoJson->crm)->setNome($dadoJson->nome);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }
}
```

### 3.4 Recuperando registros e _repository_

#### 3.4.1 Listando todos médicos

Para confirmar que os registros estão sendo armazenados no banco podemos tentar recuperar esses registros do banco (ba dum tsss). Para fazer isso também podemos utilizar uma camada de abstração no _controller_, um _repository_.

O Symfony também disponibiliza uma classe para essa finalidade, para ter acesso precisamos apenas fazer o _controller_ herdar uma classe chamada `AbstractController`. Com isso o _controller_ de médicos passa a ter acesso a várias funcionalidades interessantes, uma delas é o "gerenciador de registros" (`getDoctrine()`) que é a partir do qual temos acesso ao "repositório" (`getRepository()`).

Como estamos interessados nos registros do tipo Medico, devemos passar essa informação para o _repository_, e se queremos recuperar todos os registros dessa classe usamos o método `findAll()`. Abaixo é apresentada a implementação do método `index()`:

```php
<?php

namespace App\Controller;
...

class MedicoController extends AbstractController
{
    ...

    /**
     * @Route("/medicos", methods={"GET"})
     */
    public function index(): Response
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);

        $medicos = $repositorioDeMedicos->findAll();

        return new JsonResponse($medicos);
    }
    ...
}
```

#### 3.4.2 Recuperando um médico específico

Se quisermos recuperar um registro em especifico podemos mapear uma rota que receba um identificador único desse registro como parâmetro. Para isso basta definir o nome do parâmetro que queremos entre chaves na rota, como um subrecurso do recurso `/medicos`.

E se este parâmetro for a chave primária do registro podemos utilizar o método `find()` do _repository_ para buscá-lo, caso exista. Se não existir, o método `find()` retorna-rá `null` e podemos avaliar isso para definir o código da resposta HTTP.

```php
<?php

namespace App\Controller;
...

class MedicoController extends AbstractController
{
    ...

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function show(Request $request): Response
    {
        $repositorioDeMedicos = $this->getDoctrine()->getRepository(Medico::class);

        $medico = $repositorioDeMedicos->find($$request->get('id'));

        return new JsonResponse(
            $medico,
            is_null($medico) ? 404 : 200
        );
    }
    ...
}
```

[↑ voltar ao topo](#índice)

#### 3.4.3 Atualizando um médico

Para que seja possível atualizar algum registro no Banco devemos criar uma rota com a mesma URL utizada para recuperar um registro específico, passando um identificador único como parâmetro de rota, mas precisamos acessa-lá através de um verbo HTTP diferente.

De acordo com o padrão REST, o verbo utilizado em uma rota que vai atualizar todos os campos de um registro é o verbo `PUT`.

```php
<?php

namespace App\Controller;
...

class MedicoController extends AbstractController
{
    ...

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(Request $request): Response
    {
        $medico = $this->getDoctrine()->getRepository(Medico::class)->find($request->get('id'));

        if ( is_null($medico) ) {
            return new Response('', 404);
        }

        $novoMedico = json_decode($request->getContent());

        $medico->setCrm($novoMedico->crm)->setNome($novoMedico->nome);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }
    ...
}
```

Note que não utilizamos o método `persist()` para indicar que o objeto do tipo Medico devia ser monitorado, apenas chamamos o método `flush()` para concretizar as alterações no Banco quando já haviamos atualizado o objeto. Isso ocorre pois o registro já foi recuperado pelo Doctrine, através do _repository_, portanto já estava sendo monitorado.

### 3.5 Factory

Perceba que estamos usando o mesmo código, em mais de um lugar, para obter um objeto do tipo Medico "montado" a partir de dados enviados pela requisição. Podemos abstrair essa responsabilidade para uma classe que será responsável apenas por isso. Este é o padrão _factory_, que no caso será uma "fábrica de médicos".

Vamos criar a classe `MedicoFactory` em `src/Helper` e dentro dela o método `createMedico()`, que a partir de uma _string_ em JSON vai retornar um objeto do tipo Medico montado:

```php
<?php

namespace App\Helper;

use App\Entity\Medico;

class MedicoFactory
{
    public function createMedico(string $json): Medico
    {
        $request = json_decode($json);

        $medico = new Medico();
        $medico->setCrm($request->crm)->setNome($request->nome);

        return $medico;
    }
}
```

Para ter acesso a esta nova classe dentro do _controller_ de médicos, podemos fazer uma injeção de dependência no construtor do _controller_, da mesma forma como foi feito com o genrenciador de entidades. E então podemos utilizar o método `createMedico()` da _factory_ dentro do método `store()`, tornando o código necessário para criar um novo registro menor e mais legível.

```php
<?php
...

class MedicoController extends AbstractController
{
    protected $entityManager;
    protected $factory;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $factory)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
    }

    /**
     * @Route("/medicos", methods={"POST"})
     */
    public function store(Request $request): Response
    {
        $medico = $this->factory->createMedico($request->getContent());

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(Request $request): Response
    {
        $medico = $this->getDoctrine()->getRepository(Medico::class)->find($request->get('id'));

        if ( is_null($medico) ) {
            return new Response('', 404);
        }

        $novoMedico = $this->factory->createMedico($request->getContent());

        $medico->setCrm($novoMedico->crm)->setNome($novoMedico->nome);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }
    ...
}
```

Outro benefício que obtemos dessa refatoração é que uma responsabilidade que estava no _controller_ foi delegada para uma outra classe responsável apenas por isso.

[↑ voltar ao topo](#índice)

#### 3.5.1 Abstraindo para um método

Note que nos métodos `show()` e `update()` do _controller_ estamos utilizando o mesmo código para recuperar um registro específico. Não precisamo criar uma nova classe para abstrair essa responsabilidade, mas podemos abstrair esse código para um método dentro do próprio _controller_.

Além disso, podemos definir um parâmetro nestes métodos para ter acesso direto ao identificador passado como parâmetro na URL. Assim não precisamos acessar todo o objeto Request para ter acesso a este parâmetro:

```php
<?php

namespace App\Controller;
...

class MedicoController extends AbstractController
{
    ...
    private function searchMedico(int $id): ?Medico
    {
        $medico = $this->getDoctrine()->getRepository(Medico::class)->find($id);

        return $medico;
    }

    /**
     * @Route("/medicos/{id}", methods={"GET"})
     */
    public function show(int $id): Response
    {
        $medico = $this->searchMedico($id);

        return new JsonResponse($medico, is_null($medico) ? 404 : 200);
    }

    /**
     * @Route("/medicos/{id}", methods={"PUT"})
     */
    public function update(int $id, Request $request): Response
    {
        $medico = $this->searchMedico($id);

        if ( is_null($medico) ) {
            return new Response('', 404);
        }

        $novoMedico = $this->factory->createMedico($request->getContent());

        $medico->setCrm($novoMedico->crm)->setNome($novoMedico->nome);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }
    ...
}
```

### 3.6 Deletando registros

Para deletar um registro do Banco podemos seguir a lógica implementada no método `update()`, recuperar um registro e executar uma ação sobre ele. Vamos começar pela rota que vai acessar o método `destroy()`, terá a mesma URL mas será acessada através do verbo `DELETE`.

E obviamente devemos executar algum método que remova esse registro do banco, então chamamos o `remove()` do gerenciador de entidades passando o objeto encontrado:

```php
<?php

namespace App\Controller;
...

class MedicoController extends AbstractController
{
    ...
    /**
     * @Route("/medicos/{id}", methods={"DELETE"})
     */
    public function destroy(int $id): Response
    {
        $medico = $this->searchMedico($id);
        if ( is_null($medico) ) {
            return new Response('', 404);
        }

        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', 204);
    }
    ...
}
```

A implementação do CRUD de médicos, com algumas diferenças do que é mostrado neste guia, pode ser encontrada no _commit_ [1c97a64](https://github.com/brnocesar/alura/commit/1c97a6473d4bef3ca2ca7fe9b302e9dc62238bf2).

[↑ voltar ao topo](#índice)

## 4 Configurando MySQL

Para configurar o Banco da aplicação como sendo o MySQL basta comentar a linha no arquivo `.env` que diz respeito ao SQLite, descomentar a do MySQL e definir as informações necessárias para acesso ao Banco: usuário  do Banco de Dados (com privilégios), senha desse usuário e o nome da base de dados ( que será criada/utilizada):

```env
DATABASE_URL=mysql://bruno:1234@127.0.0.1:3306/apisymfony2?serverVersion=5.7
# DATABASE_URL=sqlite:///%kernel.project_dir%/var/data.db
```

Agora rodamos o mesmo comando usado na seção 3.1 para criar o Banco:

```terminal
php bin/console list doctrine:database:create
```

Se tentarmos rodar a _migration_ que cria a tabela de médicos vamos receber um erro informando que a _migration_ existente é específica para ser rodada em bancos SQLite. Então devemos deletar a _migration_ que temos para o SQLite e criar uma nova para o MySQL:

```terminal
php bin/console list doctrine:migrations:diff
```

Agora sim podemos criar a tabela de médicos rodando a _migration_ para MySQL:

```terminal
php bin/console doctrine:migrations:migrate
```

No _commit_ [c5c4067](https://github.com/brnocesar/alura/commit/c5c4067ee6e42e3a6878a7e0fd571b8d47b0bae5) você pode acompanhar essas alterações no projeto.

## 5 Relacionamentos e uma nova entidade

### 5.1 Gerando código pela CLI

Um recurso muito utilizado nos projetos Laravel é a funcionalidade de criar arquivos (_models_, _controllers_, ...) através do Artisan e isso também pode ser feito pela nos projetos Symfony, mas para isso precisamos instalar um _plugin_ chamado **Maker**. Então adicionamos essa dependência ao projeto com o comando abaixo:

```terminal
composer require maker
```

Apos finalizar a instalação do pacote podemos verificar os comandos disponíveis:

```terminal
php bin/console list make
```

### 5.2 Criando uma nova entidade

Agora vamos criar uma nova entidade para a aplicação usando o auxilio da CLI. Começamos com o comando abaixo e basta ir preenchendo o que a interface pedir: nome da entidade, campos e seus tipos e etc...

```terminal
php bin/console make:entity
```

No caso vamos apenas definir o nome da entidade como `Especialidade` e definir um campo do tipo _string_ chamado `descricao`. Ao final, a saída no terminal nos sugere um comando do pacote **Maker** para criar a migration:

```terminal
php bin/console make:migration
```

[↑ voltar ao topo](#índice)

### 5.3 Definindo o relacionamento entre entidades

Na seção acima não fizemos nenhuma definição relacionada a entidade Medico, faremos isso agora utilizando a CLI. Se rodarmos o comando `php bin/console make:entity` e passarmos o nome de uma entidade que já existe, no caso Medico, isso será identificado e podemos adicionar novos campos a essa entidade.

No caso queremos adicionar um campo que registre a relação de Medico com Especialidade, então vamos escolher um campo do tipo _relation_ `ManyToOne`, onde cada médico tem uma especialidade e cada especialidade pode pertencer a vários médicos.

Ao finalizar o assistente podemos verificar o atributo `$especialidade` que foi adicionado na entidade Medico, gerar a _migration_ usando o comando do pacote **Maker** e rodá-la.

Dependendo das _constraits_ definidas ao criar o atributo `especialidade` na entidade Medico, apenas rodar a última _migratrion_ pode resultar em um erro devido a incoerência do que foi definido com o estado atual do Banco, portanto, faz sentido recriarmos o Banco do zero.

Primeiro devemos excluir o atual Banco com o comando abaixo. Se o atributo `--force` não for passado, ao rodar o comando receberemos apenas um aviso de que o Banco será apagado, mas nada será feito.

```terminal
php bin/console doctrine:database:drop --force
```

Após isso basta criar o Banco novamente e rodar todas as _migrations_. As alterações referentes a implementação da nova entidade e a definição do relacionamento podem ser acompanhadas no _commit_ [0ac6420](https://github.com/brnocesar/alura/commit/0ac6420ad6f9bd672deeb4f432707fe288d37ff9).

### 5.4 CRUD de Especialidade

Vamos criar o _controller_ de especialidades usando a CLI:

```terminal
php bin/console make:controller
```

Todo o procedimento restante é muito similar ao que já foi feito para o CRUD de médicos.

### 5.5 Interface `JsonSerializable`

Até agora os atributos das entidades permaneceram públicos, apesar de ja termos métodos _getters_ e _setters_. Se apenas mudarmos os modificadores de acesso dos atributos para `private` e tentamos acessar qualquer rota que retorne algum objeto, vamos notar que mesmo em caso de sucesso a resposta está vindo vazia. Isso ocorre porque os atributos das entidades não estão mais públicos.

Para contornar isso a entidade deve implementar uma interface do próprio PHP chamada `JsonSerializable`, que impõe a implementação de um método chamado `jsonSerialize()`. Este método deve retornar algum dado que possa ser lido pela função do PHP `json_encode()`, para ser "parseado" em formato JSON _string_, da mesma forma como o `JsonResponse()` faz (ou as vezes ele até usa o `json_encode()`).

Ou seja, quando uma instância da entidade for passada para algum método que "parseia" esse objeto para o formato JSON, é o retorno deste método que será passado para essa função. E se estamos interessados em obter uma representação em JSON do objeto, o mais natural é que o método `jsonSerialize()` retorne um _array_ associativo com os atributos da entidade.

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity()
 */
class Medico implements JsonSerializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\Column(type="string")
     */
    private $crm;
    /**
     * @ORM\Column(type="string")
     */
    private $nome;
    /**
     * @ORM\ManyToOne(targetEntity=Especialidade::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $especialidade;

    public function jsonSerialize()
    {
        return [
            'id'              => $this->getId(),
            'crm'             => $this->getCrm(),
            'nome'            => $this->getNome(),
            'especialidadeId' => $this->getEspecialidade()->getId(),
        ];
    }
}
```

### 5.6 Corrigindo CRUD de médicos

Agora que modificamos o acesso aos atributos da entidade Medico, precisamos usar os métodos _setters_ para definir seus atributos na _factory_ de médicos. Além disso, devemos adicionar a atribuição da especialidade ao médico.

Da mesma forma que usamos um _repository_ para recuperar os registros no _controller_ de médicos, faremos na _factory_ de médicos para recuperar o registro de Especialidade a partir de seu ID. No _controller_ usamos o _repository_ do próprio Doctrine, disponível devido a herança do `AbstractController`, mas aqui não precisamo nos preocupar com isso.

Quando criamos a entidade Especialidade pela CLI, automáticamente foi criado um _repository_ para essa entidade, `src/Repository/EspecialidadeRepository.php`. Então basta fazer a injeção de dependência desse _repository_ no construtor da _factory_ de médicos e utilizar um método que retorno o registro a partir de seua chave primária:

```php
<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory
{
    private $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository)
    {
        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function createMedico(string $json): Medico
    {
        $request = json_decode($json);

        $especialidade = $this->especialidadeRepository->find($request->especialidadeId);

        $medico = new Medico();
        $medico->setCrm($request->crm)
            ->setNome($request->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }
}
```

As implementações do CRUD de Especialidade, adaptações às entidades e ao CRUD de médicos foram feitas no _commit_ [791775f](https://github.com/brnocesar/alura/commit/791775f4568f160e800143c2a38b710f445c510f).

[↑ voltar ao topo](#índice)

## 6 Rotas com sub-recursos

Vamos iniciar a implementação de sub-recursos nas rotas que já existem. Por exemplo, digamos que queremos listar todos os médicos de uma especialidade, já temos a rota (recurso) para os detalhes de uma especialidade, então é natural apenas adicionar um sub-recurso a esta rota: `/especialidades/{especialidadeId}/medicos`. Como se trata de uma consulta que o resultado será uma lista de médicos, devemos implementar isso no _controller_ de médicos.

Criamos o método público `indexByEspecialidade()` recebendo o ID da especialidade como mapeado na rota e filtramos os médicos pela especialidade usando o método `findBy()` do _repository_ (_commit_ [bd6e655](https://github.com/brnocesar/alura/commit/bd6e655e92d6f20efad9c7dfa96781bf1daf44d8)).

```php
<?php

namespace App\Controller;
...

class MedicoController extends AbstractController
{
    ...
    /**
     * @Route("especialidades/{especialidadeId}/medicos", methods={"GET"})
     */
    public function indexByEspecialidede(int $especialidadeId): Response
    {
        $medicos = $this->getDoctrine()->getRepository(Medico::class)->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos, Response::HTTP_OK);
    }
    ...
}
```

### 6.1 Criando um _repository_ para médicos

Note que diferente do _controller_ de especialidades em que fizemos a injeção de depenêndia do _repository_ no construtor, no _controller_ de médicos acessamos o _repository_ através de `$this->getDoctrine()->getRepository(Medico::class)`. Não podemos receber um _repository_ no _controller_ de médicos dessa forma pois não existe uma classe _repository_ para médicos, a de especialidade foi criada junto com a entidade quando fizemos isso através da CLI. Então vamos criar um _repository_ para médicos seguindo a lógica implementada para especialidades.

Primeiro criamos um arquivo chamada `MedicoRepository` em `src/Repository` e vamos adicionando os seguintes itens:

- definimos o _namespace_
- definimos a classe `MedicoRepository` e a fazemos herdar `ServiceEntityRepository` (classe da integração entre Symfony e Doctrine)
- adicionamos um construtor que recebe um objeto da classe `RegistryInterface` (responsável pelo mapeamento entre entidades e tabelas)
- dentro do construtor de  `MedicoRepository` chamamos o construtor da classe pai passando como segundo parâmetro qual entidade o _repository_ controla

```php
<?php

namespace App\Repository;

use App\Entity\Medico;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MedicoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Medico::class);
    }
}
```

Além disso precisamos informar na entidade qual é sua "classe de repositório":

```php
<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * @ORM\Entity(repositoryClass="MedicoRepository::class")
 */
class Medico implements JsonSerializable
{
    ...
}
```

Agora basta alterar o _controller_ de médicos pedindo o _repository_ de médicos por injeção de depêndencia no construtor e substituir o método `getDoctrine()->getRepository(Medico::class)` (_commit_ [bd415aa](https://github.com/brnocesar/alura/commit/bd415aa48d955efb6ef482b062080e8a69ef19be)).

[↑ voltar ao topo](#índice)

## 7 Aproveitando Código e `BaseController`

Olhando para nossos _controllers_ podemos perceber que existe muito código repetido neles, basicamente cada um deles faz a mesma coisa mas para entidades diferentes. Nesse caso podemos criar um `BaseController` com todo esse comportamento comum e fazer os atuais _controllers_ o extenderem. E como esse _controller_ não será instânciado, apenas herdado, podemos definir essa classe como abstrata.

Precisamos definir os tipos dos objetos recebidos por injeção de dependência no construtor do `BaseController`. Começando pelo repositório, devemos pedir um objeto da classe `ObjectRepository`, que é uma interface e o tipo mais primitivo para essa finalidade. Assim, no construtor dos _controllers_ de entidades devemos chamar o construtor da classe pai passando o repositório da entidade.

```php
<?php

namespace App\Controller;

use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

abstract class BaseController extends AbstractController
{
    protected $repository;

    public function __construct(ObjectRepository $repository)
    {
        $this->repository = $repository;
    }
}
```

```php
<?php

namespace App\Controller;
...

class MedicoController extends BaseController
{
    protected $entityManager;
    protected $factory;

    public function __construct(EntityManagerInterface $entityManager, MedicoFactory $factory, MedicoRepository $repository)
    {
        $this->entityManager = $entityManager;
        $this->factory = $factory;
        parent::__construct($repository);
    }
}
```

```php
<?php

namespace App\Controller;
...

class EspecialidadeController extends BaseController
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $repository)
    {
        $this->entityManager = $entityManager;
        parent::__construct($repository);
    }
}

```

O primeiro método que podemos implementar no `BaseController` é o `index()`, que a não ser pela rota, é exatamente igual para os dois _controllers_. E agora precisamos definir as rotas em um arquivo externo aos _controllers_, em `config/routes.yaml`:

```yaml
medicos:
  path: /medicos
  controller: App\Controller\MedicoController::index
  methods:
    - GET

especialidades:
  path: /especialidades
  controller: App\Controller\EspecialidadeController::index
  methods:
    - GET
```

Para o método `show()` o procedimento é o mesmo, basta definir o método no `BaseController` e as rotas.

### 7.1 Interface para _factory_

Passando para os métodos que realizam alguma modificação no Banco, precisamos de um gerenciador de entidades no `BaseController`, então vamos defini-lo no construtor e fazer a devida alteração nos _controllers_ de entidades. Para o método `destroy()` isso já basta.

Para o método `store()` precisamos de uma _factory_. Até o momento apenas a entidade Medico possui uma _factory_, então precisamos criar uma para a entidade Especialidade. Além disso, devemos garantir que as duas _factories_ possuam o mesmo comportamento, criar um objeto a partir de uma _string_ em JSON e retorná-lo.

Uma forma de garantir que mais de uma classe tenha o mesmo comportamento é fazendo-as implementar a mesma interface. Além disso, podemos passar essa interface no construtor do `BaseController`. Então recapitulando por partes:

- Criar a inteface `src/Factory/EntityFactory.php` (note que a pasta `Helper` foi renomeada para `Factory`):

```php
<?php

namespace App\Factory;

interface EntityFactory
{
    public function createEntity(string $json);
}
```

- Fazer as _factories_ de Medico e Especialidade implementarem a interface criada

- Adicionar a interface criada ao construtor de `BaseController` por injeção de dependêcia

### 7.2 Método abstrato

O método `update()` é um pouco diferente. Recuperamos um objeto através do _repository_ (registro que já existe) e criamos um outro objeto através da _factory_ (com os novos valores). Devemos atualizar o primeiro objeto com os valores do segundo, mas o código necessário para cada entidade vai ser diferente, pois cada uma possui seus próprios atributos.

Uma boa solução para esse problema é abstrair a atualização dos atributos para um método a parte. Vamos utilizar o _template method_, em que no `BaseController` definimos um método abstrato (o _template_) e nas classes filhas definimos a implementação concreta, de acordo com a entidade.

A criação do `BaseController` e centralização das rotas está registrada no (_commit_ [ea14074](https://github.com/brnocesar/alura/commit/ea1407418dc0d720933b6faab5fe91e2e6cefaad)).

[↑ voltar ao topo](#índice)

### 8 Melhorando as respostas

### 8.1 Ordenação e filtro de resultados

#### 8.1.1 Ordenando resultados

Vamos implementar a funcionalidade de ordenação e filtro nas rotas de listagem da API. O primeiro passo é definir como o usuário irá informar que isso deve ser feito.

Como as rotas de listagem são todas do tipo `GET`, essa informação deve chegar através da _query string_. E vamos utilizar o padrão do _array_ `sort[]` em que cada condição para ordenação é um elemento desse _array_, com o campo usado como base para ordenação sendo o índice e a ordem o valor, como no exemplo abaixo:

```url
http://localhost:8080/medicos?sort[nome]=asc&sort[crm]=desc
```

Dentro do _controller_ podemos usar o método `query->all()` na _request_ para obter todos os parâmetros passados na _query string_ ou o método `query->get('sort')` para recuperar apenas o campo `sort` da _query string_. Dessa forma teremos um _array_ associativo da ordem em que cada campo deve ser ordenado

Agora podemos substituir o método `findAll()` do _repository_ pelo `findBy()` que recebe como um dos seus parâmetros um _array_ de ordenação exatamente no formato do _array_ `sort` que recebemos através da _query string_. Então basta passar o parâmetro `sort` como segundo parâmetro no método `findBy` (enquanto os "parâmetros nomeados" não chegam no PHP, tem que ser assim).

#### 8.1.2 Filtrando resultados

Para filtrar resultados de acordo com o valor de um campo, o processo é tão simples como a ordenação. Estabelecendo que o usuário deve enviar o nome do campo com o valor que deseja filtrar na _query string_, basta usar o método `query->all()` na _request_ e remover o campo `sort`. Assim teremos um _array_ associativo que é exatamente o formato do primeiro parâmetro que o método `findBy()` espera receber como "condição de busca".

```url
http://localhost:8080/especialidades?descricao=cardiologia
```

#### 8.1.3 Abstraindo a extração de dados da _request_

Visando manter o método do _controller_ apenas com a reponsabilidade de receber a requisição e enviar a resposta, vamos abstrair toda a lógica de extração dos parâmetros vindos na _query string_ para uma classe que terá apenas essa responsabilidade.

Criamos a classe `src/Helper/DataExtractorRequest.php`, definimos os métodos que devem retornar cada uma das informações (ordenação e filtro), no _controller_ base pedimos uma instância dessa classe por injeção de dependência no construtor e em cada um dos _controllers_ que extendem o base pedimos isso pelo construtor da classe mãe.

Essa implementação para ordenar e filtrar os resultados está registrada no (_commit_ [ec6cd0c](https://github.com/brnocesar/alura/commit/ec6cd0cc92208ada6ffd913d9333db9d5d038629)).

### 8.2 Paginando os resultados

Agora vamos implementar a funcionalidade de paginação nas repostas. Isso é muito importante pois garante que o cliente vai receber a quantidade de itens que ele quiser, ou ao menos não fazer uso desnecessário de recursos retornando todos os itens da coleção.

Primeiro definimos a forma como esperamos receber as informações de paginação do usuário:

```url
http://localhost:8080/medicos?page=1&perPage=3
```

Agora vamos modificar nossa classe dedicada a extrair dados da _request_, o processo é o mesmo da parte de ordenação e filtragem.

Após isso recebemos estas informações no método `index()` do _controller_ e passamos para o método `findBy()`. O terceiro parâmetro recebido é a quantidade de itens que deve ser recuparada e o segundo é o item a partir do qual (este não incluso) deve ser recuperado (_commit_ [262071a](https://github.com/brnocesar/alura/commit/262071a363c63aa1f3912b728938b529816a7c0b)).

Perceba que o nome da classe `DataExtractorRequest` foi alterado para `UrlDataExtractor` (_commit_ [0bafa08](https://github.com/brnocesar/alura/commit/0bafa08515443708e2feb809e72e787ad4fb5d1b)).

[↑ voltar ao topo](#índice)

### 8.3 Retornando informação extra e linkando recursos

Vamos passar a retornar algumas informações complementares nas respostas como por exemplo a página retornada, quantidade total de itens do recurso acessada e até mesmo links que permitam a navegação entre recursos.

Começamos criando a _factory_ de respostas `src/Factory/ResponseFactory.php`. Em seu construtor devemos receber todas as informações que desejamos enviar na resposta e basta montar o _array_ que será enviado no método `getResponse()`.

Por uma decisão minha, essa _factory_ será utilizada apenas nos métodos que retornam uma listagem de itens, pois nos outros métodos não necessidade alguma de retornar algo além do objeto (ou não) e do _status code_. Mas você pode implementar sua _factory_ de forma mais genérica como apresentada abaixo, de modo que ela possa usada para outros casos:

```php
<?php

namespace App\Factory;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFactory
{
    private $content;
    private $currentPage;
    private $itensPerPage;
    private $statusCode;

    public function __construct($content, int $statusCode=Response::HTTP_OK, int $currentPage=null, int $itensPerPage=null)
    {
        $this->content      = $content;
        $this->statusCode   = $statusCode;
        $this->currentPage  = $currentPage;
        $this->itensPerPage = $itensPerPage;
    }

    public function getResponse(): Response
    {
        if ($this->statusCode >= 400) {
            return new Response('', $this->statusCode);
        }

        $response = ['dados' => $this->content];

        if ( !is_null($this->currentPage) ) {
            $response['paginaAtual']    = $this->currentPage;
            $response['itensPorPagina'] = $this->itensPerPage;
        }

        return new JsonResponse($response, $this->statusCode);
    }
}
```

A minha implementação da _factory_ pode ser vista no _commit_ [67caf2f](https://github.com/brnocesar/alura/commit/67caf2fafef647800bd8d11bd0c27001a8ead7ae).

Agora vamos adicionar links para navegação entre recursos. Como já serializamos a representação das entidades, basta adicionar um elemento `_links` (adicionamos o _underline_ por convenção, para informar que é uma informação extra e não um atributo do objeto) e definir os recursos que queremos expor (_commit_ [4deea4e](https://github.com/brnocesar/alura/commit/4deea4e4fda43cc8253fcf4dd0fa9dfaf3645fea)).

[↑ voltar ao topo](#índice)

## 9 Autenticação e proteção de rotas

### 9.1 Pacotes necessários

Agora vamos começar a proteger nossa API para que usuários não autenticados sejam impedidos de acessar alguns recursos. Neste [link](https://symfony.com/doc/current/security.html) podem ser encontradas as instruções para implementações de segurança no Symfony.

Primeiro devemos instalar alguns pacotes necessários para essa implementação, são eles:

- o _plugin_ de segurança do Symfony (_commit_ [0eee001](https://github.com/brnocesar/alura/commit/0eee0017b9426de75f44d1b808986884c5834342)). Este pacote vai trazer tudo que é necessário para que o Symfony verifique quem pode ou não acessar as rotas da aplicação, de acordo com nossa implementação:

```terminal
composer require security
```

- um pacote para gerar os _tokens_ utilizados na autenticação por JWT que vamos implementar (_commit_ [177d521](https://github.com/brnocesar/alura/commit/177d521d3978ee90419bcfe9be7ecd0944807376)). Caso você queira um texto mais detalhado e com exemplo de implementação usando esse coiceito, isso foi feito no [projeto de Lumen](https://github.com/brnocesar/learning-PHP/tree/main/10-lumen) deste mesmo repositório.

```terminal
composer require firebase/php-jwt
```

### 9.2 Entidade `User`

Vamos criar uma entidade para representar os usuários que vão acessar a API. Essa entidade deve seguir alguns se adequar aos requisitos do pacote de segurança, então vamos criar essa classe através da CLI para facilitar nosso trabalho:

```terminal
$ php bin\console make:user

 The name of the security user class (e.g. User) [User]:
 >

 Do you want to store user data in the database (via Doctrine)? (yes/no) [yes]:
 > yes

 Enter a property name that will be the unique "display" name for the user (e.g. email, username, uuid) [email]:
 > username

 Will this app need to hash/check user passwords? Choose No if passwords are not needed or will be checked/hashed by some other system (e.g. a single sign-on server).

 Does this app need to hash/check user passwords? (yes/no) [yes]:
 > yes

 created: src/Entity/User.php
 created: src/Repository/UserRepository.php
 updated: src/Entity/User.php
 updated: config/packages/security.yaml

  Success!

 Next Steps:
   - Review your new App\Entity\User class.
   - Use make:entity to add more fields to your User entity and then run make:migration.
   - Create a way to authenticate! See https://symfony.com/doc/current/security.html
```

Após isso você pode conferir a classe criada, criar a _migration_ para atualizar o banco de dados e rodá-la:

```terminal
php bin/console make:migration

php bin/console doctrine:migrations:migrate
```

As alterações no projeto referentes a criação dessa classe estão registradas no _commit_ [98e5278](https://github.com/brnocesar/alura/commit/98e52788a2f3d61c8c957d62278278f1d8c9384b).

[↑ voltar ao topo](#índice)

#### 9.2.1 _Fixtures_ do Doctrine

Vamos utilizar um recurso chamado _fixture_ para criar o registro de um usuário no banco de dados. Rodando o comando `php bin/console list` podemos procurar pelo comando `php bin/console make:fixtures` que terá a seguinte descrição: "Creates a new class to load Doctrine fixtures".

Isso significa que vamos poder escrever código orientado a objetos para criar um (ou mais) registros no banco, da mesma forma que foi para os nossos CRUDs, mas não será um recurso que vai ficar disponível, é apenas para esses registros específicos.

Podemos fazer um paralelo com os _seeders_ do Laravel, mas não são recursos análogos. De forma geral, as _fixtures_ são indicadas para testes e _dummy data_, ou seja, mais utilizadas durante o desenvolvimento.

Apesar do parágrafo acima não vamos adicionar seu pacote apenas para desenvolvimento (`--dev`):

```terminal
composer require orm-fixtures
```

Após finalizar a adição dessa nova dependência ao projeto (_commit_ [a978c8b](https://github.com/brnocesar/alura/commit/a978c8b0aed4545b97c71b9cb4438722e16c95fd)) podemos rodar o comando mencionado acima para criar uma fixture (_commit_ [725601a](https://github.com/brnocesar/alura/commit/725601a5f2ba58b11cbf875189375e204486ccec)):

```terminal
$ php bin/console make:fixture

 The class name of the fixtures to create (e.g. AppFixtures):
 > UserFixtures

 created: src/DataFixtures/UserFixtures.php

  Success!

 Next: Open your new fixtures class and start customizing it.
 Load your fixtures by running: php bin/console doctrine:fixtures:load
 Docs: https://symfony.com/doc/master/bundles/DoctrineFixturesBundle/index.html
```

Agora podemos criar o nosso usuário de teste na _fixture_ criada em `src/DataFixtures/UserFixtures.php`. Para isso basta criar um objeto do tipo `User` e definir seus atributos dentro do método `load()`, note que já recebemos um gerenciador de entidades, que permite usar o `persist()` e o `flush()`.

Para não salvar a senha em texto puro ou não precisar chamar um método que criptografe a senha dentro da _fixture_ (ou só pra fazer diferente mesmo) vamos usar um outro recurso da CLI do Symfony que "encoda" uma senha:

```terminal
$ php bin/console security:encode-password

Symfony Password Encoder Utility
================================

 Type in your password to be encoded:
 > 123456

 ------------------ ------------------------------------------------------------------
  Key                Value
 ------------------ ------------------------------------------------------------------
  Encoder used       Symfony\Component\Security\Core\Encoder\MigratingPasswordEncoder
  Encoded password   $argon2id$v=19$m=65536,t=4,p=1$6JiRycZUeQ93a/7IO(...)eQH5H/xxFbg  
 ------------------ ------------------------------------------------------------------

 ! [NOTE] Self-salting encoder used: the encoder generated its own built-in salt.

 [OK] Password encoding succeeded
```

Para rodar a _fixture_ basta utilizar o comando abaixo. Mas isso também vai apagar todos os registros do banco de dados.

```terminal
php bin\console doctrine:fixtures:load
```

A alteração na _fixture_ pode ser observada no _commit_ [4a2c8be](https://github.com/brnocesar/alura/commit/4a2c8be27c7874a71184b9fc9f991d9e5d4bc030).

[↑ voltar ao topo](#índice)

### 9.3 _Login_

Vamos criar um _controller_ para realizar a ação de fazer o _login_ de um usuário registrado (_commit_ [3fb4c4f](https://github.com/brnocesar/alura/commit/3fb4c4f3f541d38831cdf3fa8b843f4723184805)). No método mapeado para a rota `/login` devemos:

- verificar se existem os campos `username` e `password` no body da requisição enviada e pegá-los
- recuperar um registro da tabela de usuários de acordo com o valor do campo `username` passado na requisição
- verificar se o usuário existe e se a senha confere
- gerar o _token_ de acesso e retorná-lo

### 9.4 Autenticador

Agora devemos implementar a classe que vai ter a responsabilidade verificar se uma requisição está sendo feita por um usuário válido. Ou seja, devemos verificar se o _token_ está sendo enviado na requisição e se ele é válido.

Criamos a classe `src/Security/JwtAuthenticator.php` e a fazemos herdar de `AbstractGuardAuthenticator`, isso nos trará várias facilidades mas para isso precisamos implementar alguns métodos da interface `AuthenticatorInterface`. Você pode ir dando `ctrl + click` até essa interface (que muito provavelmente estará em `vendor/symfony/security-guard/AuthenticatorInterface.php`), copiar seu conteúdo e ir implementando de acordo com as instruções. Os métodos que devem ser implementados obrigatóriamente são:

- `supports()`: define se o autenticador "suporta" a requisição enviada, ou seja, em que requisicões o autenticador deve ser chamado e ele será chamado sempre que o retorno for `true`. Em nosso caso queremos que apenas a rota `/login` seja aberta

```php
/**
 * Does the authenticator support the given Request?
 *
 * If this returns false, the authenticator will be skipped.
 *
 * @return bool
 */
public function supports(Request $request)
{
    return $request->getPathInfo() !== '/login';
}
```

- `getCredentials()`: neste método devemos recuperar as credênciais de acesso do usuário a partir da requisição feita. No nosso caso a informação enviada no _payload_ do _token_ é um _array_ associativo do `username_ do usuário, e é essa informação que vamos retornar.

```php
/**
 * Get the authentication credentials from the request and return them
 * as any type (e.g. an associate array).
 *
 * Whatever value you return here will be passed to getUser() and checkCredentials()
 *
 * For example, for a form login, you might:
 *
 *      return [
 *          'username' => $request->request->get('_username'),
 *          'password' => $request->request->get('_password'),
 *      ];
 *
 * Or for an API token that's on a header, you might use:
 *
 *      return ['api_key' => $request->headers->get('X-API-TOKEN')];
 *
 * @return mixed Any non-null value
 *
 * @throws \UnexpectedValueException If null is returned
 */
public function getCredentials(Request $request)
{
    $token = str_replace('Bearer ', '', $request->headers->get('Authorization'));

    try {
        return JWT::decode($token, 'chave1234', ['HS256']);
    } catch (Exception $e) {
        return false;
    }
}
```

- `getUser()`: utiliza o retorno de `getCredentials()` para recuperar um usuário e retorná-lo. Para isso podemos pedir o _repository_ de usuários no construtor. Se o retorno for `null` será lançada uma exceção apropriada.

```php
/**
 * Return a UserInterface object based on the credentials.
 *
 * The *credentials* are the return value from getCredentials()
 *
 * You may throw an AuthenticationException if you wish. If you return
 * null, then a UsernameNotFoundException is thrown for you.
 *
 * @param mixed $credentials
 *
 * @throws AuthenticationException
 *
 * @return UserInterface|null
 */
public function getUser($credentials, UserProviderInterface $userProvider)
{
    if (!is_object($credentials) or !property_exists($credentials, 'username')) {
        return null;
    }

    return $this->repository->findOneBy([
        'username' => $credentials->username
    ]);
}
```

[↑ voltar ao topo](#índice)

- `checkCredentials()`: verifica se as credênciais retornadas por `getCredentials()` são válidas

```php
/**
 * Returns true if the credentials are valid.
 *
 * If false is returned, authentication will fail. You may also throw
 * an AuthenticationException if you wish to cause authentication to fail.
 *
 * The *credentials* are the return value from getCredentials()
 *
 * @param mixed $credentials
 *
 * @return bool
 *
 * @throws AuthenticationException
 */
public function checkCredentials($credentials, UserInterface $user)
{
    return is_object($credentials) and property_exists($credentials, 'username');
}
```

- `onAuthenticationFailure()`: o que deve ser feito quando a autenticação falhar. No nosso caso queremos interromper/finalizar a requisição, então retornamos uma resposta em JSON com o conteúdo e código de _status_ HTTP adequados.

```php
/**
 * Called when authentication executed, but failed (e.g. wrong username password).
 *
 * This should return the Response sent back to the user, like a
 * RedirectResponse to the login page or a 401 response.
 *
 * If you return null, the request will continue, but the user will
 * not be authenticated. This is probably not what you want to do.
 *
 * @return Response|null
 */
public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
{
    return new JsonResponse(['error' => 'Authentication failure'], Response::HTTP_UNAUTHORIZED);
}
```

- `onAuthenticationSuccess()`: o que deve ser feito quando a autenticação ocorrer com sucesso. Podemos seguir exatamente o que as instruções orientam para uma API, retornar `null`

```php
/**
 * Called when authentication executed and was successful!
 *
 * This should return the Response sent back to the user, like a
 * RedirectResponse to the last page they  visited.
 *
 * If you return null, the current request will continue, and the user
 * will be authenticated. This makes sense, for example, with an API.
 *
 * @return Response|null
 */
public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
{
    return null;
}
```

- `supportsRememberMe()`: especifica se a aplicação vai "lembrar do usuário autenticado". Como uma API não deve guardar estados, cada requisição é independete, vamos retornar `false`

```php
/**
 * Does this method support remember me cookies?
 *
 * Remember me cookie will be set if *all* of the following are met:
 *  A) This method returns true
 *  B) The remember_me key under your firewall is configured
 *  C) The "remember me" functionality is activated. This is usually
 *      done by having a _remember_me checkbox in your form, but
 *      can be configured by the "always_remember_me" and "remember_me_parameter"
 *      parameters under the "remember_me" firewall key
 *  D) The onAuthenticationSuccess method returns a Response object
 *
 * @return bool
 */
public function supportsRememberMe()
{
    return false;
}
```

- `start()`: no nosso caso não teremos usuários anônimos, então podemos deixar em branco.

```php
/**
 * Returns a response that directs the user to authenticate.
 *
 * This is called when an anonymous request accesses a resource that
 * requires authentication. The job of this method is to return some
 * response that "helps" the user start into the authentication process.
 *
 * Examples:
 *
 * - For a form login, you might redirect to the login page
 *
 *     return new RedirectResponse('/login');
 *
 * - For an API token authentication system, you return a 401 response
 *
 *     return new Response('Auth header required', 401);
 *
 * @return Response
 */
public function start(Request $request, AuthenticationException $authException = null){
    // TODO: Implement start() method
}
```

Após implementar todos os métodos necessários ainda devemos informar ao Symfony que agora existe um _guard file_, o autenticador. Essa informação é registrada no arquivo `config/packages/security.yaml`, em `firewalls` devemos adicionar e modificar algumas diretivas:

- primeiro modificamos a diretiva `anonymous` para informar que não teremos usuários anônimos (com isso não precisamos implementar o método `start()` em nosso autenticador(?))
- expecificamos que não temos uma rota de _logout_
- e qual nosso arquivo de _guard_

Nesse _commit_ [c171124](https://github.com/brnocesar/alura/commit/c171124004a90c1bbf7a48e7f5fbccf52e088907) você pode verificar o resultado com todos os métodos implementados.

[↑ voltar ao topo](#índice)

## 10 Tratando erros

### Eventos do Symfony

[↑ voltar ao topo](#índice)
