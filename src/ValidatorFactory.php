<?php

declare(strict_types=1);

namespace Validation;

use Validation\Rule\Rule;
use Validation\Checker\Checker;
use Validation\Exception\NoCheckersRegistered;

use function array_map;
use function get_class;

class ValidatorFactory
{

    private int $identifier;

    /**
     * @var array
     * @psalm-var array<class-string<Rule>, list<int>>
     */
    private array $checkersByRule;

    /**
     * @var array
     * @psalm-var array<int, Checker>
     */
    private array $checkers;

    public function __construct()
    {
        $this->identifier = 0;
        $this->checkers = [];
        $this->checkersByRule = [];
    }

    /**
     * @psalm-template I
     * @psalm-template O
     *
     * @param Rule $rule
     * @psalm-param Rule<I, O> $rule
     *
     * @return Validator
     * @psalm-return Validator<I, O>
     *
     * @throws NoCheckersRegistered
     */
    public function create(Rule $rule): Validator
    {
        $ruleClass = get_class($rule);
        $checkerIds = $this->checkersByRule[$ruleClass] ?? null;
        if ($checkerIds === null) {
            throw new NoCheckersRegistered($ruleClass);
        }

        /** @psalm-var list<Checker<Rule<I, O>>> $checkers */
        $checkers = array_map(
            fn(int $id) => $this->checkers[$id],
            $checkerIds,
        );

        return new Validator($rule, $checkers);
    }

    public function register(Checker $checker): void
    {
        $id = $this->identifier;

        foreach ($checker->canCheck() as $ruleFqcn) {
            $this->checkersByRule[$ruleFqcn][] = $id;
        }

        $this->checkers[$id] = $checker;

        $this->identifier++;
    }
}
