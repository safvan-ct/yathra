@extends('layouts.admin')

@section('content')
    <style>
        .route-scroll {
            max-height: 500px;
            overflow-y: auto;
            padding-right: 5px;
        }

        .route-scroll::-webkit-scrollbar {
            width: 6px;
        }

        .route-scroll::-webkit-scrollbar-thumb {
            background: #bbb;
            border-radius: 4px;
        }
    </style>

    <x-admin.page-header :title="'Route Builder for ' .
        $routeDirection->routePattern->name .
        ' (' .
        $routeDirection->routePattern->code .
        ')' .
        $routeDirection->name .
        ' ' .
        $routeDirection->direction" :breadcrumb="[
        ['label' => 'Dashboard', 'link' => route('backend.dashboard')],
        ['label' => 'Route Direction', 'link' => route('route-direction.index')],
        ['label' => 'Route Builder'],
    ]" />

    <form method="POST" action="{{ route('route-direction-stop.store') }}" class="mt-3">
        @csrf
        <input type="hidden" name="route_direction_id" value="{{ $routeDirection->id }}">

        <div class="row g-4">

            {{-- LEFT: STOP SEARCH --}}
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body">

                        <label class="fw-bold">Import From Another Route</label>

                        <select id="import-pattern" class="form-select">
                            <option value="">Select Route</option>

                            @foreach ($allDirections as $item)
                                <option value="{{ $item->id }}">
                                    {{ $item->routePattern->name . ' (' . $item->routePattern->code . ')' }}
                                    {{ $item->name }} {{ $item->direction }}
                                </option>
                            @endforeach

                        </select>

                        <button class="btn btn-warning mt-3 w-100" id="import-btn" type="button">
                            Import Stops
                        </button>

                    </div>
                </div>

                <div class="card">
                    <div class="card-header fw-bold">Search Stops</div>
                    <div class="card-body">

                        <select id="stop-search" class="form-select"></select>

                        <button type="button" class="btn btn-primary w-100 mt-2" id="add-stop">
                            Add to Route
                        </button>

                        <small class="text-muted d-block mt-2">
                            Type at least 2 letters to search.
                        </small>

                    </div>
                </div>
            </div>

            {{-- RIGHT: ROUTE BUILDER --}}
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header fw-bold d-flex justify-content-between">
                        <span>Route Timeline</span>

                        <div>
                            <button type="button" class="btn btn-outline-info btn-sm me-2" id="reverse-route">
                                Reverse Order
                            </button>

                            <button type="button" class="btn btn-outline-secondary btn-sm" id="clear-draft">
                                Clear Draft
                            </button>
                        </div>
                    </div>

                    <div class="card-body">

                        <!-- SCROLLABLE ROUTE LIST -->
                        <div class="route-scroll">

                            <ul id="route-list" class="list-group"></ul>

                        </div>

                        <div id="hidden-inputs"></div>

                        <div class="d-flex justify-content-end mt-3">
                            <button class="btn btn-success">
                                Save Route Stops
                            </button>
                        </div>

                    </div>

                </div>
            </div>

        </div>
    </form>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    <script>
        // Hydrate initial route stops from DB
        window.initialRouteStops = @json($stops);

        /* =========================================================
           STATE
        ========================================================= */

        let routeStops = []; // [{id, name, offset}]

        const STORAGE_KEY = "route_builder_draft_{{ $routeDirection->id }}";

        /* =========================================================
           CHOICES SEARCH
        ========================================================= */

        const stopSearch = new Choices('#stop-search', {
            searchEnabled: true,
            searchChoices: false,
            placeholderValue: 'Search stop...',
            shouldSort: false,
            removeItemButton: true
        });

        document.querySelector('#stop-search').addEventListener('search', async e => {

            const q = e.detail.value;
            if (q.length < 2) return;

            const res = await fetch(`/stops?q=${encodeURIComponent(q)}`);
            const data = await res.json();

            stopSearch.clearChoices();

            stopSearch.setChoices(
                data.map(s => ({
                    value: s.id,
                    label: `${s.name} (${s.code})`
                })),
                'value',
                'label',
                true
            );
        });

        /* =========================================================
           ADD STOP
        ========================================================= */

        document.getElementById('add-stop').onclick = () => {

            const val = stopSearch.getValue();
            if (!val || !val.value) {
                alert("Select a stop first.");
                return;
            }

            const id = val.value;
            const label = val.label;

            if (routeStops.some(s => s.id == id)) {
                alert("This stop is already in the route.");
                return;
            }

            routeStops.push({
                id,
                name: label,
                offset: 0
            });

            stopSearch.clearStore();
            renderRoute();
        };

        /* =========================================================
           RENDER ROUTE LIST
        ========================================================= */

        function renderRoute() {
            const list = document.getElementById('route-list');
            const hidden = document.getElementById('hidden-inputs');

            list.innerHTML = '';
            hidden.innerHTML = '';

            routeStops.forEach((stop, i) => {

                // Visible row
                const li = document.createElement('li');
                li.className = "list-group-item d-flex align-items-center";
                li.dataset.id = stop.id;

                let offsetValue = i == 0 ? 0 : (stop.offset || 2);

                li.innerHTML = `
            <span class="me-3 fw-bold">${i+1}</span>

            <div class="flex-grow-1">
                ${stop.name}
            </div>

            <input type="number"
                   class="form-control w-25 me-2 offset-input"
                   min="0"
                   value="${offsetValue}"
                   title="Offset minutes">

            <button class="btn btn-outline-danger remove-btn">✕</button>
        `;

                list.appendChild(li);

                // Hidden inputs for POST
                hidden.insertAdjacentHTML('beforeend', `
            <input type="hidden" name="stops[${i}][stop_id]" value="${stop.id}">
            <input type="hidden" name="stops[${i}][stop_order]" value="${i+1}">
            <input type="hidden" name="stops[${i}][offset]" value="${stop.offset}">
        `);
            });

            attachRowEvents();
            saveDraft();
        }

        /* =========================================================
           EVENTS FOR EACH ROW
        ========================================================= */

        function attachRowEvents() {

            // Remove stop
            document.querySelectorAll('.remove-btn').forEach(btn => {
                btn.onclick = function() {
                    if (!confirm("Remove this stop from route?")) return;

                    const id = this.closest('li').dataset.id;
                    routeStops = routeStops.filter(s => s.id != id);
                    renderRoute();
                };
            });

            // Offset edit
            document.querySelectorAll('.offset-input').forEach((input, idx) => {
                input.onchange = function() {
                    routeStops[idx].offset = parseInt(this.value || 0);
                    renderRoute(); // refresh hidden inputs
                };
            });
        }

        /* =========================================================
           DRAG & DROP
        ========================================================= */

        new Sortable(document.getElementById('route-list'), {
            animation: 150,
            onEnd: function() {
                const newOrder = [];
                document.querySelectorAll('#route-list li').forEach(li => {
                    const id = li.dataset.id;
                    newOrder.push(routeStops.find(s => s.id == id));
                });
                routeStops = newOrder;
                renderRoute();
            }
        });

        /* =========================================================
           AUTOSAVE DRAFT
        ========================================================= */

        function saveDraft() {
            localStorage.setItem(STORAGE_KEY, JSON.stringify(routeStops));
        }

        function loadDraft() {
            const draft = localStorage.getItem(STORAGE_KEY);
            if (draft) {
                routeStops = JSON.parse(draft);
                renderRoute();
            }
        }

        document.getElementById('clear-draft').onclick = () => {
            if (!confirm("Clear saved draft?")) return;
            document.getElementById('import-pattern').value = '';
            localStorage.removeItem(STORAGE_KEY);
            routeStops = window.initialRouteStops.length > 0 ? window.initialRouteStops : [];
            renderRoute();
        };

        /* =========================================================
           INIT
        ========================================================= */

        window.onload = () => {

            const draft = localStorage.getItem(STORAGE_KEY);

            if (draft && draft !== '[]') {
                // Load autosaved draft first
                routeStops = JSON.parse(draft);
            } else if (window.initialRouteStops.length > 0) {
                // Otherwise load DB stops
                routeStops = window.initialRouteStops;
            }
            //routeStops = window.initialRouteStops;

            renderRoute();
        };

        /* =========================================================
           IMPORT ROUTE
        ========================================================= */
        document.getElementById('import-btn').onclick = async function() {

            const patternId = document.getElementById('import-pattern').value;

            if (!patternId) {
                alert("Select a route pattern to import");
                return;
            }

            if (!confirm("Import stops from this route? Current builder will be replaced.")) {
                return;
            }

            let url = "{{ route('route-direction-stops.get', ':id') }}";
            url = url.replace(':id', patternId);

            const res = await fetch(url);
            const data = await res.json();

            routeStops = data.map(stop => ({
                id: stop.id,
                name: stop.name,
                offset: stop.offset
            }));

            renderRoute();
        };

        /* =========================================================
            REVERSE ROUTE
        ========================================================= */
        document.getElementById('reverse-route').onclick = function() {

            if (routeStops.length < 2) {
                alert("Need at least 2 stops to reverse.");
                return;
            }

            if (!confirm("Reverse stop order?")) return;

            // Reverse array
            routeStops.reverse();

            // Reset first stop offset to 0 (important)
            routeStops.forEach((stop, index) => {
                if (index === 0) {
                    stop.offset = 0;
                }
            });

            renderRoute();
        };
    </script>
@endpush
