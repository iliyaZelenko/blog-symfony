[Подробнее о различии Stub между Mock.](https://stackoverflow.com/questions/3459287/whats-the-difference-between-a-mock-stub/17810004#17810004)


### Stub

Stub — просто возвращает тестовые данные, заменяет сложную логику получения каких-то данных простой логикой.

Например, есть метод calculate которы подсчитывает 5 мин, чтобы не ждать 5 мин можно написать простую логику которая выполнится за 1 сек.

Stub в отличие от Mock больше основан на состоянии.

Stub не проверяют через assert, обычно там строго заданные значения которые просто позволяют работать другим частям теста.

Stub'ы не могут зафейлить тест в отличие от Mock'ов.

### Mock

Mock — объект в котором настраиваются ожидания на выполнения какого-то действия, возврата какого-то значения.

Mock код проверяют как вызывается через assert в отличие от Stub.

### Dummy

Dummy — объект который ничего не делает и не вызывается (если и вызывается то обычно просто возвращает `null`), 
просто передается в качестве аргументов в конструктор или другой метод.

## PHP Unit примеры

[Источник](https://stackoverflow.com/a/45975572/5286034)

### Пример Stub

```php
$stub = $this->createMock(SomeClass::class);
$stub->method('getSomething')
    ->willReturn('foo');

$sut->action($stub);
```

### Пример Mock

```php
$mock = $this->createMock(SomeClass::class);
$mock->expects($this->once())
    ->method('doSomething')
    ->with('bar');

$sut->action($mock);
```


### Пример Dummy

```php
$dummy = $this->createMock(SomeClass::class);

// SUT - System Under Test
$sut->action($dummy);
```
