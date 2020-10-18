<?php

declare(strict_types=1);

namespace Tests\Validation\Unit;

use Validation\ValidationResult;
use PHPUnit\Framework\TestCase;

class ValidationResultTest extends TestCase
{

    /**
     * @test
     */
    public function itWillStoreDefaultsOnCreation(): void
    {
        $instance = new ValidationResult('a');

        self::assertSame('a', $instance->getValue());
        self::assertTrue($instance->isValid());
        self::assertEquals([], $instance->getErrors());
        self::assertEquals([], $instance->getErrorsByPath());
    }

    /**
     * @test
     */
    public function itWillStoreDataOnCreationWithErrors(): void
    {
        $instance = ValidationResult::withErrors('a', ['' => ['b', 'c']]);

        self::assertSame('a', $instance->getValue());
        self::assertFalse($instance->isValid());
        self::assertEquals(['b', 'c'], $instance->getErrors());
        self::assertEquals(['' => ['b', 'c']], $instance->getErrorsByPath());
    }

    /**
     * @test
     */
    public function itWillStoreDataOnCreationWithErrorsButNoneProvided(): void
    {
        $instance = ValidationResult::withErrors('a', []);

        self::assertSame('a', $instance->getValue());
        self::assertTrue($instance->isValid());
        self::assertEquals([], $instance->getErrors());
        self::assertEquals([], $instance->getErrorsByPath());
    }

    /**
     * @test
     */
    public function itWillAddErrorsCorrectly(): void
    {
        $instance = new ValidationResult('a');

        $instance->addError('err1');
        $instance->addError('err2');

        $instance->addToPath('p1');

        $instance->addError('err3');
        $instance->addError('err4');

        $instance->addToPath('p2');

        $instance->addError('err5');
        $instance->addError('err6');

        $instance->removeLastPath();

        $instance->addError('err7');

        $instance->removeLastPath();

        $instance->addError('err8');

        self::assertEquals(
            [
                '' => [
                    'err1',
                    'err2',
                    'err8',
                ],
                'p1' => [
                    'err3',
                    'err4',
                    'err7',
                ],
                'p1.p2' => [
                    'err5',
                    'err6',
                ],
            ],
            $instance->getErrorsByPath(),
        );
    }

    /**
     * @test
     */
    public function itWillCorrectlyDetermineValidity(): void
    {
        $instance1 = new ValidationResult('a');

        self::assertTrue($instance1->isValid());

        $instance1->addError('err1');

        self::assertFalse($instance1->isValid());

        $instance2 = new ValidationResult('a');
        $instance2->addToPath('p1');
        $instance2->addError('err1');

        self::assertFalse($instance2->isValid());
    }

    /**
     * @test
     */
    public function itWillGetErrorsCorrectly(): void
    {
        $instance = new ValidationResult('a');

        $instance->addError('err1');
        $instance->addError('err2');

        $instance->addToPath('p1');

        $instance->addError('err3');
        $instance->addError('err4');

        $instance->addToPath('p2');

        $instance->addError('err5');
        $instance->addError('err6');

        $instance->removeLastPath();

        $instance->addError('err7');

        $instance->removeLastPath();

        $instance->addError('err8');

        self::assertEquals(
            [
                'err1',
                'err2',
                'err8',
                'p1' => [
                    'err3',
                    'err4',
                    'err7',
                    'p2' => [
                        'err5',
                        'err6',
                    ],
                ],
            ],
            $instance->getErrors(),
        );
    }

    /**
     * @test
     */
    public function itWillMergeErrorsCorrectly(): void
    {
        $instance1 = new ValidationResult('a');

        $instance1->addError('err1');
        $instance1->addError('err2');

        $instance1->addToPath('p1');

        $instance1->addError('err3');
        $instance1->addError('err4');

        $instance1->addToPath('p2');

        $instance1->addError('err5');
        $instance1->addError('err6');

        $instance1->removeLastPath();

        $instance1->addError('err7');

        $instance1->removeLastPath();

        $instance1->addError('err8');

        $instance2 = new ValidationResult('b');

        $instance2->addError('err9');
        $instance2->addError('err10');

        $instance2->addToPath('p1');

        $instance2->addError('err11');
        $instance2->addError('err12');

        $instance2->addToPath('p2');

        $instance2->addError('err13');
        $instance2->addError('err14');

        $instance2->removeLastPath();

        $instance2->addError('err15');

        $instance2->removeLastPath();

        $instance2->addError('err16');

        $instance2->addToPath('p3');

        $instance2->addError('err17');
        $instance2->addError('err18');

        $instance1->mergeAtCurrentPath($instance2);

        self::assertEquals(
            [
                'err1',
                'err2',
                'err8',
                'p1' => [
                    'err3',
                    'err4',
                    'err7',
                    'p2' => [
                        'err5',
                        'err6',
                        'err13',
                        'err14',
                    ],
                    'err11',
                    'err12',
                    'err15',
                ],
                'err9',
                'err10',
                'err16',
                'p3' => [
                    'err17',
                    'err18',
                ],
            ],
            $instance1->getErrors(),
        );
    }
}
