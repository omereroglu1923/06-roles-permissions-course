<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    @include('partials.head')
</head>

<body @class([
    'min-h-screen',
    'bg-white dark:bg-zinc-800' => !auth()->user()->is_admin,
    'bg-amber-50 dark:bg-amber-950' => auth()->user()->is_admin,
])>
    <flux:sidebar sticky collapsible="mobile"
        class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.header>
            <x-app-logo :sidebar="true" href="{{ route('dashboard') }}" wire:navigate />
            <flux:sidebar.collapse class="lg:hidden" />
        </flux:sidebar.header>

        @can(\App\Enums\Permission::SWITCH_TEAM)
            <flux:dropdown position="bottom" align="start">
                <flux:button variant="ghost"
                    class="group w-full justify-start in-data-flux-sidebar-collapsed-desktop:justify-center">
                    <flux:icon name="users" class="hidden size-4 in-data-flux-sidebar-collapsed-desktop:block" />
                    <span
                        class="truncate font-semibold in-data-flux-sidebar-collapsed-desktop:hidden">{{ Auth::user()->currentTeam->name }}</span>
                    <flux:icon name="chevrons-up-down" variant="micro"
                        class="ms-auto size-4 in-data-flux-sidebar-collapsed-desktop:hidden" />
                </flux:button>

                <flux:menu class="min-w-56">
                    @foreach (Auth::user()->teams as $team)
                        <flux:menu.item :href="route('team.change', $team->id)" class="cursor-pointer">
                            <div class="flex w-full items-center justify-between">
                                <span>{{ $team->name }}</span>
                                @if (Auth::user()->current_team_id === $team->id)
                                    <flux:icon name="check" class="size-4" />
                                @endif
                            </div>
                        </flux:menu.item>
                    @endforeach
                </flux:menu>
            </flux:dropdown>
        @endcan

        <flux:sidebar.nav>
            <flux:sidebar.group :heading="__('Platform')" class="grid">
                <flux:sidebar.item icon="home" :href="route('dashboard')" :current="request()->routeIs('dashboard')"
                    wire:navigate>
                    {{ __('Dashboard') }}
                </flux:sidebar.item>

                @if (auth()->user()->is_admin)
                    <flux:sidebar.item icon="check-circle" :href="route('admin.tasks.index')"
                        :current="request()->routeIs('admin.tasks.index')" wire:navigate>
                        {{ __('Tasks') }}
                    </flux:sidebar.item>
                @else
                    <flux:sidebar.item icon="check-circle" :href="route('user.tasks.index')"
                        :current="request()->routeIs('user.tasks.index')" wire:navigate>
                        {{ __('Tasks') }}
                    </flux:sidebar.item>
                @endif

                @can(\App\Enums\Permission::LIST_TEAM)
                    <flux:sidebar.item icon="building-office" :href="route('teams.index')"
                        :current="request()->routeIs('teams.*')" wire:navigate>
                        {{ __('Clinics') }}
                    </flux:sidebar.item>
                @endcan
            </flux:sidebar.group>
            <flux:sidebar.item icon="clipboard-document-list" :href="route('tasks.index')"
                :current="request()->routeIs('tasks.*')" wire:navigate>
                {{ __('Tasks (Gates/Policies)') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <flux:spacer />

        <flux:sidebar.nav>
            <flux:sidebar.item icon="folder-git-2" href="https://github.com/laravel/livewire-starter-kit"
                target="_blank">
                {{ __('Repository') }}
            </flux:sidebar.item>

            <flux:sidebar.item icon="book-open-text" href="https://laravel.com/docs/starter-kits#livewire"
                target="_blank">
                {{ __('Documentation') }}
            </flux:sidebar.item>
        </flux:sidebar.nav>

        <x-desktop-user-menu class="hidden lg:block" :name="auth()->user()->name" />
    </flux:sidebar>

    <!-- Mobile User Menu -->
    <flux:header class="lg:hidden">
        <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

        <flux:spacer />

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="auth()->user()->initials()" icon-trailing="chevron-down" />

            <flux:menu>
                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                            <flux:avatar :name="auth()->user()->name" :initials="auth()->user()->initials()" />

                            <div class="grid flex-1 text-start text-sm leading-tight">
                                <flux:heading class="truncate">{{ auth()->user()->name }}</flux:heading>
                                <flux:text class="truncate">{{ auth()->user()->email }}</flux:text>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <flux:menu.radio.group>
                    <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                        {{ __('Settings') }}
                    </flux:menu.item>
                </flux:menu.radio.group>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                        class="w-full cursor-pointer" data-test="logout-button">
                        {{ __('Log out') }}
                    </flux:menu.item>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    {{ $slot }}

    @persist('toast')
        <flux:toast.group>
            <flux:toast />
        </flux:toast.group>
    @endpersist

    @fluxScripts
</body>

</html>
