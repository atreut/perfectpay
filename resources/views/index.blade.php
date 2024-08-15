@extends('layout')

@section('content')

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row mb-4">
        <div class="col-lg-12">
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="d-flex align-items-end row">
                    <div class="col-sm-7">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Bem-Vindo Taylan Atreu! 🎉</h5>
                            <p class="mb-4">Esse é o painel de gerenciamento da sua conta PerfectPay.</p>
                            <p>Saldo Atual</p>
                            <h3 class="card-title mb-2">R$ {{ number_format($saldo ?? 0, 2, ',', '.') }}</h3>
                        </div>
                    </div>
                    <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                            <img src="{{ asset('assets/img/illustrations/man-with-laptop-light.png') }}" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-4">
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/wallet-info.png') }}" alt="Saldo Recebidas" class="rounded" />
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Recebidas</span>
                    <h3 class="card-title mb-2">R$ {{ number_format($saldoRecebidos ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/cc-success.png') }}" alt="Saldo Confirmadas" class="rounded" />
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Confirmadas</span>
                    <h3 class="card-title mb-2">R$ {{ number_format($saldoConfirmadas ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/cc-primary.png') }}" alt="Saldo Aguardando Pagamento" class="rounded" />
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Aguardando Pagamento</span>
                    <h3 class="card-title mb-2">R$ {{ number_format($saldoPendentes ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card">
                <div class="card-body">
                    <div class="card-title d-flex align-items-start justify-content-between">
                        <div class="avatar flex-shrink-0">
                            <img src="{{ asset('assets/img/icons/unicons/cc-warning.png') }}" alt="Saldo Vencidas" class="rounded" />
                        </div>
                    </div>
                    <span class="fw-semibold d-block mb-1">Vencidas</span>
                    <h3 class="card-title mb-2">R$ {{ number_format($saldoVencidas ?? 0, 2, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
