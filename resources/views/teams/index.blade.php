<x-layouts::app :title="__('All Clinics')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('All Clinics') }}</flux:heading>

            @can(\App\Enums\Permission::CREATE_TEAM)
                <flux:button :href="route('teams.create')" wire:navigate>
                    {{ __('Add new clinic') }}
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
                    </tr>
                </thead>
                <tbody class="divide-y divide-neutral-200 dark:divide-neutral-700">
                    @foreach ($teams as $team)
                        <tr class="bg-white dark:bg-neutral-900">
                            <td class="px-4 py-3 text-neutral-800 dark:text-neutral-200">
                                {{ $team->name }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div>
            {{ $teams->links() }}
        </div>
    </div>
</x-layouts::app>
