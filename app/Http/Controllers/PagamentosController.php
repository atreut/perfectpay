<?php

namespace App\Http\Controllers;

use App\Services\AsaasService;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\ProcessPaymentRequest;
use App\Http\Resources\PaymentResource;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class PagamentosController extends Controller
{
    protected $asaasService;

    public function __construct(AsaasService $asaasService)
    {
        $this->asaasService = $asaasService;
    }

    public function index()
    {
        try {
            $responseSaldo = $this->asaasService->getSaldo();

            $responsePaymentsRecebidos = $this->asaasService->getPayments('RECEIVED');
            $responsePaymentsConfirmados = $this->asaasService->getPayments('CONFIRMED');
            $responsePaymentsPendentes = $this->asaasService->getPayments('PENDING');
            $responsePaymentsVencidas = $this->asaasService->getPayments('OVERDUE');

            $this->checkForErrors([
                $responsePaymentsRecebidos,
                $responsePaymentsConfirmados,
                $responsePaymentsPendentes,
                $responsePaymentsVencidas
            ]);

            $paymentsRecebidos = $responsePaymentsRecebidos['data']['data'] ?? [];
            $paymentsConfirmados = $responsePaymentsConfirmados['data']['data'] ?? [];
            $paymentsPendentes = $responsePaymentsPendentes['data']['data'] ?? [];
            $paymentsVencidas = $responsePaymentsVencidas['data']['data'] ?? [];

            $saldo = $responseSaldo['data']['data'] ?? 0;
            $saldoTotal = array_sum(array_column($saldo, 'value') ?: []);

            $saldoRecebidos = array_sum(array_column($paymentsRecebidos, 'value') ?: []);
            $saldoConfirmadas = array_sum(array_column($paymentsConfirmados, 'value') ?: []);
            $saldoPendentes = array_sum(array_column($paymentsPendentes, 'value') ?: []);
            $saldoVencidas = array_sum(array_column($paymentsVencidas, 'value') ?: []);

            return view('index', [
                'saldo' => $saldoTotal,
                'saldoRecebidos' => $saldoRecebidos,
                'saldoConfirmadas' => $saldoConfirmadas,
                'saldoPendentes' => $saldoPendentes,
                'saldoVencidas' => $saldoVencidas
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar os pagamentos: ' . $e->getMessage());
        }
    }

    public function listar()
    {
        try {
            $responsePagamentos = $this->asaasService->getPayments();
            $responseClientes = $this->asaasService->getCustomers();

            $this->checkForErrors([$responsePagamentos, $responseClientes]);

            $pagamentos = $responsePagamentos['data']['data'] ?? [];
            $clientes = $responseClientes['data']['data'] ?? [];

            $clientesAssoc = [];
            foreach ($clientes as $cliente) {
                $clientesAssoc[$cliente['id']] = $cliente;
            }

            foreach ($pagamentos as &$pagamento) {
                $pagamento['value'] = isset($pagamento['value']) && is_numeric($pagamento['value']) ? (float)$pagamento['value'] : 0;
                $clienteId = $pagamento['customer'] ?? null;
                $pagamento['customerData'] = $clientesAssoc[$clienteId] ?? null;
            }

            return view('pagamentos.index', [
                'pagamentos' => PaymentResource::collection($pagamentos),
                'clientes' => $clientes,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao listar pagamentos e clientes: ' . $e->getMessage());
        }
    }

    public function processamento(ProcessPaymentRequest $request)
    {
        try {
            $validatedData = $request->validated();

            $paymentData = [
                'customer' => $validatedData['customer'],
                'billingType' => $validatedData['billingType'],
                'value' => $validatedData['value'],
                'dueDate' => $validatedData['dueDate'],
                'description' => $validatedData['description'],
            ];

            $response = $this->asaasService->processPayment($paymentData);

            if ($response['status'] === 'error') {
                return redirect()->back()->with('error', $response['message']);
            }

            Session::put('paymentData', $response['data']);

            if ($validatedData['billingType'] === 'PIX') {
                $paymentId = $response['data']['id'];                
                $qrCodeResponse = $this->asaasService->getPixQrCode($paymentId);    
                $qrCodeImage = $this->generateQrCodeImage($qrCodeResponse['data']['encodedImage']);
                Session::put('pixQrCode', [
                    'encodedImage' => $qrCodeImage,
                    'payload' => $qrCodeResponse['data']['payload']
                ]);
            }

            return redirect()->route('pagamentos.finalizar');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao processar pagamento: ' . $e->getMessage());
        }
    }

    public function finalizar()
    {
        $paymentData = Session::get('paymentData');
        $pixQrCode = Session::get('pixQrCode');

        if (!$paymentData) {
            return redirect()->route('pagamentos.listar')
                            ->with('error', 'Dados de pagamento não encontrados.');
        }

        return view('pagamentos.finalizar', [
            'paymentData' => $paymentData,
            'pixQrCode' => $pixQrCode
        ]);
    }

    private function generateQrCodeImage($base64QrCode)
    {
        $imageData = base64_decode($base64QrCode);
        $image = Image::make($imageData)->encode('data-url'); 
        return $image;
    }

    public function destroy($id)
    {
        try {
            $response = $this->asaasService->deletePayment($id);
            
            if ($response) {
                return response()->json([
                    'deleted' => true,
                    'id' => $id,
                    'message' => 'Pagamento excluído com sucesso.'
                ]);
            } else {
                return response()->json([
                    'deleted' => false,
                    'message' => 'Falha ao excluir o pagamento.'
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao excluir pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    private function checkForErrors(array $responses)
    {
        foreach ($responses as $response) {
            if ($response['status'] === 'error') {
                throw new \Exception($response['message']);
            }
        }
    }
    
}
