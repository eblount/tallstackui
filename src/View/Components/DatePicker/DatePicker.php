<?php

namespace TallStackUi\View\Components\DatePicker;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Personalization\SoftPersonalization;
use TallStackUi\View\Components\BaseComponent;

#[SoftPersonalization('datepicker')]
class DatePicker extends BaseComponent implements Personalization
{
    public function __construct(
        public ?bool $rangeMode = false,
        public ?bool $timePicker = false,
        public ?bool $helperBtns = false,
        public ?string $label = 'Select Date',
        public ?array $disabledDates = [],
    ) {
        //

    }

    public function blade(): View
    {
        return view('tallstack-ui::components.datepicker.datepicker');
    }

    public function personalization(): array
    {
        return Arr::dot([]);
    }
}
