<?php

namespace TallStackUi\View\Components\Wrapper;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use TallStackUi\Support\Personalizations\Contracts\Personalize;

class Input extends Component implements Personalize
{
    public function __construct(
        public ?string $computed = null,
        public ?string $label = null,
        public ?string $hint = null,
        public ?string $alpine = null,
        public bool $error = false,
        public bool $validate = false,
        public bool $password = false,
    ) {
        //
    }

    public function personalization(): array
    {
        return ['wrapper' => 'relative rounded-md shadow-sm'];
    }

    public function render(): View
    {
        return view('tallstack-ui::components.wrapper.input');
    }
}
