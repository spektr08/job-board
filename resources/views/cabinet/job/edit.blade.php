<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Job Vacancy') }}
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form method="POST" action="">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="title" :value="__('Title')" />

                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="$jobVacancy->title" required autofocus />

                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />

                            <textarea id="description" class="block mt-1 w-full" type="text" name="description"  >{{$jobVacancy->description}}</textarea>

                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button class="ml-3">
                                {{ __('Edit') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
