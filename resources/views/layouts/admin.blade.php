<x-app-layout>
    <div class="flex h-screen">
        @livewire('admin.layouts.sidebar')
        <main class="flex-1 ml-72 flex flex-col">
            @livewire('admin.layouts.navigation')
            <div class="flex-1 p-8 overflow-y-auto">
                {{ $slot }}
            </div>
        </main>
    </div>
</x-app-layout>