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
                  <i class="bi bi-menu-button-wide"></i><span>Products</span><i class="bi bi-chevron-down ms-auto"></i>
              </a>
              <ul id="components-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                  <li>
                      <a href="{{ route('admin.product.add') }}">
                          <i class="bi bi-circle"></i><span>Add Product</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.products') }}">
                          <i class="bi bi-circle"></i><span>Products</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('admin.Categories') }}">
                          <i class="bi bi-circle"></i><span>Categories</span>
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
      </ul>

  </aside><!-- End Sidebar-->
