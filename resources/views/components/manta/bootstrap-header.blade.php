 <header>
     <nav class="navbar navbar-expand-lg navbar-light bg-light">
         <div class="container">
             <a class="navbar-brand" href="{{ route('cms.dashboard') }}">{{ env('APP_NAME') }}</a>
             <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                 aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                 <span class="navbar-toggler-icon"></span>
             </button>
             <div class="navbar-collapse collapse" id="navbarNavDropdown">
                 <ul class="navbar-nav mb-lg-0 mb-2 me-auto">
                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                             data-bs-toggle="dropdown" aria-expanded="false">
                             Modules
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                             <li><a class="dropdown-item" href="{{ route('news.list') }}">Case histories</a></li>
                             <li><a class="dropdown-item" href="{{ route('page.list') }}">Tekstpagina's</a></li>
                         </ul>
                     </li>
                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                             data-bs-toggle="dropdown" aria-expanded="false">
                             Tools
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                             <li><a class="dropdown-item" href="{{ route('user.list') }}">Klanten</a></li>
                             <li><a class="dropdown-item" href="{{ route('upload.list') }}">Uploads</a></li>
                             <li>
                                 <hr class="dropdown-divider">
                             </li>
                             <li><a class="dropdown-item" href="{{ route('staff.list') }}">Gebruikers</a></li>
                         </ul>
                     </li>
                 </ul>
                 <ul class="navbar-nav">
                     <li class="nav-item">
                         <a class="nav-link active" aria-current="page" href="{{ env('APP_URL') }}" target="_blank"><i
                                 class="fa-light fa-globe-pointer"></i> Website</a>
                     </li>

                     <li class="nav-item dropdown">
                         <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                             data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-user"></i>
                             {{ auth('staff')->user()->name }}
                         </a>
                         <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                             <li><a class="dropdown-item" href="{{ route('logout') }}"><i
                                         class="fa-solid fa-right-from-bracket"></i> Logout</a></li>
                         </ul>
                     </li>
                 </ul>
             </div>
         </div>
     </nav>
 </header>
