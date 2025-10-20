{{-- resources/views/passenger/partials/operator-modal.blade.php --}}
<div class="modal fade" id="operatorModal" tabindex="-1" aria-labelledby="operatorModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="operatorSelectForm">
        <div class="modal-header">
          <h5 class="modal-title" id="operatorModalLabel">Choose an Operator</h5>
        </div>
        <div class="modal-body">
          <select class="form-control" id="operator_id" name="operator_id" required>
            <option value="">Select Operator</option>
            @foreach($operators as $operator)
              <option value="{{ $operator->id }}">{{ $operator->name }}</option>
            @endforeach
          </select>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Continue</button>
        </div>
      </form>
    </div>
  </div>
</div>