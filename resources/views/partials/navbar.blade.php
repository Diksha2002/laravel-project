<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('orders.index') }}">Shop Panel</a>
    <ul class="navbar-nav ms-auto">
      <li class="nav-item"><a href="{{ route('orders.index') }}" class="nav-link">Orders</a></li>
      <li class="nav-item"><a href="{{ route('logout') }}" class="nav-link"
         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
    </ul>
  </div>
  <form id="logout-form" action="{{ route('logout') }}" method="POST">@csrf</form>
</nav>
