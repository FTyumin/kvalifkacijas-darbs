@extends('layouts.app')

@section('title', 'dashboard')

@section('content')

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    Already watched(bookmark list)
                    @if ($message = Session::get('success'))
                          <div class="p-4 mb-3 bg-green-400 rounded">
                              <p class="text-green-800">{{ $message }}</p>
                          </div>
                      @endif
                    <div class="flex flex-col">
                        <div class="overflow-x-auto">
                            <div class="p-1.5 w-full inline-block align-middle">
                                <div class="overflow-hidden border rounded-lg">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                                >
                                                    ID
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                                >
                                                   movie Name
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                                >
                                                    Director
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-xs font-bold text-left text-gray-500 uppercase"
                                                >
                                                    Image
                                                </th>
                                                <th
                                                    scope="col"
                                                    class="px-6 py-3 text-xs font-bold text-right text-gray-500 uppercase"
                                                >
                                                    Delete
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-200">
                                            @foreach ($movies as $movie)
                                                
                                            
                                            <tr>
                                                <td
                                                    class="px-6 py-4 text-sm font-medium text-gray-800 whitespace-nowrap"
                                                >
                                                    {{ $movie->id }}
                                                </td>
                                                <td
                                                    class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap"
                                                >
                                                {{ $movie->name }}
                                                   
                                                </td>
                                                <td
                                                    class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap"
                                                >
                                                {{ $movie->director->name }}
                                                    
                                                </td>
                                                <td
                                                    class="px-6 py-4 text-sm text-gray-800 whitespace-nowrap"
                                                >
                                              <img src="{{ $movie->image }}" alt="{{ $movie->image }}" class="w-12 h-12">  
                                                    
                                                </td>
                                               
                                                <td
                                                    class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap"
                                                >
                                                <form action="{{ route('favorite.remove',$movie->id) }}" method="POST"
                                                    onsubmit="return confirm('{{ trans('Are you sure? ') }}');"
                                                    style="display: inline-block; background-color:#E6E6FA">
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                    <input type="submit" class="px-4 py-2 rounded"
                                                        value="Delete">
                                                </form>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
            </div>
        </div>
    </div>
@endsection