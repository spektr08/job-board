<x-main-layout>

                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg content-wrapper">
                            <div class="p-6 bg-white border-b border-gray-200 prose prose-slate">
                                <h1 class="text-center font-semibold">{{$jobVacancy->title}}</h1>
                                <div class="pb-5">
                                    {!! nl2br(e($jobVacancy->description)) !!}
                                </div>
                                @auth
                                    @if($jobVacancy->canResponse(Auth::id()))
                                    <a href="{{ route('job.response', $jobVacancy) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Response</a>
                                    @endif
                                @endauth
                                <p class="pt-3">
                                    Likes: {{$jobVacancy->favorites->count()}}
                                @auth
                                    @if($jobVacancy->canBeLikedByUser(Auth::id()))
                                        <form method="POST" action="{{ route('favorite.job', $jobVacancy->id) }}" style="display: contents;">
                                            @csrf
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Like</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('favorite.delete', $jobVacancy->getFavoriteId(Auth::id())) }}" style="display: contents;">
                                            @csrf
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Unlike</button>
                                        </form>
                                        @endif
                                    @endauth
                                </p>
                                <div class="pt-4 text-right">
                                    <hr>
                                    <p>User: {{$jobVacancy->user->email}}</p>
                                    <p>Likes: {{$jobVacancy->user->favorites->count()}}</p>
                                    @auth
                                        @if($jobVacancy->user->canBeLikedByUser(Auth::id()))
                                            <form method="POST" action="{{ route('favorite.user', $jobVacancy->user) }}" style="display: contents;">
                                            @csrf
                                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Like</button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('favorite.delete', $jobVacancy->user->getFavoriteId(Auth::id())) }}" style="display: contents;">
                                                @csrf
                                                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Unlike</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                        </div>
                    </div>
</x-main-layout>

