<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
      <div class="sidebar-brand-icon" style="width: 50px; height: 50px; display: flex;align-items: center; justify-content: center; 
        background-color: white; border-radius: 50%;
        box-shadow: 0 0 10px #ffd700, 0 0 20px #ffd700, 0 0 30px #ffd700;">
        <img src="https://pbs.twimg.com/profile_images/1625786717935640577/QUQt8syP_400x400.png" alt="Logo" style="width: 40px; height: 40px; border-radius: 50%;">
      </div>
      <div class="sidebar-brand-text mx-3" style="font-size: 10px; font-weight: bold; color: #fff;
        text-shadow: 0 0 10px #ffd700, 0 0 20px #ffd700, 0 0 30px #ffd700;">
        Kanhiya<sub style="font-size: 10px; font-weight: bold; color: #fff;
          text-shadow: 0 0 10px #ffd700, 0 0 20px #ffd700, 0 0 30px #ffd700;">Mittal</sub>
      </div>
    </a>
    <!-- Divider -->
    <hr class="sidebar-divider my-0">
    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
      <a class="nav-link" href="{{ route('dashboard') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a href="{{ route('products.create') }}" class="nav-link">
      <i class="fas fa-plus-circle text-dark"></i>
      <span class="ms-2">Create Product</span>
      </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('category') }}">
      <i class="fas fa-th-large text-info"></i>
      <span>Categories</span>
      </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('paragraphupload') }}">
      <i class="fas fa-calendar-alt text-success"></i>
      <span>Upcoming Events</span>
      </a>
    </li>
    
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('upload-reel') }}">
      <i class="fas fa-film text-danger"></i>
      <span> Reels</span>
      </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('audio/upload') }}">
      <i class="fas fa-music text-warning"></i>
      <span>Audios</span>
      </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('videolink') }}">
        <i class="fas fa-video text-secondary"></i> <!-- Video Icon -->
        <span>Video Link</span>
      </a>
    </li>
    <hr class="sidebar-divider">
    <li class="nav-item">
      <a class="nav-link" href="{{ route('userlist') }}">
      <i class="fas fa-fw fa-users"></i>
      <span>User List</span>
      </a>
    </li>
    <!-- Nav Item - Tables -->
    <li class="nav-item">
      <a class="nav-link" href="{{route('userlist')}}"></a>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">
    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
      <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
  </ul>
  <!-- End of Sidebar -->