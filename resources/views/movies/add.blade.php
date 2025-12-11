@extends('layouts.app')

@section('content')

<!-- INPUTS:
MOVIE TITLE
DIRECTOR ID
GENRES
ACTORS
DESCRIPTION
YEAR
POSTER 

-->

<div>
    <form action="{{ route('movies.store') }}">
        <div>
            <label for="name">Movie title</label>

            <input type="text" id="name" name="name" required 
                autocomplete="name"
                class="w-full pl-10 pr-4 py-3 bg-gray-700/50 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all @error('email') border-red-500 focus:ring-red-500 @enderror"
                placeholder="Enter movie title"
            >
        </div>


        <div></div>
        <div></div>
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
                <div id="dropdown" class="dropdown" style="display: none;"></div>
            </div>
            
            <!-- Hidden inputs that will be submitted with the form -->
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
        <div></div>
        <div></div>

        <button type="submit" class="btn btn-primary">Save Movie</button>
    </form>
</div>
@endsection


@push('scripts')
<script>
    const selectedActors = new Set();
    const searchInput = document.getElementById('actorSearch');
    const dropdown = document.getElementById('dropdown');
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

    function displayResults(results) {
        const availableResults = results.filter(actor => !selectedActors.has(actor.id));
        
        if (availableResults.length === 0) {
            dropdown.innerHTML = '<div class="no-results">No actors found</div>';
            dropdown.style.display = 'block';
            return;
        }
        console.log(availableResults);
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

</script>
@endpush