@extends('layout')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="row">
            <div class="col-lg-12">
                <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Painel /</span> Pagamentos</h4>
                <div class="card">
                    <div class="card-header">
                        <h2>Pagamentos</h2>
                    </div>
                    <div class="card-body">
                        <div class="card">
                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif
                            <hr />

                            <div class="row">
                                <div class="col-lg-8"></div>
                                <div class="col-lg-4" style="text-align: right;">
                                    <button
                                        type="button"
                                        class="btn btn-success"
                                        data-bs-toggle="modal"
                                        data-bs-target="#addModal"
                                    >
                                        <i class='tf-icons bx bx-plus-circle'></i> Nova Cobrança
                                    </button>
                                </div>
                            </div>

                            <hr />
                            <div class="table-responsive text-nowrap">
                                @if (isset($pagamentos) && count($pagamentos) > 0)
                                    <table class="table table-borderless table-hover table-bordered">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Cliente</th>
                                                <th>Descrição</th>
                                                <th>Valor</th>
                                                <th>Data Criação</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($pagamentos as $index => $pagamento)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div>{{ $pagamento['customerData']['name'] ?? 'N/A' }}</div>
                                                        <div><small>{{ $pagamento['customerData']['email'] ?? 'N/A' }}</small></div>
                                                    </td>
                                                    <td>
                                                        <div><strong>{{ $pagamento['description'] }}</strong></div>
                                                        <div><small>{{ $pagamento['billingType'] }}</small></div>
                                                    </td>
                                                    <td>
                                                        @php
                                                            $value = isset($pagamento['value']) && is_numeric($pagamento['value']) ? (float)$pagamento['value'] : 0;
                                                        @endphp
                                                        R$ {{ number_format($value, 2, ',', '.') }}
                                                    </td>
                                                    <td>
                                                        @php
                                                            $dateCreated = isset($pagamento['dateCreated']) ? $pagamento['dateCreated'] : '';
                                                        @endphp
                                                        {{ \Carbon\Carbon::parse($dateCreated)->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        <span class="badge {{ strtolower('status-' . $pagamento['status']) }}">
                                                            {{ $pagamento['status'] }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @else
                                    <p class="text-center">Não há pagamentos para exibir.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <!-- Modal -->
                        <div class="modal fade" id="addModal" data-bs-backdrop="static" tabindex="-1">
                            <div class="modal-dialog">
                                <form id="paymentForm" class="modal-content" method="POST" action="{{ route('pagamentos.processamento') }}">
                                    @csrf
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="addModalLabel">Adicionar Cobrança</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="errorMessages"></div>
                                        
                                        <div class="mb-3">
                                            <label for="customer" class="form-label">Cliente <span class="text-danger">*</span></label>
                                            <select
                                                id="customer"
                                                name="customer"
                                                class="form-select"
                                                required
                                            >
                                                <option value="">Selecione um cliente</option>
                                                @foreach ($clientes as $cliente)
                                                    <option value="{{ $cliente['id'] }}">{{ $cliente['name'] }}</option>
                                                @endforeach
                                            </select>
                                            <div class="text-danger" id="customerError"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="billingType" class="form-label">Forma de Pagamento <span class="text-danger">*</span></label>
                                            <select
                                                id="billingType"
                                                name="billingType"
                                                class="form-select"
                                                required
                                            >
                                                <option value="">Selecione um tipo</option>
                                                <option value="BOLETO">Boleto</option>
                                                <option value="CREDIT_CARD">Cartão de Crédito</option>
                                                <option value="PIX">PIX</option>
                                            </select>
                                            <div class="text-danger" id="billingTypeError"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="value" class="form-label">Valor <span class="text-danger">*</span></label>
                                            <input
                                                type="number"
                                                id="value"
                                                name="value"
                                                class="form-control"
                                                step="0.01"
                                                min="0.01"
                                                placeholder="Digite o valor"
                                                required
                                            />
                                            <div class="text-danger" id="valueError"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="dueDate" class="form-label">Data de Vencimento <span class="text-danger">*</span></label>
                                            <input
                                                type="date"
                                                id="dueDate"
                                                name="dueDate"
                                                class="form-control"
                                                required
                                            />
                                            <div class="text-danger" id="dueDateError"></div>
                                        </div>
                                        <div class="mb-3">
                                            <label for="description" class="form-label">Descrição <span class="text-danger">*</span></label>
                                            <textarea
                                                id="description"
                                                name="description"
                                                class="form-control"
                                                rows="3"
                                                placeholder="Digite a descrição"
                                                required
                                            ></textarea>
                                            <div class="text-danger" id="descriptionError"></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary"><i class="tf-icons bx bx-save"></i> Salvar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
