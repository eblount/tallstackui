<?php

use Illuminate\View\ViewException;

it('can render', function () {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar" footer="Foo bar baz">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Foo bar', 'Bar Baz', 'Foo bar baz');
});

it('can render footer attributes when is not string', function () {
    $slide = <<<'HTML'
    <x-slide title="Foo Bar">
        Bar Baz
        <x-slot:footer class="foo-bar-baz-bah">
            Footer    
        </x-slot:footer>
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Foo Bar', 'Bar Baz', 'Footer', 'foo-bar-baz-bah');
});

it('can thrown exception when wire is empty', function () {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The slide [wire] property cannot be an empty string');

    $slide = <<<'HTML'
    <x-slide wire="">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Foo bar', 'Bar Baz', 'Foo bar baz');
});

it('can thrown exception when size is unnaceptable', function (string $size) {
    $this->expectException(ViewException::class);
    $this->expectExceptionMessage('The slide size must be one of the following: [sm, md, lg, xl, 2xl, 3xl, 4xl, 5xl, 6xl, 7xl, full]');

    $slide = <<<HTML
    <x-slide size="$size">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Bar Baz');
})->with([
    'foo',
    'bar',
    '8xl',
    '9xl',
    '10xl',
]);

it('can thrown exception when z-index does not contains prefix', function () {
    $this->expectException(ViewException::class);

    $slide = <<<'HTML'
    <x-slide z-index="50">
    Bar Baz
    </x-slide>
    HTML;

    expect($slide)->render()
        ->toContain('Bar Baz');
});
