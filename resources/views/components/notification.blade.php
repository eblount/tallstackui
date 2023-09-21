<div x-data="tasteui_notificationBase()"
     x-cloak
     x-on:tasteui:notification.window="add($event)"
     @class([
        'pointer-events-none fixed inset-0 flex flex-col items-end justify-end gap-y-2 px-4 py-4',
        'md:justify-start' => str_contains($position, 'top-'),
        'md:justify-end'   => str_contains($position, 'bottom-'),
        $zIndex
    ])>
    <template x-for="notification in notifications" :key="notification.id">
        <div x-data="tasteui_notificationLoop(notification)" x-show="show" @class([
                'flex w-full flex-col items-center space-y-4',
                'md:items-start' => $position === 'top-left' || $position === 'bottom-left',
                'md:items-end'   => $position === 'top-right' || $position === 'bottom-right',
            ])>
            <div x-show="show"
                 x-transition:enter="transform ease-out duration-300 transition"
                 x-transition:enter-start="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
                 class="pointer-events-auto w-full max-w-sm overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="p-4">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <div x-show="notification.type === 'success'">
                                <x-icon icon="check-circle" class="h-6 w-6 text-green-400" />
                            </div>
                            <div x-show="notification.type === 'error'">
                                <x-icon icon="x-circle" class="h-6 w-6 text-red-400" />
                            </div>
                            <div x-show="notification.type === 'info'">
                                <x-icon icon="information-circle" class="h-6 w-6 text-blue-400" />
                            </div>
                            <div x-show="notification.type === 'warning'">
                                <x-icon icon="exclamation-circle" class="h-6 w-6 text-yellow-400" />
                            </div>
                            <div x-show="notification.type === 'question'">
                                <x-icon icon="question-mark-circle" class="h-6 w-6 text-secondary-400" />
                            </div>
                        </div>
                        <div class="ml-3 w-0 flex-1 pt-0.5">
                            <p class="text-sm text-gray-800" x-bind:class="{ 'font-medium' : !notification.confirm, 'font-semibold' : notification.confirm }" x-text="notification.title"></p>
                            <p class="mt-1 text-sm text-gray-700" x-text="notification.description"></p>
                            <template x-if="notification.type === 'question'">
                                <div class="mt-3 flex gap-x-3">
                                    <button class="rounded-md bg-white text-sm font-semibold text-primary-600 focus:outline-none"
                                            x-on:click="accept(notification)"
                                            x-text="notification.options.confirm.text"></button>
                                    <button class="rounded-md bg-white text-sm font-medium text-secondary-700 focus:outline-none"
                                            x-on:click="reject(notification)"
                                            x-text="notification.options.cancel.text"></button>
                                </div>
                            </template>
                        </div>
                        <div class="ml-4 flex flex-shrink-0">
                            <button x-on:click="hide()" type="button" class="inline-flex rounded-md bg-white text-gray-400 focus:outline-none focus:ring-0">
                                <x-icon icon="x-mark" class="h-5 w-5" />
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>