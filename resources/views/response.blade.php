<x-main-layout>

                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-wrapper">
                            <div class="p-6 bg-white border-b border-gray-200 prose prose-slate">
                                <h1 class="text-center font-semibold">Response to Job vacancy {{$jobVacancy->title}}</h1>
                                <form method="POST" action="">
                                    @csrf
                                    <div class="pt-5">
                                        <div>
                                            <x-input-label for="content" :value="__('Response')" />

                                            <textarea id="content" class="block mt-1 w-full" type="text" name="content"  ></textarea>

                                            <x-input-error :messages="$errors->get('content')" class="mt-2" />
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end mt-4">
                                        <x-primary-button class="ml-3">
                                            {{ __('Response') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
</x-main-layout>

