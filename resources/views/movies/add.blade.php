@extends('layouts.app')

@section('content')

<div>
    <form method="POST" action="{{ route('movies.store') }}">
        @csrf
        <div>
            <label for="name">Movie title</label>

            <input type="text" id="name" name="name" required 
                autocomplete="name"
                class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                placeholder="Enter movie title"
            >
        </div>

        <div>>
            <label for="id">Movie title</label>

            <input type="text" id="id" name="id" required 
                autocomplete=""
                class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                placeholder="Enter movie ID"
            >
        </div>

        <fieldset>
            <legend class="text-white">Genres</legend>
            <ul>    
                @foreach($genres as $genre)
                <li>
                    <label class="text-white" for="genre_{{ $genre->id }}">
                        <input type="checkbox" 
                            id="genre_{{ $genre->id }}" 
                            name="genres[]" 
                            value="{{ $genre->id }}">
                        {{ $genre->name }}
                    </label>
                </li>
                @endforeach
            </ul>
        </fieldset>

        <div>
            <label for="">Director</label>
            <div class="director-selector">
                <div class="selected-directors" id="selectedDirector"></div>
                
                <input 
                    type="text" 
                    id="directorSearch" 
                    class="form-control" 
                    placeholder="Search director by name..."
                    autocomplete="off"
                >
                
                <div id="directorDropdown" class="dropdown" style="display: none;"></div>
            </div>
            
            <input type="hidden" name="director_id" id="directorIdInput" value="">
        </div>

        <div>
            <div class="form-group mb-3">
            <label>Actors</label>
            <div class="actor-selector">
                <!-- Selected actors display here as tags -->
                <div class="selected-actors" id="selectedActors"></div>
                
                <!-- Search input -->
                <input 
                    type="text" 
                    id="actorSearch" 
                    class="form-control" 
                    placeholder="Search actors by name..."
                    autocomplete="off"
                >
                
                <!-- Dropdown for search results -->
                <div id="dropdownActor" class="dropdown" style="display: none;"></div>
            </div>
            
            <div id="hiddenInputs"></div>
        </div>

        </div>
        <div>
            <label for="description">Movie title</label>

            <input type="text" id="description" name="description" 
                class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                placeholder="Enter a description(optional)"
            >
        </div>
        <div>
            <label for="year">Release year</label>

            <input type="text" id="year" name="year" required 
                class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                placeholder="Enter release year"
            >
        </div>

        <div>
             <label for="year">Poster URL</label>

            <input type="text" id="poster_url" name="poster_url"  
                class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                placeholder="Enter poster url"
            >
        </div>

        <button type="submit" class="btn btn-primary text-white mt-8">Save Movie</button>
    </form>
</div>
@endsection


@push('scripts')
<script>
    const selectedActors = new Set();
    const searchInput = document.getElementById('actorSearch');
    const dropdown = document.getElementById('dropdownActor');
    const dropdownDirectors = document.getElementById('dropdownDirector');
    const selectedActorsContainer = document.getElementById('selectedActors');
    const hiddenInputsContainer = document.getElementById('hiddenInputs');
    
    // Pre-populate with existing actors (for edit mode)
    // @if(isset($movie))
    //     const existingActors = @json($movie->actors);
    //     existingActors.forEach(actor => {
    //         const name = actor.first_name + ' ' + actor.last_name;
    //         addActor(actor.id, name, false);
    //     });
    // @endif

    let debounceTimer;

    // Search actors via AJAX
    function searchActors(query) {
        dropdown.innerHTML = '<div class="loading">Searching...</div>';
        dropdown.style.display = 'block';
        
        fetch(`{{ route('actors.search') }}?search=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                const people = Array.isArray(data) ? data : (data.results || []);
                const actors = people.map(person => ({
                    id: person.id,
                    name: `${person.first_name} ${person.last_name}`
                }));
                displayResults(actors);
            })
            .catch(error => {
                console.error('Error:', error);
                dropdown.innerHTML = '<div class="no-results">Error loading actors</div>';
            });
    }

    function searchDirector(query) {
        dropdownDirectors.innerHTML = '<div class="loading">Searching...</div>';
        dropdownDirectors.style.display = 'block';
        
        fetch(`{{ route('directors.search') }}?search=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                const people = Array.isArray(data) ? data : (data.results || []);
                const directors = people.map(person => ({
                    id: person.id,
                    name: `${person.first_name} ${person.last_name}`
                }));
                displayResults(directors);
            })
            .catch(error => {
                console.error('Error:', error);
                dropdownDirectors.innerHTML = '<div class="no-results">Error loading directors</div>';
            });
    }

    function displayResults(results, type) {
        // if()
        const availableResults = results.filter(actor => !selectedActors.has(actor.id));
        
        if (availableResults.length === 0) {
            dropdown.innerHTML = '<div class="no-results text-white">No actors found</div>';
            dropdown.style.display = 'block';
            return;
        }
        dropdown.innerHTML = availableResults.map(actor => `
            <div class="dropdown-item text-white" data-id="${actor.id}" data-name="${actor.name}">
                ${actor.name}
            </div>
        `).join('');
        
        dropdown.style.display = 'block';

        dropdown.querySelectorAll('.dropdown-item').forEach(item => {
            item.addEventListener('click', () => {
                addActor(
                    parseInt(item.dataset.id),
                    item.dataset.name
                );
            });
        });
    }

    function addActor(id, name, clearSearch = true) {
        if (selectedActors.has(id)) return;

        selectedActors.add(id);

        const tag = document.createElement('div');
        tag.className = 'actor-tag text-white';
        tag.innerHTML = `
            ${name}
            <button type="button" data-id="${id}">&times;</button>
        `;
        
        tag.querySelector('button').addEventListener('click', () => {
            removeActor(id, tag);
        });
        
        selectedActorsContainer.appendChild(tag);

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'actors[]';
        input.value = id;
        input.id = `actor-input-${id}`;
        hiddenInputsContainer.appendChild(input);

        if (clearSearch) {
            searchInput.value = '';
            dropdown.style.display = 'none';
        }
    }

    function removeActor(id, tagElement) {
        selectedActors.delete(id);
        tagElement.remove();
        document.getElementById(`actor-input-${id}`).remove();
    }

    searchInput.addEventListener('input', (e) => {
        clearTimeout(debounceTimer);
        const query = e.target.value.trim();

        if (query.length < 2) {
            dropdown.style.display = 'none';
            return;
        }

        debounceTimer = setTimeout(() => {
            searchActors(query);
        }, 300);
    });

    document.addEventListener('click', (e) => {
        if (!e.target.closest('.actor-selector')) {
            dropdown.style.display = 'none';
        }
    });

    // Director selector
const directorSearchInput = document.getElementById('directorSearch');
const directorDropdown = document.getElementById('directorDropdown');
const selectedDirectorContainer = document.getElementById('selectedDirector');
const directorIdInput = document.getElementById('directorIdInput');

let selectedDirectorId = null;
let directorDebounceTimer;


function searchDirectors(query) {
    directorDropdown.innerHTML = '<div class="loading">Searching...</div>';
    directorDropdown.style.display = 'block';
    
    fetch(`{{ route('directors.search') }}?search=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            const people = Array.isArray(data) ? data : (data.results || []);
            const directors = people.map(person => ({
                id: person.id,
                name: `${person.first_name} ${person.last_name}`
            }));
            displayDirectorResults(directors);
        })
        .catch(error => {
            console.error('Error:', error);
            directorDropdown.innerHTML = '<div class="no-results">Error loading directors</div>';
        });
}

function displayDirectorResults(results) {
    if (results.length === 0) {
        directorDropdown.innerHTML = '<div class="no-results">No directors found</div>';
        directorDropdown.style.display = 'block';
        return;
    }

    directorDropdown.innerHTML = results.map(director => `
        <div class="dropdown-item text-white" data-id="${director.id}" data-name="${director.name}">
            ${director.name}
        </div>
    `).join('');
    
    directorDropdown.style.display = 'block';

    directorDropdown.querySelectorAll('.dropdown-item').forEach(item => {
        item.addEventListener('click', () => {
            setDirector(parseInt(item.dataset.id), item.dataset.name);
        });
    });
}

function setDirector(id, name) {
    selectedDirectorId = id;
    directorIdInput.value = id;
    
    selectedDirectorContainer.innerHTML = `
        <div class="director-tag text-white">
            ${name}
            <button type="button" onclick="clearDirector()">&times;</button>
        </div>
    `;
    
    directorSearchInput.value = '';
    directorDropdown.style.display = 'none';
}

function clearDirector() {
    selectedDirectorId = null;
    directorIdInput.value = '';
    selectedDirectorContainer.innerHTML = '';
}

directorSearchInput.addEventListener('input', (e) => {
    clearTimeout(directorDebounceTimer);
    const query = e.target.value.trim();

    if (query.length < 2) {
        directorDropdown.style.display = 'none';
        return;
    }

    directorDebounceTimer = setTimeout(() => {
        searchDirectors(query);
    }, 300);
});

document.addEventListener('click', (e) => {
    if (!e.target.closest('.director-selector')) {
        directorDropdown.style.display = 'none';
    }
});

</script>
@endpush