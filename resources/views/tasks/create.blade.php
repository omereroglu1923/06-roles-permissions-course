<x-layouts::app :title="__('Add Task')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        <flux:heading size="xl">{{ __('Add New Task') }}</flux:heading>

        <form action="{{ route('tasks.store') }}" method="POST" class="flex flex-col gap-4 max-w-md">
            @csrf
            <flux:input name="name" label="{{ __('Name') }}" value="{{ old('name') }}" />
            <flux:input type="date" name="due_date" label="{{ __('Due Date') }}" value="{{ old('due_date') }}" />

            <flux:select name="assigned_to_user_id" :label="__('Assignee (Doctor/Staff)')"
                :placeholder="__('Select an assignee')">
                @foreach ($assignees as $id => $name)
                    <flux:select.option value="{{ $id }}" :selected="old('assigned_to_user_id') == $id">
                        {{ $name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:select name="patient_id" :label="__('Patient')" :placeholder="__('Select a patient')">
                @foreach ($patients as $id => $name)
                    <flux:select.option value="{{ $id }}" :selected="old('patient_id') == $id">
                        {{ $name }}</flux:select.option>
                @endforeach
            </flux:select>

            <flux:button type="submit" variant="primary">{{ __('Save') }}</flux:button>
        </form>
    </div>
</x-layouts::app>
