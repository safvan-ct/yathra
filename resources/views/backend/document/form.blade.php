<input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

@if ($docGroups->isNotEmpty())
    <div class="form-floating mb-2">
        <select class="form-select" name="document_group_id" id="document_group_id">
            <option value="">Select Document Group</option>
            @foreach ($docGroups as $item)
                <option value="{{ $item->id }}" {{ $data?->document_group_id == $item->id ? 'selected' : '' }}>
                    {{ $item->name }}</option>
            @endforeach
        </select>
        <label for="document_group_id">Document Group</label>
    </div>
@endif

<x-admin.input name="name" label="Document Name" value="{{ $data->name ?? '' }}" />

<div class="form-floating mb-2">
    <textarea class="form-control" name="notes" id="notes" placeholder="Notes" style="min-height: 100px">{{ $data?->notes ?? '' }}</textarea>
    <label for="notes">Notes</label>
</div>
