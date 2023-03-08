<div class="user-box dropdown">
    <a class="d-flex align-items-center nav-link dropdown-toggle dropdown-toggle-nocaret" href="#" role="button"
        data-bs-toggle="dropdown" aria-expanded="false">
        <img src="{{ url('') }}/foto_pengguna/{{ Auth::user()->file_foto }}" class="user-img" alt="user avatar">
        <div class="user-info ps-3">

            <p class="user-name mb-0">{{ Auth::user()->name }}</p>

            <p class="designattion mb-0">
                @foreach ($jab as $j)
                    @if (Auth::user()->peran == $j->id_jabatan)
                        {{ $j->nama_jabatan }}
                    @endif
                @endforeach
            </p>

        </div>
    </a>
    <ul class="dropdown-menu dropdown-menu-end">
        <li><a class="dropdown-item" href="javascript:;"><i class="bx bx-user"></i><span>Profile</span></a>
        </li>
        <li><a class="dropdown-item" href="javascript:;"><i class='bx bx-home-circle'></i><span>Dashboard</span></a>
        </li>
        <li>
            <div class="dropdown-divider mb-0"></div>
        </li>
        <li><a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                <span>Logout</span></a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>
        </li>
    </ul>
</div>
