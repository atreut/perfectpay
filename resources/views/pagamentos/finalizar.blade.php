@extends('layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Painel / Pagamentos /</span> Finalização</h4>
                <div class="card">
                    <div class="card-body">
                        <div class="card">
                            @if(isset($paymentData['billingType']))
                                @if($paymentData['billingType'] === 'BOLETO' && isset($paymentData['bankSlipUrl']))
                                    <div class="callout callout-info mb-4">
                                        <h4>Cobrança Gerada</h4>
                                        <p>Seu boleto está disponível no link abaixo:</p>
                                    </div>
                                    
                                    <p class="text-center">
                                        <a href="{{ $paymentData['bankSlipUrl'] }}" class="btn btn-danger" target="_blank"><i class="tf-icons bx bx-printer"></i> Imprimir Boleto</a>
                                    </p>
                                @elseif($paymentData['billingType'] === 'PIX')
                                    <div class="callout callout-info mb-4">
                                        <h4>Cobrança Gerada</h4>
                                        <p>Seu pagamento via Pix foi gerado com sucesso.</p>
                                    </div>
                                    <div class="row mb-4">
                                        @if(isset($pixQrCode['encodedImage']))
                                            <div class="col-lg-12 text-center">
                                                <h4>QR Code para PIX</h4>
                                                <img src="{{ $pixQrCode['encodedImage'] }}" width="180" alt="QR Code" class="img-fluid">
                                                <p style="text-align: center; font-size: 1.2rem;">Acesse seu aplicativo de pagamentos e faça a leitura do QR Code ao lado para efetuar o pagamento de forma rápida e segura.</p>
                                            </div>
                                        @else
                                            <p class="text-center">QR Code não disponível.</p>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="text-center">Copia e Cola:</h4>
                                        <textarea class="text-center form-control mb-4" readonly>{{ $pixQrCode['payload'] ?? '' }}</textarea>
                                    </div>
                                @else
                                    <div class="alert alert-danger" role="alert">Forma de pagamento não reconhecida.</div>
                                @endif
                            @else
                                <div class="alert alert-danger" role="alert">Informações do pagamento não disponíveis.</div>
                            @endif

                            <div class="text-center mt-4">
                                <a href="{{ route('pagamentos.listar') }}" class="btn btn-primary"><i class="tf-icons bx bx-chevron-left-circle"></i> Voltar para cobranças</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer"></div>
                </div>
            </div>
        </div>
    </div>
@endsection
