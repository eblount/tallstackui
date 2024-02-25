import {error} from '../../helpers';

export default (
    model,
    range,
    multiple,
    format,
    minDate,
    maxDate,
    minYear,
    maxYear,
    disable,
) => ({
  picker: {
    common: false,
    year: false,
    month: false,
  },
  format: format,
  model: model,
  value: '',
  day: '',
  month: '',
  year: '',
  days: [],
  blanks: [],
  range: {
    year: {
      min: minYear,
      max: maxYear,
      start: 0,
      first: 0,
      last: 0,
    },
  },
  date: {
    min: minDate,
    max: maxDate,
    start: null,
    end: null,
  },
  multiple: multiple,
  disable: disable,
  interval: null,
  selected: null,
  init() {
    const dayjs = this.dayjs;

    if (!dayjs) {
      return error('The dayjs library is not available. Please, review the docs.');
    }

    this.date.min = minDate ? dayjs(minDate) : null;
    this.date.max = maxDate ? dayjs(maxDate) : null;

    this.month = dayjs().month();
    this.year = dayjs().year();
    this.day = dayjs().day();

    this.calculate();

    // Checks if the model is defined and hydrates according to the mode (range, multiple, default) of the datepicker
    if (this.model) {
      if (range && this.model.length === 2) {
        this.date.start = dayjs(this.model[0]).$d;
        this.date.end = dayjs(this.model[1]).$d;
      } else if (this.multiple) {
        this.selected = this.model;
      } else {
        this.date.start = dayjs(this.model).$d;
        this.value = dayjs(this.model).format(this.format);
      }

      this.updateInputValue();
      this.picker.common = false;
    }
  },
  /**
   * Based on the type of datepicker, this function treats the clicked date and applies the appropriate
   * formatting and values.
   * @param {string} day
   */
  clicked(day) { // ??? clicked
    const selected = this.dayjs(`${this.year}-${this.month + 1}-${day}`);

    if (this.multiple) {
      // Toggle: Add if it doesn't exist, remove if it does
      this.selected = this.selected ?
            this.selected.includes(selected.format('YYYY-MM-DD')) ?
                this.selected.filter((date) => date !== selected.format('YYYY-MM-DD')) :
                [...this.selected, selected.format('YYYY-MM-DD')] :
            [selected.format('YYYY-MM-DD')];
    } else if (range) {
      if (this.date.start && !this.date.end && selected > this.date.start) {
        this.date.end = selected;
      } else {
        this.date.start = selected;
        this.date.end = null;
      }
    } else {
      this.date.start = selected;
      this.date.end = null;
    }

    this.updateInputValue();
  },
  /**
   * Checks if the date informed by the model is the same as the loop date
   * @param {string} day
   * @returns boolean
   */
  selectedDate(day) {
    if (!this.model) return false;

    const date = this.dayjs(`${this.year}-${this.month + 1}-${day}`);

    return this.model.includes(date.format('YYYY-MM-DD'));
  },
  /**
   * Checks if the given date is between the range date in order to colorize the range interval
   * @param {string} date
   * @returns boolean
   */
  dateInterval(date) {
    if (!range || !this.date.end) return false;

    const current = this.dayjs(date);
    const start = this.dayjs(this.date.start);
    const end = this.dayjs(this.date.end);

    return current.isAfter(start) &&
           current.isBefore(end) ||
           current.isSame(start) ||
           current.isSame(end);
  },
  /**
   * Generate calendar days based on the selected month and year.
   */
  calculate() { // calculate
    const dayjs = this.dayjs;

    const month = dayjs(`${this.year}-${this.month + 1}-01`).endOf('month').date();
    const week = dayjs(`${this.year}-${this.month + 1}-01`).day();

    this.blanks = Array.from({length: week}, (key, value) => value + 1);

    this.days = Array.from({length: month}, (key, value) => {
      const date = dayjs(`${this.year}-${this.month + 1}-${value + 1}`);
      const disabled = this.dateDisabled(date.toDate());

      return {day: value + 1, full: date, disabled};
    });
  },
  /**
   * Logic to make the helper buttons work according to the datepicker type
   * @param {string} type
   */
  changeDate(type) {
    let currentDate = this.dayjs();

    if (type === 'yesterday' || type === 'tomorrow') {
      currentDate = currentDate.add(type === 'yesterday' ? -1 : 1, 'day');
    } else if (type.startsWith('last')) {
      if (range) {
        const days = parseInt(type.replace('last', ''), 10);
        const startDate = currentDate.subtract(days, 'day').startOf('day');
        const endDate = currentDate.startOf('day');

        if (!this.dateDisabled(startDate.toDate()) && !this.dateDisabled(endDate.toDate())) {
          Object.assign(this, {startDate: startDate.toDate(), endDate: endDate.toDate()});
          this.updateInputValue();
          return;
        }
      } else {
        const daysToSubtract = parseInt(type.replace('last', ''), 10);
        currentDate = currentDate.subtract(daysToSubtract, 'day');
      }
    }

    const current = currentDate.format('YYYY-MM-DD');

    this.month = currentDate.month();
    this.year = currentDate.year();
    this.day = currentDate.date();
    this.model = current;
    this.value = currentDate.format(this.format);

    this.calculate();

    // Checks if there is a disabled date and if it corresponds to the selected date and clears the value if true
    this.days.forEach((date) => {
      if (current === date.full.format('YYYY-MM-DD') && date.isDisabled) {
        this.value = '';
      }
    });
  },
  /**
   * Handles items according to the datepicker type and display format
   */
  updateInputValue() {
    const startDateFormated = this.date.start ? this.dayjs(this.date.start).format(this.format) : '';
    const endDateFormated = this.date.end ? this.dayjs(this.date.end).format(this.format) : '';

    if (this.multiple) {
      this.model = this.selected;
      this.value = this.model.map((date) => this.dayjs(date).format(this.format)).join(', ');
    } else if (range) {
      this.model = [
        this.dayjs(this.date.start).format('YYYY-MM-DD'),
        this.date.end !== null ? this.dayjs(this.date.end).format('YYYY-MM-DD') : null,
      ];
      this.value = startDateFormated + ' - ' + endDateFormated;
      this.picker.common = this.date.start !== null;
    } else {
      this.model = this.date.start ? this.dayjs(this.date.start).format('YYYY-MM-DD') : null;
      this.value = startDateFormated;
      this.picker.common = false;
    }
  },
  isToday(day) {
    const today = this.dayjs();
    const date = this.dayjs(`${this.year}-${this.month + 1}-${day}`);

    return Boolean(today.isSame(date, 'day'));
  },
  dateDisabled(date) {
    return (this.date.min && date <= this.date.min) ||
            (this.date.max && date >= this.date.max) ||
            this.disable.includes(this.dayjs(date).format('YYYY-MM-DD'));
  },
  previousMonth() {
    this.month = (this.month === 0) ? 11 : this.month - 1;

    if (this.month === 11) this.year--;

    this.calculate();
  },
  nextMonth() {
    this.month = (this.month + 1) % 12;

    if (this.month === 0) this.year++;

    this.calculate();
  },
  previousYearRange(event) {
    e.stopPropagation();

    if (this.range.year.min !== null && this.range.year.first <= this.range.year.max) return;

    this.range.year.start -= 19;
  },
  nextYearRange(event) {
    event.stopPropagation();

    if (this.range.year.min !== null && this.range.year.last >= this.range.year.max) return;

    this.range.year.start += 19;
  },
  generateYearRange() {
    const startYear = this.range.year.start;

    const minYear = this.range.year.min ?? -Infinity;
    const maxYear = this.range.year.max ?? Infinity;

    const yearRange = Array.from({length: 20}, (_, index) => {
      const year = startYear + index;
      const disabled = year < minYear || year > maxYear;
      return {year, disabled};
    });

    this.range.year.first = yearRange[0]?.year;
    this.range.year.last = yearRange[yearRange.length - 1]?.year;

    return yearRange;
  },
  selectMonth(event, month) {
    event.stopPropagation();

    this.month = month;
    this.picker.month = false;

    this.calculate();
  },
  selectYear(event, year) {
    event.stopPropagation();

    this.year = year;
    this.picker.year = false;

    this.calculate();
  },
  toggleYear() {
    this.picker.year = true;

    this.range.year.start = this.year - 11;
  },
  /**
   * Reset all properties
   */
  clear() {
    this.model = this.value = this.date.start = this.date.end = this.selected = null;
  },
  // format(date, format)
  get period() {
    const dayjs = this.dayjs;

    return {
      week: dayjs.weekdaysShort().map((days) => days.charAt(0).toUpperCase() + days.slice(1)),
      month: dayjs.months().map((month) => month.charAt(0).toUpperCase() + month.slice(1)),
    };
  },
  /**
   * Get the dayjs library.
   * @return {Dayjs}
   */
  get dayjs() {
    return window.dayjs;
  },
});
