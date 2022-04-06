Site da aplicacao

@auth

    <h1>Usuario autenticadoM</h1>
   <p> ID {{ Auth::user()->id }} </p>
   <p> Nome {{ Auth::user()->name }} </p>
   <p>  E-mail {{ Auth::user()->email }} </p>

@endauth

@guest

    <p>Olá visitante, você precisa se logar para ter mais opções!</p>

@endguest
