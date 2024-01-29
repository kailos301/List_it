@if ($language->direction == 1)
  @section('style')
    <style>
      form:not(.modal-form.create) input, 
      form:not(.modal-form.create) textarea, 
      form:not(.modal-form.create) select {
        direction: rtl;
      }

      form:not(.modal-form.create) .note-editor.note-frame .note-editing-area .note-editable {
        direction: rtl;
        text-align: right;
      }
    </style>
  @endsection
@endif
