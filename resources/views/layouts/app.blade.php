<!DOCTYPE html>
<html lang="en">
@include('layouts.partials.head')

<body>
  @include('layouts.partials.header')
  @include('layouts.partials.sidebar')

  <main id="main" class="main">
    @yield('content')
  </main>

  @include('layouts.partials.footer')
  @include('layouts.partials.foot')
</body>
</html>
