# Exceptions

1. [Execução de um programa](#1)
2. [Tratamento de exceções](#2)  
2.1. [Capturando exceções e erros](#2-1)  
3. [Lançando exceções](#3)  
3.1 [_Annotations_](#3-1)
4. [Hierarquia de exceções](#4)  
4.1. [Criando exceções](#4-1)
4.2. [Bloco `finally`](#4-2)

## 1. Execução de um programa (#1)

A execução de um programa PHP inicia sempre a partir de um arquivo (_main_, ponto único de entrada?) e este arquivo (assim como todos?) possui duas formas básicas de instrução: (i) declaração (de funções, classes e etc) e a execução.

A ordem em que as funções estão no arquivo não importam, mas a execução vai ocorrer de cima para baixo.

No inicio da execução do programa é reservado um espaço na memória para o _main_ e conforme outras funções vão sendo chamadas o mesmo vai sendo feito para elas. Caso alguma função utilize variáveis, estas vão sendo alocadas no seu devido escopo.

Assim que uma função tem sua execução encerrada esse espaço na memória é liberado.

```php
<?php

function funcao1()
{
    echo 'Entrei na função 1' . PHP_EOL;
    funcao2();
    echo 'Saindo da função 1' . PHP_EOL;
}

function funcao2()
{
    echo 'Entrei na função 2' . PHP_EOL;
    for ($i = 1; $i <= 5; $i++) {
        echo $i . PHP_EOL;
    }
    // var_dump(debug_backtrace());
    echo 'Saindo da função 2' . PHP_EOL;
}

// main
echo 'Iniciando o programa principal' . PHP_EOL;
funcao1();
echo 'Finalizando o programa principal' . PHP_EOL;
```

```terminal
$ php pilha.php
Iniciando o programa principal
Entrei na função 1
Entrei na função 2
1
2
3
4
5
Saindo da função 2
Saindo da função 1
Finalizando o programa principal
```

Considerando o código acima, podemos representar a ordem de execução através de uma coisa chamada pilha (_stack_). Considere que o PHP vai jogando em uma pilha cada uma das chamadas a declarações que ele encontra, e sempre o que está por cima está sendo executado. Podemos acompanhar os estados da pilha ao longo da execução do programa.

|Inicia prog.|chama `funcao1()`|chama `funcao2()`|exec. `funcao2()`|finaliza exec. `funcao2()`|finaliza exec. `funcao1()`|finaliza prog.|
|:-:|:-:|:-:|:-:|:-:|:-:|:-:|
|||`funcao2()`|`funcao2()` e `$i`||||
||`funcao1()`|`funcao1()`|`funcao1()`|`funcao1()`||
|_main_|_main_|_main_|_main_|_main_|_main_||

> _"A pilha da Zend Engine (interpretador do PHP) armazena as funções e os métodos que estão sendo executados. Além do bloco de código, na pilha ficam guardadas as variáveis e as referências referentes ao bloco. Assim, o PHP organiza a execução e sabe exatamente qual método está sendo executado, que é sempre o código no topo da pilha. O PHP também sabe quais outros métodos ainda precisam ser executados, que são justamente os que estão abaixo."_

A melhor forma de visualizar a pilha de execução é usando um depurador. Mas para quebrar um galho podemos usar o `var_dump(debug_backtrace())` que retorna a pilha de execução até o ponto de sua chamada.

## 2. Tratamento de exceções (#2)

Inicialmente vamos modificar a `funcao1()` criando um _array_ de tamanho fixo e depois tentar acessar uma posição que não existe nesse _array_.

```php
function funcao1()
{
    echo 'Entrei na função 1' . PHP_EOL;
    // $arrayFixo = new SplFixedArray(2);
    // $arrayFixo[3] = 'valor'; // RuntimeException
    // $divisao = intdiv(5, 0); // DivisionByZeroError
    $variavel = 0;
    $divisao = 5/$variavel;
    funcao2();
    echo 'Saindo da função 1' . PHP_EOL;
}
```

```terminal
$ php pilha.php
Iniciando o programa principal
Entrei na função 1
PHP Fatal error:  Uncaught RuntimeException: Index invalid or out of range in /home/bruno/repos/learning-PHP/4-exceptions/pilha.php:7
Stack trace:
#0 /home/alura-exceptions/pilha.php(23): funcao1()
#1 {main}
  thrown in /home/alura-exceptions/pilha.php on line 7
```

A saída da execução nos retorna muitas informações sobre o que aconteceu, como qual foi o problema, qual o estado da pilha, a linha no código e etc. Aqui tivemos uma "exceção em tempo de execução" que não foi tratada, o código está escrito de forma correta, mas ocorreu um comportamento excepicional.

Agora se tentarmos realizar uma divisão por zero no mesmo ponto do código obteremos a seguinte saída:

```terminal
$ php pilha.php
Iniciando o programa principal
Entrei na função 1
PHP Fatal error:  Uncaught DivisionByZeroError: Division by zero in /home/bruno/repos/learning-PHP/4-exceptions/pilha.php:8
Stack trace:
#0 /home/bruno/repos/learning-PHP/4-exceptions/pilha.php(8): intdiv()
#1 /home/bruno/repos/learning-PHP/4-exceptions/pilha.php(24): funcao1()
#2 {main}
  thrown in /home/bruno/repos/learning-PHP/4-exceptions/pilha.php on line 8
```

Note que agora temos um erro e não uma exceção, significa que existe um problema na forma como o código está escrito.

Se ao contrário de uma divisão explícita tivermos uma expressão em que denominador é uma variável que assume o valor zero, receberemos apenas um `Warning:  Division by zero`, que não irá interromper a execução do programa.

### 2.1. Capturando exceções e erros (#2-1)

Exceções em tempo de execução e erros modificam o fluxo de execução de um programa. Portanto, devemos usar alguma estrutura de controle para prevenir essas alterações no fluxo. Devemos ser capazes de "capturar" um problema que ocorra durante a execução e a estrutura `try/catch` serve exatamente  para isso.

```php
function funcao1()
{
    echo 'Entrei na função 1' . PHP_EOL;
    try {
        $arrayFixo = new SplFixedArray(2);
        $arrayFixo[3] = 'valor'; // RuntimeException

        $divisao = intdiv(5, 0); // DivisionByZeroError
    } catch (RuntimeException $problema) {
        echo 'Ocorreu uma exceção em tempo de execução na funcao1()' . PHP_EOL;
    } catch (DivisionByZeroError $problema) {
        echo 'Ocorreu uma tentativa de dividir por zero na funcao1()' . PHP_EOL;
    }
    $variavel = 0;
    $divisao = 5/$variavel;
    funcao2();
    echo 'Saindo da função 1' . PHP_EOL;
}
```

Dentro do bloco `try` fica o código que pode resultar em uma exceção ou erro e nos blocos `catch` vai o código que lida com o problema. Note que podemos ter multiplos blocos _catch_, cada um lidando com uma _exception_/erro específica.

Existe uma outra forma de tratar múltiplas exceções, quando queremos dar o mesmo tratamento a diferentes tipos podemos usar o _multi-catch_. Com ele podemos especificar mais de um tipo de exceção/erro em um único bloco `catch`, apenas separando-os com o operador `|`. Ele é usado quando queremos dar o mesmo tratamento a diferentes exceções.

```php
try {
    // código
} catch (RuntimeException | DivisionByZeroError $problema) {
    $problema->getMessage(). PHP_EOL;
    $problema->getLine(). PHP_EOL;
    $problema->getTraceAsString(). PHP_EOL;
}
```

## 3. Lançando exceções (#3)

Como as exceções modificam o fluxo de execução de um programa, podem existir situações em que seu uso seja para tratar comportamentos excepicionais relacionados a alguma regra de negócio e não a um "problema" que ocorreu durante a execução.

Nessa situação podemos **lançar** uma instância de alguma exceção que faça sentido para nosso propósito, para isso devemos usar a palavra reservada `throw`. Ao instânciarmos um objeto de uma de exceção podemos (devemos) passar alguns parâmetros para seu construtor, como uma mensagem, o _HTTP status code_, e até mesmo uma exceção anterior (pra que serve essa última eu ainda não sei).

Note que essa exceção lançada deve ser **capturada** por algum `catch`, do contrário, se ela não for tratada irá ocorrer um "erro fatal" e o programa vai encerrar sua execução. Um ponto interessante é que também podemos lançar exceções de dentro dos blocos `catch` e acessar a exceção anterior.

```php
try {
    // código
    trhow new RuntimeException();
} catch (RuntimeException | DivisionByZeroError $problema) {
    // tratamento da exceção...
    $problema->getPrevious(); // exceção anterior a que foi capturada
}
```

### 3.1. _Annotations_ (#3-1)

Usando _annotations_ podemos documentar funções que lançam exceções. Isso não será lido pelo PHP, mas pode ser utilizado pela sua IDE ou algum pacote que você esteja usando para te ajudar.

```php
/**
 * @throws Exception
 */
function funcaoQueLancaExcecao()
{}
```

## 4. Hierarquia de exceções (#4)

Todas as classes lançáveis no PHP derivam da interface `Throwable`, que é usada apenas pelo PHP e não pode ser implementada diretamente por outras classes que não sejam as do PHP.

Apenas duas classes implementam essa interface: a classe `Exception` e a `Error`, que são as classes bases para erros do usuário e internos, respectivamente.

As classes de erros já são definidas no _core_ do PHP e não podem ser criadas pelos programadores usuários de PHP. Em relação às exceções, apenas a classe `Exception` é definida no _core_ da linguagem. Felizmente, existe uma extensão que vem habilitada em qualquer instalação do PHP chamada SPL (Standard PHP Library), que entre muitas coisas define os "tipos básicos" de exceções.

As exceções básicas do SPL são duas: as **lógicas** (`LogicException`), lançadas quando é feito um mau uso da linguagem; e as **em tempo de execução** (`RuntimeException`), em que não há problema com o código, mas durante a execução ocorre algum comportamento inesperado e que impede o programa de continuar rodando.

Apesar de não ser possível, para um programador usuário de PHP, criar exceções que extendam diretamente a interface `Throwable`, é possível escrever um `catch` genérico capturando esse tipo.

### 4.1. Criando exceções (#4-1)

Exceções criadas pelos programadores usuários de PHP devem extender a classe `Exception`, ou qualquer uma que a extenda. Para isso basta criar uma classe normal e extender o tipo exceção que melhor se adeque a sua necessidade.

```php
<?php

function minhaExcecao() extends Exception
{
    public function meuMetodoEspecial()
    {}
}

try {
    throw new MinhaExcecao();
} catch (MinhaExcecao $e) {
    $e->meuMetodoEspecial();
}
```

Em geral, o mais comum é que as exceções "customizadas" não implementem nenhum método, pois todos os métodos básicos já são herdados. Mas caso haja a necessidade de implementar métodos específicos para essa exceção, eles estaram disponíveis quando ela for capturada por algum `catch`.

### 4.2. Bloco `finally` (#4-2)

O código dentro do bloco `finally` será executado independente da ocorrência ou captura de uma exceção, ele sempre executa. É um recurso não muito utilizado de forma prática, pois o mesmo resultado pode ser facilmente alcançado sem utilizá-lo.

```php
<?php

try {
    echo "Executando" . PHP_EOL;
} catch (Throwable $th) {
    echo "Caindo no catch" . PHP_EOL;
} finally {
    echo "Executando o Finally" . PHP_EOL;
}
```

```terminal
$ php finally.php
Executando
Caindo no catch
Executando o Finally
```

```terminal
$ php finally.php
Executando
Executando o Finally
```

O `finally` é útil quando todos os blocos da estrutura retornam alguma coisa e, ainda assim, existe código que deve ser executado após estes blocos.

```php
<?php

try {
    return "Executando" . PHP_EOL;
} catch (Throwable $th) {
    return "Caindo no catch" . PHP_EOL;
} finally {
    echo "Executando o Finally" . PHP_EOL;
}
```

```terminal
$ php finally.php
Executando
Executando o Finally
```
