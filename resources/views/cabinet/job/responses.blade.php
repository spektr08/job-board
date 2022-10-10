<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Job Responses') }}
        </h2>

    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-wrapper">
                <div class="p-6 bg-white border-b border-gray-200 prose prose-slate">
                    <div class="not-prose relative bg-slate-50 rounded-xl overflow-hidden dark:bg-slate-800/25"><div style="background-position:10px 10px" class="absolute inset-0 bg-grid-slate-100 [mask-image:linear-gradient(0deg,#fff,rgba(255,255,255,0.6))] dark:bg-grid-slate-700/25 dark:[mask-image:linear-gradient(0deg,rgba(255,255,255,0.1),rgba(255,255,255,0.5))]"></div>
                        <div class="relative rounded-xl overflow-auto">
                            <div class="shadow-sm overflow-hidden my-8">
                                <table class="border-collapse table-auto w-full text-sm">
                                    <thead>
                                    <tr>
                                        <th class="border-b dark:border-slate-600 font-medium p-4 pl-8 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">Response</th>
                                        <th class="border-b dark:border-slate-600 font-medium p-4 pt-0 pb-3 text-slate-400 dark:text-slate-200 text-left">User</th>
                                    </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-slate-800">
                                    @foreach ($responses as $response)
                                        <tr>
                                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 pl-8 text-slate-500 dark:text-slate-400">{{$response->content}}</td>
                                            <td class="border-b border-slate-100 dark:border-slate-700 p-4 text-slate-500 dark:text-slate-400">{{$response->user->email}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="absolute inset-0 pointer-events-none border border-black/5 rounded-xl dark:border-white/5"></div>
                    </div>
                    {{ $responses->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
