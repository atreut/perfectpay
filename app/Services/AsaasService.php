<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AsaasService
{
    protected $client;
    protected $apiUrl;
    protected $apiKey;
    protected $userAgent;

    public function __construct()
    {
        $this->apiUrl = config('services.asaas.api_url');
        $this->apiKey = config('services.asaas.api_key');
        $this->userAgent = config('services.asaas.user_agent');

        $this->client = new Client([
            'headers' => [
                'accept' => 'application/json',
                'access_token' => $this->apiKey,
                'User-Agent' => $this->userAgent,
            ],
        ]);
    }

    public function getSaldo()
    {
        try {
            $response = $this->client->get($this->apiUrl . '/v3/financialTransactions');
            return [
                'status' => 'success',
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao recuperar saldo: ' . $e->getMessage(),
            ];
        }
    }

    public function processPayment(array $data)
    {
        try {
            $response = $this->client->post($this->apiUrl . '/v3/payments', [
                'body' => json_encode($data),
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ]);

            $responseData = json_decode($response->getBody()->getContents(), true);

            Log::info('API Response:', [
                'status_code' => $response->getStatusCode(),
                'response' => $responseData,
            ]);

            return [
                'status' => 'success',
                'data' => $responseData,
            ];
        } catch (\Exception $e) {
            Log::error('Error processing payment:', [
                'exception' => $e,
                'response_status_code' => $e->getCode(),
            ]);

            return [
                'status' => 'error',
                'message' => 'Erro ao processar pagamento: ' . $e->getMessage(),
            ];
        }
    }

    public function getPayments($status = null)
    {
        try {
            $uri = $this->apiUrl . '/v3/payments';
            if ($status) {
                $uri .= '?status=' . $status;
            }
            $response = $this->client->get($uri);
            return [
                'status' => 'success',
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao recuperar pagamentos: ' . $e->getMessage(),
            ];
        }
    }

    public function getCustomers()
    {
        try {
            $response = $this->client->get($this->apiUrl . '/v3/customers');
            return [
                'status' => 'success',
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao recuperar clientes: ' . $e->getMessage(),
            ];
        }
    }

    public function getPixQrCode($paymentId)
    {
        try {
            $response = $this->client->get($this->apiUrl . "/v3/payments/{$paymentId}/pixQrCode");
            return [
                'status' => 'success',
                'data' => json_decode($response->getBody()->getContents(), true),
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Erro ao recuperar QR Code PIX: ' . $e->getMessage(),
            ];
        }
    }

    public function deletePayment($id)
    {
        $response = $this->client->request('DELETE', 'https://sandbox.asaas.com/api/v3/payments/pay_gjuujmmrie55xuth', [
            'headers' => [
                'Authorization' => 'Bearer aact_YTU5YTE0M2M2N2I4MTliNzk0YTI5N2U5MzdjNWZmNDQ6OjAwMDAwMDAwMDAwMDAwODYzMTc6OiRhYWNoX2U5N2Q3ZjNkLTRhY2YtNDk3NC1hYzk3LWIzM2QzMGY1ZjAzMA==',
                'User-Agent' => 'PerfectPay',
                'Accept' => 'application/json',
            ],
        ]);
        
        echo $response->getBody();
    }
}
