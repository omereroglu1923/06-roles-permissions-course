<x-layouts::app :title="__('Edit Task')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        <flux:heading size="xl">{{ __('Edit Task') }}</flux:heading>

        <form action="{{ route('tasks.update', $task) }}" method="POST" class="flex flex-col gap-4 max-w-md">
            @csrf
            @method('PUT')
            <flux:input name="name" label="{{ __('Name') }}" value="{{ old('name', $task->name) }}" />
            <flux:input type="date" name="due_date" label="{{ __('Due Date') }}"
                value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}" />
            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
        </form>
    </div>
</x-layouts::app>
