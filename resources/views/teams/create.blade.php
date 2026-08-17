<x-layouts::app :title="__('New Clinic')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        <flux:heading size="xl">{{ __('New Clinic') }}</flux:heading>

        <flux:separator />

        <div class="w-full max-w-lg">
            <form method="POST" action="{{ route('teams.store') }}" class="space-y-6">
                @csrf

                <flux:input name="clinic_name" :label="__('Clinic Name')" :value="old('clinic_name')" required />

                <flux:heading size="lg">{{ __('Select User') }}</flux:heading>

                <flux:select name="user_id" :label="__('User')">
                    <flux:select.option value="">{{ __('-- SELECT USER --') }}</flux:select.option>
                    @foreach ($users as $id => $name)
                        <flux:select.option value="{{ $id }}" :selected="old('user_id') == $id">
                            {{ $name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:heading size="lg">{{ __('Or Create a new User') }}</flux:heading>

                <flux:input name="name" :label="__('User Name')" :value="old('name')" />

                <flux:input name="email" type="email" :label="__('User Email')" :value="old('email')" />

                <flux:input name="password" type="password" :label="__('User Password')" />

                <flux:button type="submit" variant="primary">
                    {{ __('Save') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::app>
