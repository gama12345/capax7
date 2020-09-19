
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">        

</head>
    <body style="margin-top: 100px;">
    @if(Session::get('success'))
        <script>
            alert('Se ha reestablecido su contraseña correctamente');
            location.href = @json(route('main'));
        </script>
    @else
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">{{ __('Reestablecer Contraseña') }}</div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('updatePassword') }}">
                                @csrf
                                <input type="hidden" name="token" value="{{ $token }}">
                                <input type="hidden" id="oldEmail" name="oldEmail" value="{{ $oldEmail }}">

                                <div class="form-group row">
                                    <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Email') }}</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $oldEmail }}" required='' autocomplete="email" autofocus oninvalid="if (this.value == ''){this.setCustomValidity('Ingrese su email antes de continuar')} if (this.value != ''){this.setCustomValidity('El email ingresado es incorrecto')}" oninput="setCustomValidity('')">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Contraseña') }}</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required="" autocomplete="new-password" oninvalid="if (this.value == ''){this.setCustomValidity('Ingrese su nueva contraseña')} if (this.value != ''){this.setCustomValidity('La contraseña ingresada es incorrecta')}" oninput="setCustomValidity('')">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="passwordconfirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirmar contraseña') }}</label>

                                    <div class="col-md-6">
                                        <input id="passwordconfirmation" type="password" class="form-control" name="passwordconfirmation" required='' autocomplete="new-password" oninvalid="if (this.value == ''){this.setCustomValidity('Ingrese su nueva contraseña')} if (this.value != ''){this.setCustomValidity('La contraseña ingresada es incorrecta')}" oninput="setCustomValidity('')">
                                    </div>
                                </div>

                                <div class="form-group row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Reestablecer contraseña') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
    </body>
</html>