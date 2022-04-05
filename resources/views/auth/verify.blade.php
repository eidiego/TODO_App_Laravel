@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Verifique sua caixa de e-mail') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('A fresh verification link has been sent to your email address.') }}
                        </div>
                    @endif

                    {{ __('Antes de prosseguir, por favor valide seu e-mail pelo link enviado para sua caixa de entrada.') }}
                    {{ __('Caso não tenha recebido o link em seu e-mail, clique no link abaixo para solicitar novo envio.') }},
                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('Clique aqui para solicitar um novo e-mail de validação!') }}</button>.
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
