<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use InvalidArgumentException;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;

#[SoftPersonalization('tooltip')]
class Tooltip extends BaseComponent implements Personalization
{
    public function __construct(
        public ?string $text = null,
        public ?string $icon = 'question-mark-circle',
        public string $color = 'primary',
        public ?bool $xs = null,
        public ?bool $sm = null,
        public ?bool $md = null,
        public ?bool $lg = null,
        public ?bool $solid = false,
        public ?bool $outline = false,
        #[SkipDebug]
        public ?string $size = null,
        public ?string $position = 'top',
        #[SkipDebug]
        public ?string $style = null,
    ) {
        $this->size = $this->lg ? 'lg' : ($this->md ? 'md' : ($this->xs ? 'xs' : 'sm'));
        $this->style = $this->outline ? 'outline' : ($this->solid ? 'solid' : config('tallstackui.icon', 'solid'));
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.tooltip');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => 'inline-flex',
            'sizes' => [
                'xs' => 'h-4 w-4',
                'sm' => 'h-5 w-5',
                'md' => 'h-6 w-6',
                'lg' => 'h-7 w-7',
            ],
        ]);
    }

    protected function validate(): void
    {
        $positions = [
            'top',
            'top-start',
            'top-end',
            'bottom',
            'bottom-start',
            'bottom-end',
            'left',
            'left-start',
            'left-end',
            'right',
            'right-start',
            'right-end',
            'auto',
            'auto-start',
            'auto-end',
        ];

        if (! in_array($this->position, $positions)) {
            throw new InvalidArgumentException('The tooltip [position] must be one of the following: ['.implode(', ', $positions).']');
        }
    }
}
