
## Testes unitários e TDD com PHP e PHPUnit

### Credito:
**curso:** https://www.udemy.com/testes-unitarios-php-phpunit/

**Observações:**
Esse projeto foi feito com base o projeto do curso completo usando PHPUnit do professor Vinicius Silva.

**phpunit.xml:** https://github.com/bakame-php/geolocation
___

## Instruções

**Clone o projeto:**

`git clone git@github.com:weliton1996/test_with_phpunit.git`

## Without_phpunit

**Para executar os testes da `without_phpunit` basta acessar o diretório:**

`cd test_with_phpunit/without_phpunit`

**Executar os testes:**

`php run_tests.php`

## With_phpunit

**Para executar os testes da `with_phpunit` siga as seguintes instruções:**

**Acesse o diretório do projeto:**
 
`cd test_with_phpunit/with_phpunit`

**Instale as dependências:**
 
`./composer.phar install`

**Execute os testes:**
 
`./vendor/bin/phpunit src/...path`

**Execute o teste mais a cobertura de código**
`.\vendor\bin\phpunit -c phpunit.xml src` **OU** `.\vendor\bin\phpunit src`

**Para visualizar a cobertura de testes**
- **Execute o servidor no entrypoint:** `.\with_phpunit\build\coverage\index.html`

**O que se espera de um nome de cenário de teste:**
- O que está sentdo testado?
- Quais as circuntâncias?
- Qual o resultado esperado?

**Exemplo de nomes de cenários de teste:**
1. ShouldBeValidWhenValueIsANumber
1. whenValueIsANumberShouldBeValid
1. Should_BeValid_When_ValueIsANumber
1. IsValid_valueIsANumber_true
1. IsValid_True_ValueIsANumber


