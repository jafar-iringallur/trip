<ul class="sidebar-nav" id="sidebar-nav">
  @php
    $route_name = request()->route()->getName();
  @endphp
    {{-- <li class="nav-item">
      <a class="nav-link {{ $route_name == "dashboard" ? '' : 'collapsed' }}" href="{{route('dashboard')}}">
        <i class="bi bi-grid"></i>
        <span>Dashboard</span>
      </a>
    </li><!-- End Dashboard Nav --> --}}

   



   
    <li class="nav-item">
      <a class="nav-link {{ $route_name == "places.index" ? '' : 'collapsed' }}" href="{{route('places.index')}}">
        <i class="bi bi-people"></i>
        <span>Places</span>
      </a>
    </li>
    
    <li class="nav-item">
      <a class="nav-link collapsed" href="{{route('logout')}}">
        <i class="bi bi-box-arrow-in-right"></i>
        <span>Logout</span>
      </a>
    </li>
   


   
  
 
   

   

  </ul>