# API com Symfony

## 1. Definindo rotas através de anotações

```php
/**
 * @Route("/hello", methods={"GET"})
*/
public function helloWorld(Request $request): Response
{
    return new JsonResponse(["message" => "Hello world"]);
}
```

## 2. CRUD de Entidades (Médico, Especialidade...)

- _Controller_
- Entidade (_Model_)
- Integrando o ORM Doctrine
- Configurando o Banco de Dados
- Modelando o Banco (_Migrations_)
- Factory
- Relacionamentos
- Injeção de dependências
