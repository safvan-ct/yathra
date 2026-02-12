<input type="hidden" name="id" value="{{ $data->id ?? 0 }}">

<x-admin.input name="name" label="Document Group Name" value="{{ $data->name ?? '' }}" />

<div class="form-floating mb-2">
    <textarea class="form-control" name="notes" id="notes" placeholder="Notes" rows="10" style="min-height: 130px">{{ $data?->notes ?? '' }}</textarea>
    <label for="notes">If have any <b>Notes</b></label>
</div>
