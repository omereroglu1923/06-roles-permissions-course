<x-layouts::app :title="__('Users')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Clinic Users') }}</flux:heading>

            @can(\App\Enums\Permission::CREATE_USER)
                <flux:button :href="route('users.create')" wire:navigate>
                    {{ __('Add new user') }}
                </flux:button>
            @endcan
        </div>

        <flux:separator />

        <div class="overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
            <table class="w-full text-sm">
                <thead class="bg-neutral-50 dark:bg-neutral-800">
                    <tr>
                        <th class="px-4 py-3 text-start font-medium text-neutral-600 dark:text-neutral-300">
                            {{ __('Name') }}
                        </th>
                        <th class="px-4 py-3 text-start font-medium text-neutral-600 dark:text-neutral-300">
                            {{ __('Email') }}
                        </th>
                        <th class="px-4 py-3 text-start font-medium text-neutral-600 dark:text-neutral-300">
                            {{ __('Role') }}
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach ($users as $user)
                        <tr class="bg-white dark:bg-neutral-900">
                            <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                {{ $user->name }}
                            </td>
                            <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                {{ $user->email }}
                            </td>
                            <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                {{ $user->roles->pluck('name')->join(', ') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
