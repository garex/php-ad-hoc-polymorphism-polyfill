## Why?

PHP don't knows what is [Ad hoc polymorphism](https://en.wikipedia.org/wiki/Ad_hoc_polymorphism). 
When Java and other higher level languages knows about it:

```java
class Overload
{
  void demo (int a)
  {
   System.out.println ("a: " + a);
  }
  void demo (int a, int b)
  {
   System.out.println ("a and b: " + a + "," + b);
  }
  double demo(double a) {
   System.out.println("double a: " + a);
   return a*a;
  }
}
```

So here we are trying to provide somekind of polyfill in PHP.

## Show me the code

Assume you have class which can do something with `Iterator`s:

```php
interface IteratorCaller
{
  public function callMe(Iterator $i);
}
```

And you want to process different iterators differently.
In ad-hoc polymorphism world you can just define different `callMe` methods with concrete `Iterator` signatures.
In PHP world you can't do this. One possible way is to go by the "instanceof" way, which would be probably a code smell.

So it's time to try `Polycall`:

```php
use Garex\Adhoc2p\Polycall;

class IteratorCallerImpl implements IteratorCaller
{

  public function callMe(Iterator $i)
  {
    $_p = (new Polycall($this))
      ->on(ArrayIterator::class)->to('callMe0')
      ->on(DirectoryIterator::class)->to('callMe1')
    ;
    return $_p->go(func_get_args());
  }

  public function callMe0(ArrayIterator $i)
  {
    return 'ArrayIterator';
  }

  public function callMe1(DirectoryIterator $i)
  {
    return 'DirectoryIterator';
  }
}
```

## What about optimizations?

### One time init

```php
  public function callMe(Iterator $i)
  {
      static $_p = null;
      if (!is_null($_p)) {
          return $_p->go(func_get_args());
      }
      
      $_p = (new Polycall($this))
          ->on(ArrayIterator::class)->to('callMe0')
          ->on(ArrayIterator::class)->to('callMe1')
      ;
      return $_p->go(func_get_args());
  }
```

### Guess overloaded methods names

```php
  public function callMe(Iterator $i)
  {
    $_p = (new Polycall\AutoTo($this, __METHOD__))
      ->on(ArrayIterator::class)
      ->on(DirectoryIterator::class)
    ;
    return $_p->go(func_get_args());
  }
```

### Guess overloaded methods names

```php
  public function callMe(Iterator $i)
  {
    $_p = (new Polycall\AutoTo($this, __METHOD__))
      ->on(ArrayIterator::class)
      ->on(DirectoryIterator::class)
    ;
    return $_p->go(func_get_args());
  }
```

### Preconfigure polycall object in callable

```php
  use Polycall\Inside;
  
  public function callMe(Iterator $i)
  {
    return $this->polycallGo(func_get_args(), $method = __METHOD__, function() use ($method) {
      return (new Polycall\AutoTo($this, $method))
        ->on(ArrayIterator::class)
        ->on(DirectoryIterator::class)
      ;
    });
  }
```
