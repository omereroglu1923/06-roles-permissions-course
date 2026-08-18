<x-layouts::app :title="__('New User')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 p-6">
        <flux:heading size="xl">{{ __('New User') }}</flux:heading>

        <flux:separator />

        <div class="w-full max-w-lg">
            <form method="POST" action="{{ route('users.store') }}" class="space-y-6">
                @csrf

                <flux:input name="name" :label="__('Name')" :value="old('name')" required />

                <flux:input name="email" type="email" :label="__('Email')" :value="old('email')" required />

                <flux:input name="password" type="password" :label="__('Password')" required viewable />

                <flux:select name="role_id" :label="__('Role')" :placeholder="__('Select a role')" required>
                    @foreach ($roles as $id => $name)
                        <flux:select.option value="{{ $id }}" :selected="old('role_id') == $id">
                            {{ $name }}
                        </flux:select.option>
                    @endforeach
                </flux:select>

                <flux:button type="submit" variant="primary">
                    {{ __('Save') }}
                </flux:button>
            </form>
        </div>
    </div>
</x-layouts::app>
