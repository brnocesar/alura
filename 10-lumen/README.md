# Lumen

O Lumen é um _micro-framework_ PHP, é como se fosse um Laravel "enxuto".

## 1. Rodando este projeto após clonar

Vá até o diretório do projeto e execute os comandos abaixo para rodar a aplicação de forma local.

1. Instalar as dependênias

```php
composer install
```

2. Variáveis de ambiente e Banco SQLite

```terminal
cp .env.example .env & touch database/database.sqlite
```

3. Rodar _migrations_ e _seeder_

```terminal
php artisan migrate --seed
```

**Após** isso basta levantar o servidor e utilizar as seguintes credências para realizar o _login_:

```json
{
    "email": "usuario@teste.com",
    "password": "123456"
}
```

## 2. Principais diferenças para o Laravel

Logo de cara podemos notar algumas diferenças em relação ao Laravel, dentre elas temos:

- rotas: todas as definições são feitas no único arquivo `/routes/web.php`. Não tem mais o _provider_ de rotas por exemplo
- a configuração do Banco é feita apenas no `.env`, na verdade (acho que) todas as configurações são feitas apenas nesse arquivo
- ainda temos a interface por linha de comandos **_artisan_**, mas agora com menos funcionalidades, por exemplo:
  - não é mais possível criar _controllers_ e _models_ pela linha de comando
  - o servidor embutido já era, temos que usar o do PHP:
    - `php -S localhost:8000 -t public`
- alguns recursos vem até vem configurados por padrão, mas precisam ser habilitados. É o caso do Eloquent e das Facades, que devem ser habilitados descomentando suas definições no arquivo `bootstrap/app.php` (_commit_ [d97821a](https://github.com/brnocesar/alura/commit/d97821adaa15a10f25ed4d04691b256113aa713b)):

    ```php
    // $app->withFacades();
    // $app->withEloquent();
    ```

- _formrequest_ é outro regurso que não está disponível.
- o arquivo `kernel.php` não existe, os _middlewares_ devem ser registrados no arquivo `bootstrap/app.php`.

## 3. Criando um projeto

Acesse a [documentação oficial](https://lumen.laravel.com/docs) do Lumen e verifique se sua máquina satisfaz os requisitos (PHP por necessidade e Composer por comodidade) e logo após basta seguir para o comando que cria um projeto:

```terminal
composer create-project --prefer-dist laravel/lumen nome_do_projeto
```

Para facilitar os testes e o desenvolvimento da aplicação podemos ainda utilizar um cliente HTTP para fazer as requisições. Duas boas opções são o [Postman](https://www.postman.com/) e [Insomnia](https://insomnia.rest/).

## 4. Guia para implementar autenticação

### 4.1. Usando _middleware_ e _provider_ do Lumen

Primeiro vamos ver como pode ser feita essa implementação usando apenas a estrutura que já vem com o Lumen e um pacote externo para gerar os _tokens_.

#### 4.1.1. Adicionar o pacote [firebase/php-jwt](https://github.com/firebase/php-jwt) como dependência do projeto

```terminal
composer require  firebase/php-jwt
```

#### 4.1.2. Identificando o usuário

Para acessar um _endpoint_ autenticado o usuário deve enviar o _token_ através do _header_ `Authorization` na requisição.  
A busca pelo usuário registrado será feita no método `boot()` de `app/Providers/AuthServiceProvider.php`. Este método deve retornar o usuário encontrado ou `null`, para o caso de nenhum ser encontrado.
  
```php
<?php

namespace App\Providers;
...

class AuthServiceProvider extends ServiceProvider
{
    ...

    public function boot()
    {
        $this->app['auth']->viaRequest('api', function (Request $request) {
            if ($request->hasHeader('Authorization')) {
                $token = str_replace('Bearer ', '', $request->header('Authorization'));

                $dadosAutenticacao = JWT::decode($token, env('JWT_KEY'), ['HS256']);

                return User::where([
                    ['email', '=', $dadosAutenticação['email']],
                    ['password', '=', $dadosAutenticação['password']]
                ])->first();
            }
            return null;
        });
    }
}
```

Neste exemplo o _header_ `Authorization` é enviado com a palavra `Bearer` e _token_ separados por um espaço, então basta tratar isso para obter o _token_.  
Logo em seguida usamos o método `decode()` do pacote instalado para decodificar o _token_ e procurar por um usuário registrado. Este método recebe o _token_, a chave usada para codificar (que pode ser colocada no arquivo das variáveis de ambiente) e os algoritmos permitidos para decodificar o _token_.

#### 4.1.3. _Middleware_ de autenticação

Este _middleware_ deve ser ativado/registrado em `bootstrap/app.php`, assim como o _provider_ de autenticação. Para isso basta descomentar os seguintes trechos:

```php
// $app->routeMiddleware([
//     'auth' => App\Http\Middleware\Authenticate::class,
// ]);

...

// $app->register(App\Providers\AuthServiceProvider::class);
```

Agora basta informar ao Lumen em quais rotas esse _middleware_ será executado. Neste caso serão todas as rotas da aplicação, então preciso apenas adicioná-lo no grupo que envonve todas as rotas em `routes/web.php`:

```php
$router->group(['prefix' => '/api', 'middleware' => 'auth'], function () use ($router) {
    ...
});
```

#### 4.1.4. Gerando o _token_

Agora falta criar a rota para realizar o _login_, que é quando o _token_ é gerado. , Lembrando que ela não pode estar protegida, basta definir a rota fora do grupo principal:

```php
$router->post('/api/login', 'TokenController@generateToken');
```

Então criamos um _controller_ chamado `TokenController` e o método `gerarToken()` para realizar essa ação. Usando as informações enviadas no _login_, procurarmos um usuário no Banco e após nos certificarmos de que o usuário existe e é válido podemos finalmente gerar o _token_.

```php
<?php

namespace App\Http\Controllers;
...

class TokenController extends Controller
{
    public function gerarToken(Request $request)
    {
        $this->validate($request, [
            'email'    => 'required|email',
            'password' => 'required'
        ]);
        $usuario = User::where('email', '=', $request->email)->first();

        if ( is_null($usuario) or !Hash::check($request->password, $usuario->password) ) {
            return response()->json(["Não autorizado" => "Usuário e/ou senha inválidos"], 401, [], JSON_UNESCAPED_SLASHES);
        }

        $token = JWT::encode(['email' => $usuario->email], env('JWT_KEY'));

        return [
            'access_token' => $token
        ];
    }
}
```

Para gerar o _token_ usamos o método `encode()` do pacote instalado passando dois argumentos para ele. O primeiro é um objeto ou _array_ com o a informação que eu quiser enviar, aqui por exemplo foi apenas um _array_ associativo com o _e-mail_ do usuário, e o segunda é a chave que será usada para codificar o _token_, que foi definida nas variáveis de ambiente. E por fim, com o _token_ gerado é só returna-lo na resposta.

No método `encode()` também podemos passar o algoritmo que será usado para criptografar o _token_, mas o valor padrão já é o mesmo que foi definido no passo 2 ao decodificar o _token_.  

### 4.2. Usando um _middleware_ próprio

Usando a implementação apresentada acima ganhamos acesso a recursos tais como o acesso ao usuário autenticado na requisição, por exemplo. Porém em muitos casos isso não é algo que será utilizado, então dessa forma podemos implementar um _middleware_ mais simples, apenas com a função de verificar se o usuário é válido.

Criamos o arquivo para o nosso _middleware_ a partir do arquivo de exemplo na pasta `app/Http/Middleware`:
```php
<?php
namespace App\Http\Middleware;

class AutenticadorMaisMelhorDeBom
{
    public function handle(Illuminate\Http\Request $request, \Closure $next)
    {
        try {
            if ( !$request->hasHeader('Authorization') ) {
                throw new \Exception();
            }

            $authorizationHeader = $request->header('Authorization');
            $token = str_replace('Bearer ', '', $authorizationHeader);

            $dadosAutenticacao = \Firebase\JWT\JWT::decode($token, env('JWT_KEY'), ['HS256']);

            $user = \App\User::where('email', '=', $dadosAutenticacao->email)->first();
            if ( is_null($user) ) {
                throw new \Exception();
            }

            return $next($request);

        } catch (\Exception $e) {
            return response()->json('Não autorizado', 401);
        }
    }
}
```

Podemos simplesmente usar a mesma lógica implementada no _provider_, a diferença fica por conta dos retornos.  
Ao invés de retornar `null` caso o usuário não seja encontrado, devemos retornar uma resposta HTTP informando que o cliente não está autorizado. Em caso positivo, de verificar que o usuário é válido, retornamos a `$request` para o próximo _middleware_.

Registramos o _middleware_ no arquivo `bootstrap/app.php`:

```php
$app->routeMiddleware([
    'autenticador_melhor' => App\Http\Middleware\AutenticadorMaisMelhorDeBom::class,
]);
```

E por último, especificamos no arquivo de rotas qual _middleware_ deve ser usado pelo grupo principal:

```php
$router->group(['prefix' => '/api', 'middleware' => 'autenticador_melhor'], function () use ($router) {
    ...
});
```

### 4.3. _Commits_

- [Configura autenticacao por JWT](https://github.com/brnocesar/learning-PHP/commit/4672a11c0cef1984aeaba3019eb372057ddd7cc7)
- [Cria seeder para usuario](https://github.com/brnocesar/learning-PHP/commit/d8d35ef5a299f82ef520483dc375a913c16b3af1)
- [Implementa login para usuario registrado](https://github.com/brnocesar/learning-PHP/commit/8469040ef286fb3b17a87127bae421db3a077d0f)
- [Adiciona pequenos ajustes](https://github.com/brnocesar/learning-PHP/commit/97894eebe7c7717247b09138c005dd53ac3faf65)
- [Implementa autenticacao 'propria'](https://github.com/brnocesar/learning-PHP/commit/735e0050784ed838823c067d365d21660f500c37)
