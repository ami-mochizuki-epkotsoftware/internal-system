<nav id="sidebarMenu" class="col-md-2 d-md-block bg-light sidebar collapse">
  <div class="sidebar-sticky pt-3">
    <ul class="nav flex-column">
      <li class="nav-item">
        <a class="nav-link{{ request()->route()->named('index') ? ' active' : '' }}" href="{{ route('index') }}"><span data-feather="home"></span> ホーム</a>
      </li>
      <li class="nav-item">
        <a class="nav-link{{ request()->route()->named('works.*') ? ' active' : '' }}" href="{{ route('works.index') }}"><span data-feather="file-text"></span> 勤怠登録</a>
      </li>
    </ul>
  </div>
</nav>