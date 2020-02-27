# A (somewhat opinionated) Laravel package to help make preparing and executing actions a little easier and more uniform.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/telkins/laravel-pending-action.svg?style=flat-square)](https://packagist.org/packages/telkins/laravel-pending-action)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/telkins/laravel-pending-action/run-tests?label=tests)](https://github.com/telkins/laravel-pending-action/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Quality Score](https://img.shields.io/scrutinizer/g/telkins/laravel-pending-action.svg?style=flat-square)](https://scrutinizer-ci.com/g/telkins/laravel-pending-action)
[![Total Downloads](https://img.shields.io/packagist/dt/telkins/laravel-pending-action.svg?style=flat-square)](https://packagist.org/packages/telkins/laravel-pending-action)

This package is inspired by a few people.  Please check out the following to see where the inspiration came from:
- [Brent](https://twitter.com/brendt_gd)'s [article](https://stitcher.io/blog/laravel-beyond-crud-03-actions) about actions in Laravel
- [Freek](https://twitter.com/freekmurze)'s [video](https://freek.dev/1545-how-to-avoid-large-function-signatures-by-using-pending-objects) about pending objects with actions
- and [Mohamed](https://twitter.com/themsaid)'s [video](https://divinglaravel.com/when-does-php-call-__destruct) on using `__destruct()` in similar situations

From time to time you may need to perform tasks or actions in your application or different parts of your code where there isn't an out-of-the-box Laravel solution.  Most likely, this is or is related to your business logic.  An oft-used example is creating an invoice.  This is an action that may need to take place in one of your applications.  It will likely require parameters, and it will likely be made up of smaller actions.

The goal of this package is to provide a (somewhat opinionated) way to make define and use actions.  You can create `Action` classes and then, when you want to execute that action, you "prep" that class: `$myAction = MyAction::prep();`.  You get back a pending "params" object, which you define, that allows you to provide the parameters necessary for your action to be carried out.  When ready, you call `execute()`.

Here's an example...borrowing from Freek's video:

```php
CreateSubscriber::prep()
    ->withEmail('me@mydomain.com')
    ->forList($emailList)
    ->withAttributes($attributes)
    ->skipConfirmation()
    ->doNotSendWelcomeMail()
    ->execute();
```

## Installation

You can install the package via composer:

```bash
composer require telkins/laravel-pending-action
```

## Usage

For now, one must manually create `Action` and `Params` classes.

### Create an Action

To create an action class, simply extend `Action` like so, implementing the abstract `executeAction()` method:

``` php
use Telkins\LaravelPendingAction\Action;
use Telkins\LaravelPendingAction\Contracts\Params;

class CreateSubscriber extends Action
{
    public function executeAction(Params $params)
    {
        // code to perform action, using $params as needed...
    }

    // supporting methods, if needed...
}
```

### Create Action Params

To create an action parameters class, simply extend `Params` like so, providing methods to build up the params needed to execute the action:

``` php
use App\EmailList;
use Telkins\LaravelPendingAction\Params;

class CreateSubscriberParams extends Params
{
    public string $email;
    public EmailList $list;
    public array $attributes = [];
    public bool $skipConfirmation = false;
    public bool $sendWelcomeMail = true;

    public function withEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function forList(EmailList $list): self
    {
        $this->list = $list;

        return $this;
    }

    public function withAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    public function skipConfirmation(): self
    {
        $this->skipConfirmation = true;

        return $this;
    }

    public function doNotSendWelcomeMail(): self
    {
        $this->sendWelcomeMail = false;

        return $this;
    }
}
```

### "Prep" Your Action, Carry it Out

Once you have built your action and action parameters classes, then you can begin to use them.  There are three main steps to preparing your action and executing it:
1. Call the static `prep()` method on your `Action` class.  This returns the action's parameters object.
2. Using the parameters object, provide the parameters needed for the action.
3. Finally, call the `execute()` method on the parameters object.

Here is an example of preparing and executing an action all at once:

```php
UpdateLeaderboard::prep()
    ->leaderboard($leaderboard)
    ->forPlayer($player)
    ->withScore($score)
    ->execute();
```

Here is an example of using the parameters object to provide different parameters to carry out the action for different scenarios:

```php
$actionParams = UpdateLeaderboard::prep()
    ->leaderboard($leaderboard);

    ->execute();

$actionParams->forPlayer($greg)
    ->withScore($gregsScore)
    ->execute();

$actionParams->forPlayer($peter)
    ->withScore($petersScore)
    ->execute();
```

## Conventions

By default, each `Action` class will look for a "params" class that has the same FQCN with `Params` appended.  So, for our example `CreateSubscriber` class, it will look for `CreateSubscriberParams`.

To override this behavior, you can specify the "params" class name in your `Action` class like so:

``` php
use Telkins\LaravelPendingAction\Action;
use My\Custom\Namespace\UnconventionalNameParams;
use Telkins\LaravelPendingAction\Contracts\Params;

class CreateSubscriber extends Action
{
    protected static $paramsClass = UnconventionalNameParams::class; // override default "params" class

    public function executeAction(Params $params)
    {
        // ...
    }

    // ...
}
```

## Testing

``` bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please use the issue tracker.

## Credits

- [Travis Elkins](https://github.com/telkins)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
