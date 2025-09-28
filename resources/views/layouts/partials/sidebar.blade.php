  <!-- ======= Sidebar ======= -->
  <aside id="sidebar" class="sidebar">

      <ul class="sidebar-nav" id="sidebar-nav">

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('admin.dashboard') }}">
                  <i class="bi bi-grid"></i>
                  <span>Dashboard</span>
              </a>
          </li><!-- End Dashboard Nav -->

          <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#components-nav" data-bs-toggle="collapse" href="#">
                  <i class="bi bi-menu-button-wide"></i><span>Cars</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                  <li>
                      <a href="{{ route('admin.car.add') }}">
                          <i class="bi bi-circle"></i><span>Add Car</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.cars') }}">
                          <i class="bi bi-circle"></i><span>Cars</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.brands') }}">
                          <i class="bi bi-circle"></i><span>Brands</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.CarModels') }}">
                          <i class="bi bi-circle"></i><span>Models</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.BodyStyles') }}">
                          <i class="bi bi-circle"></i><span>Body Styles</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.Types') }}">
                          <i class="bi bi-circle"></i><span>Types</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.TransmissionTypes') }}">
                          <i class="bi bi-circle"></i><span>Transmission Types</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.DriveTypes') }}">
                          <i class="bi bi-circle"></i><span>Drive Types</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.EngineTypes') }}">
                          <i class="bi bi-circle"></i><span>Engine Types</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.VehicleStatuses') }}">
                          <i class="bi bi-circle"></i><span>Vehicle Statuses</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.Trim') }}">
                          <i class="bi bi-circle"></i><span>Trim</span>
                      </a>
                  </li>
              </ul>
          </li>
          @role('super-admin')
              <li class="nav-item">
                  <a class="nav-link collapsed" data-bs-target="#roles-permissions-nav" data-bs-toggle="collapse"
                      href="#">
                      <i class="bi bi-menu-button-wide"></i><span>Roles & Permissions</span><i
                          class="bi bi-chevron-down ms-auto"></i>
                  </a>
                  <ul id="roles-permissions-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                      <li>
                          <a href="{{ route('admin.Roles') }}">
                              <i class="bi bi-circle"></i><span>Roles</span>
                          </a>
                      </li>
                      <li>
                          <a href="{{ route('admin.Permissions') }}">
                              <i class="bi bi-circle"></i><span>Permissions</span>
                          </a>
                      </li>
                  </ul>
              </li>
          @endrole

          <li class="nav-item">
              <a class="nav-link collapsed" data-bs-target="#banner-nav" data-bs-toggle="collapse" href="#">
                  <i class="bi bi-menu-button-wide"></i><span>Designs</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="banner-nav" class="nav-content collapse" data-bs-parent="#sidebar-nav">
                  <li>
                      <a href="{{ route('admin.Banners') }}">
                          <i class="bi bi-circle"></i><span>Banners</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.StartAds') }}">
                          <i class="bi bi-circle"></i><span>Ads</span>
                      </a>
                  </li>
              </ul>
          </li>

          <li class="nav-heading">Management</li>

          <li class="nav-item">
              <a class="nav-link collapsed" href="{{ route('admin.Users') }}">
                  <i class="bi bi-person"></i>
                  <span>Users</span>
              </a>
          </li>
          @role('super-admin')
              <li class="nav-item">
                  <a class="nav-link collapsed" href="{{ route('admin.Admins') }}">
                      <i class="bi bi-person"></i>
                      <span>Admins</span>
                  </a>
              </li><!-- End Admins Page Nav -->
          @endrole
          <li class="nav-item">
              <a class="nav-link collapsed" href="profile">
                  <i class="bi bi-person"></i>
                  <span>Profile</span>
              </a>
          </li>


      <li class="nav-item">
        <a class="nav-link {{ request()->routeIs('admin.notifications.*') ? '' : 'collapsed' }}" href="{{ route('admin.notifications.index') }}">
            <i class="bi bi-bell"></i>
            <span>Notifications</span>
            @if($unreadCount = \App\Models\Notification::unread()->count())
            <span class="badge bg-danger rounded-pill ms-auto">{{ $unreadCount }}</span>
            @endif
        </a>
      </li>

      <li class="nav-heading">Features</li>
        @if(config('app.app') !== 'kalksat')
          <li class="nav-item">
            <li class="nav-item">
                <a class="nav-link collapsed" href="{{ route('admin.financing-requests.index') }}">
                <i class="bi bi-person"></i>
                <span>Financing Requests</span>
                </a>
            </li>
            <a class="nav-link collapsed" href="{{ route('admin.Quizzes') }}">
                <i class="bi bi-question"></i>
                <span>Quizzes</span>
            </a>
          </li><!-- End Quizzes Page Nav -->
        @endif
          <li class="nav-item">
            <a class="nav-link collapsed" href="{{ route('admin.dashboard') }}">
                <i class="bi bi-question-circle"></i>
                <span>F.A.Q</span>
            </a>
          </li><!-- End F.A.Q Page Nav -->


      </ul>

  </aside><!-- End Sidebar-->
