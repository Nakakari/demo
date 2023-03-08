@extends('layouts.mainlogin')

@section('isi')
    <div class="section-authentication-signin d-flex align-items-center justify-content-center my-5 my-lg-0">
        <div class="container-fluid">
            <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3">
                <div class="col mx-auto">
                    <div class="mb-4 text-center">
                        {{-- <img src="{{ asset('template') }}/assets/images/logo-img.png" width="180" alt="" /> --}}
                        {{-- <h3 class="">{{ __('KonyaKonya') }}</h3> --}}
                        <h3 class="">{{ title() }}</h3>

                    </div>
                    <div class="card">
                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    <b>Opps!</b> {{ session('error') }}
                                </div>
                            @endif
                            <form method="post" action="{{ route('login') }}">
                                @csrf
                                <div class="border p-4 rounded">
                                    <div class="text-center">
                                        <h3 class="">{{ __('Login') }}</h3>
                                        @if (Route::has('register'))
                                            {{-- <a href="{{ route('register') }}"
                                                class="ml-4 text-sm text-gray-700 dark:text-gray-500 underline">Register</a> --}}
                                            <p>Don't have an account yet? <a href="{{ route('register') }}">Sign up
                                                    here</a>
                                            </p>
                                        @endif

                                    </div>
                                    <div class="form-body">
                                        <form class="row g-3">
                                            <div class="col-12">
                                                <label for="inputEmailAddress" class="form-label">Email Address</label>
                                                <input id="email" type="email"
                                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                                @error('email')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                @enderror
                                            </div>
                                            <div class="col-12">
                                                <label for="inputChoosePassword" class="form-label">Enter
                                                    Password</label>
                                                <div class="input-group">
                                                    <input id="password" type="password"
                                                        class="form-control @error('password') is-invalid @enderror border-end-0"
                                                        id="inputChoosePassword" name="password" required
                                                        autocomplete="current-password"> <a href="javascript:;"
                                                        class="input-group-text bg-transparent"></a>

                                                    @error('password')
                                                        <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-switch">
                                                    {{-- <input class="form-check-input" type="checkbox" name="remember"
                                                        id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                    <label class="form-check-label" for="flexSwitchCheckChecked">Remember
                                                        Me</label> --}}
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="d-grid">
                                                    <button type="submit" class="btn btn-primary"><i
                                                            class="bx bxs-lock-open"></i>Sign in</button>
                                                    {{-- @if (Route::has('password.request'))
                                                        <a class="btn btn-link" href="{{ route('password.request') }}">
                                                            {{ __('Forgot Your Password?') }}
                                                        </a>
                                                    @endif --}}
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>


@stop
@section('js')
    <script>
        $(document).ready(function() {
            $("#show_hide_password a").on('click', function(event) {
                event.preventDefault();
                if ($('#show_hide_password input').attr("type") == "text") {
                    $('#show_hide_password input').attr('type', 'password');
                    $('#show_hide_password i').addClass("bx-hide");
                    $('#show_hide_password i').removeClass("bx-show");
                } else if ($('#show_hide_password input').attr("type") == "password") {
                    $('#show_hide_password input').attr('type', 'text');
                    $('#show_hide_password i').removeClass("bx-hide");
                    $('#show_hide_password i').addClass("bx-show");
                }
            });
        });
    </script>
@stop
