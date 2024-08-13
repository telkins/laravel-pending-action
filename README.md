# A (somewhat opinionated) Laravel package to help make preparing and executing actions a little easier and more uniform.

[![Latest Version on Packagist](https://img.shields.io/packagist/v/telkins/laravel-pending-action.svg?style=flat-square)](https://packagist.org/packages/telkins/laravel-pending-action)
[![Tests](https://github.com/telkins/laravel-pending-action/workflows/run%20tests/badge.svg)](https://github.com/telkins/laravel-pending-action/actions?query=workflow%3A"run+tests")
[![Quality Score](https://img.shields.io/scrutinizer/g/telkins/laravel-pending-action.svg?style=flat-square)](https://scrutinizer-ci.com/g/telkins/laravel-pending-action)
[![Total Downloads](https://img.shields.io/packagist/dt/telkins/laravel-pending-action.svg?style=flat-square)](https://packagist.org/packages/telkins/laravel-pending-action)

This package is inspired by a few people.  Please check out the following to see where the inspiration came from:
- [Brent](https://twitter.com/brendt_gd)'s [article](https://stitcher.io/blog/laravel-beyond-crud-03-actions) about actions in Laravel
- [Freek](https://twitter.com/freekmurze)'s [video](https://freek.dev/1545-how-to-avoid-large-function-signatures-by-using-pending-objects) about pending objects with actions
- and [Mohamed](https://twitter.com/themsaid)'s [video](https://divinglaravel.com/when-does-php-call-__destruct) on using `__destruct()` in similar situations

From time to time you may need to perform tasks or actions in your application or different parts of your code where there isn't an out-of-the-box Laravel solution.  Most likely, this is or is related to your business logic.  An oft-used example is creating an invoice.  This is an action that may need to take place in one of your applications.  It will likely require parameters, and it will likely be made up of smaller actions.

The goal of this package is to provide a (somewhat opinionated) way to make, define, and use actions.  You can create `Action` classes and then, when you want to execute that action, you "prep" that class: `$myAction = MyAction::prep();`.  You get back a pending action object, which you define, that allows you to provide the parameters necessary for your action to be carried out.  When ready, you call `execute()`.

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

For now, one must manually create `Action` and `PendingAction` classes.  A future release will include the ability to create `Action` and `PendingAction` classes via `php artisan`.

### Create an Action

There are two main ways to create an action class, depending on your needs:
- one that handles the pending action preparation completely, but does not play as nicely with your IDE without additional code
- one that plays nicely with your IDE, but requires a small amount of repetitive code across action classes

#### Create a "Light" Action

This method of creating an action requires the least amount of code.  As such, you can call this a "light" action class.  A class like this will work perfectly fine.  But, because the `prep()` method's signature shows a `PendingAction` interface return type, your IDE will not know what object you're given and code completion will be limited when working with pending action objects.  You may be able to provide information to your IDE in order to have full code completion for pending action objects, but that will require additional work.

Still, this may be perfectly fine for you.  So, should you wish to create an action class like this, simply create a class that extends the abstract `Action` class like so, adding an `execute()` method that takes the pending action object:

``` php
use Telkins\LaravelPendingAction\Action;

class CreateSubscriber extends Action
{
    public function execute(CreateSubscriberPendingAction $pendingAction)
    {
        // code to perform action, using $pendingAction as needed...
    }

    // supporting methods, if needed...
}
```

#### Create an "IDE-Friendly" Action

This method of creating a small amount of additional code for each action class.  This additional code, however, will play nicely with your IDE, allowing code completion, if available.

Should you wish to create an action class like this, simply create a "light" action class like above and then add a `prep()` method like so:

``` php
use Telkins\LaravelPendingAction\Action;

class CreateSubscriber extends Action
{
    public static function prep(): CreateSubscriberPendingAction
    {
        return self::autoPrep();
    }

    public function execute(CreateSubscriberPendingAction $pendingAction)
    {
        // code to perform action, using $pendingAction as needed...
    }

    // supporting methods, if needed...
}
```

Note: The `autoPrep()` method handles pending action object preparation for you.

#### Pending Action Class Naming Conventions

By default, each `Action` class will look for a pending action class that has the same FQCN with `PendingAction` appended.  So, for our example `CreateSubscriber` class, it will look for `CreateSubscriberPendingAction`.

To override this behavior, you can specify the pending action class name in your `Action` class like so:

``` php
use Telkins\LaravelPendingAction\Action;
use My\Custom\Namespace\UnconventionalCreateSubscriberPendingAction;

class CreateSubscriber extends Action
{
    // override default pending action class
    protected static $pendingActionClass = UnconventionalCreateSubscriberPendingAction::class;

    // ...
}
```

### Create a Pending Action

To create a pending action class, simply extend `PendingAction` like so, providing fluent methods to build up the pending action object that will then be used to execute the action:

``` php
use App\EmailList;
use Telkins\LaravelPendingAction\PendingAction;

class CreateSubscriberPendingAction extends PendingAction
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

Once you have built your action and pending action classes, then you can begin to use them.  There are three main steps to preparing your action and executing it:
1. Call the static `prep()` method on your `Action` class.  This returns the pending action object.
2. Using the pending action object, provide the parameters needed for the action.
3. Finally, call the `execute()` method on the pending action object.

Here is an example of preparing and executing an action all at once:

```php
UpdateLeaderboard::prep()
    ->leaderboard($leaderboard)
    ->forPlayer($player)
    ->withScore($score)
    ->execute();
```

Here is an example of using the pending action object to provide different parameters to carry out the action for different scenarios:

```php
$pendingUpdateLeaderboard = UpdateLeaderboard::prep()
    ->leaderboard($leaderboard);

$pendingUpdateLeaderboard->forPlayer($greg)
    ->withScore($gregsScore)
    ->execute();

$pendingUpdateLeaderboard->forPlayer($peter)
    ->withScore($petersScore)
    ->execute();
```

### Add a Pending Action Constructor to Improve "Prep"

Sometimes you may not want to litter your code with somewhat redundant or obvious method calls.  For example, if you have an action class that is meant to update a leaderboard, then you may not want to have to specifically call a `leaderboard()` method.  By creating a constructor on your pending action class, you can pass in the leaderboard via the action's `prep()` method.

Here is how your pending action class might look in order to take advantage of this feature:

``` php
class UpdateLeaderboardPendingAction extends PendingAction
{
    public Leaderboard $leaderboard;

    public function __construct(Leaderboard $leaderboard)
    {
        $this->leaderboard = $leaderboard;
    }

    // ...
}
```

You can then use it like so:

```php
UpdateLeaderboard::prep($leaderboard)
    ->forPlayer($player)
    ->withScore($score)
    ->execute();
```

**NOTE: This functionality is not IDE-friendly and the developer will be responsible for passing the right types of arguments in the right order to their constructor via `prep()`.**

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
