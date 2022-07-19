<div class="d-flex justify-content-between align-items-center p-3 my-3 border-bottom">
  <ul class="nav nav-pills">
    <li class="nav-item">
      <a class="nav-link {{ url()->current() == url('private_messages') ? 'active' : '' }}" @if(url()->current() == url('private_messages'))
        aria-current="page"
        @endif
        href="{{ url('private_messages') }}">
        <i class="bi-inbox"></i>
        Inbox
        <span class="badge text-bg-light">{{ count(Auth::user()->private_messages) }}</span>
        <span class="visually-hidden">messages in inbox</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ url()->current() == url('private_messages/sent') ? 'active' : '' }}" @if(url()->current() == url('private_messages'))
        aria-current="page"
        @endif href="{{ url('private_messages/sent') }}">
        <i class="bi-arrow-up-left"></i>
        Sent
        <span class="badge text-bg-light">{{ count(Auth::user()->private_messages_sent) }}</span>
        <span class="visually-hidden">messages in sent</span>
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link {{ url()->current() == url('private_messages/trash') ? 'active' : '' }}" @if(url()->current() == url('private_messages'))
        aria-current="page"
        @endif href="{{ url('private_messages/trash') }}">
        <i class="bi-trash"></i>
        Trash
        <span class="badge text-bg-light">{{ count(Auth::user()->private_messages_trash) }}</span>
        <span class="visually-hidden">messages in trash</span>
      </a>
    </li>
  </ul>
  <div>
    @if (url()->current() == url('private_messages/trash'))
    <a href="{{ url('private_messages/empty_trash') }}" class="btn btn-secondary"><i class="bi-trash"></i> Empty Trash</a>
    @endif
    <a href="{{ url('private_messages/compose') }}" class="btn btn-success"><i class="bi-pencil"></i> Compose</a>
  </div>
</div>
