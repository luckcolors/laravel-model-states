<?php

namespace Spatie\ModelStates\Tests\Dummy\Transitions;

use PHPUnit\Framework\Assert;
use Spatie\ModelStates\State;
use Spatie\ModelStates\Tests\Dummy\DummyDependency;
use Spatie\ModelStates\Tests\Dummy\OtherModelStates\StateY;
use Spatie\ModelStates\Tests\Dummy\TestModelWithCustomTransition;
use Spatie\ModelStates\Transition;
use Spatie\ModelStates\TransitionContext;

class CustomTransition extends Transition
{
    private TestModelWithCustomTransition $model;

    private string $message;


    public function __construct($model, ...$transitionArgs)
    {
        if ($model instanceof TransitionContext) {
            $this->model = $model->model;
        } else {
            $this->model = $model;
        }

        if (array_key_exists(0, $transitionArgs)) {
            $this->message = $transitionArgs[0];
        }
    }

    public function handle(DummyDependency $dummyDependency): TestModelWithCustomTransition
    {
        Assert::assertNotNull($dummyDependency);
        Assert::assertInstanceOf(TestModelWithCustomTransition::class, $this->model);


        $this->model->fill([
            'state' => StateY::class,
            'message' => $this->message,
        ])->save();

        return $this->model->refresh();
    }
}
