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
5. [Relacionamentos e uma nova entidade](#-relacionamentos-e-uma-nova-entidade)
  5.1. Gerando código pela CLI
  5.2. Criando uma nova entidade
  5.3. Definindo o relacionamento entre entidades
  5.4. CRUD de Especialidade
  5.5. Interface `JsonSerializable`
  5.6. Corrigindo CRUD de médicos

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
