# php-StateMachine

[![Latest Stable Version](http://poser.pugx.org/halfowl/statemachine/v)](https://packagist.org/packages/halfowl/statemachine) [![Total Downloads](http://poser.pugx.org/halfowl/statemachine/downloads)](https://packagist.org/packages/halfowl/statemachine) [![License](http://poser.pugx.org/halfowl/statemachine/license)](https://packagist.org/packages/halfowl/statemachine) [![PHP Version Require](http://poser.pugx.org/halfowl/statemachine/require/php)](https://packagist.org/packages/halfowl/statemachine)

State Machines in PHP made safe and easy.

## Installation

Using Composer:
```
$ composer require halfowl/statemachine
```

## Example

Imagine an application that holds the state of an article. It has the
following states:

- Draft
- Awaiting Copy Edit
- Published

A new article starts in "Draft", gets progressed to "Awaiting Copy Edit",
and subsequently "Published". An article in "Awaiting Copy Edit" can go
back to being a "Draft".

With this library, you can model the above as:

```php
<?php

use Halfowl\StateMachine\{State, StateMachine, StateTransition};

$draft = new State("DRAFT");
$awaitingCopy = new State("AWAITING_COPY_EDIT");
$published = new State("PUBLISHED");

// StateTransitions define legal state transitions for the StateMachine.
// The second parameter of the constructor takes in an array of States
// that the first State can transition to.
$fromDraft = new StateTransition($draft, [$awaitingCopy]);  // draft->awaiting copy
$fromAwaitingCopy = new StateTransition($awaitingCopy, [$draft, $published]);  // awaiting copy->draft/published

// Put that together into a StateMachine:
$sm = new StateMachine(
    transitions: [
        $fromDraft,
        $fromAwaitingCopy,
    ],
    starting: $draft,
);

$sm->current();                  // => DRAFT
$sm->transition($awaitingCopy);  // => AWAITING_COPY_EDIT
$sm->transition($published);     // => PUBLISHED
```

## API

(Proper auto-generated docs is WIP, tracking in #4)

### State

Reference: https://github.com/half0wl/php-StateMachine/blob/main/src/StateInterface.php

* `getName(): string`

### StateMachine

Reference: https://github.com/half0wl/php-StateMachine/blob/main/src/StateMachineInterface.php

* `current(): State`
* `can(): bool`
* `is(State $s): bool`
* `transition(State $next): void`

### StateTransition

Reference: https://github.com/half0wl/php-StateMachine/blob/main/src/StateTransitionInterface.php

* `src(): State`
* `dsts(): State[]`
* `inDst(): bool`

## License

[MIT](LICENSE)
