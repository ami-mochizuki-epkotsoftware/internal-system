@if (session('messages.' . $slot))
<div class="alert alert-{{ $slot }} alert-dismissible fade show" role="alert">
  {{ session('messages.' . $slot) }}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
