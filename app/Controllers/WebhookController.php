<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Transacao;
use App\Models\ILPI;
use App\Models\Renovacao;

class WebhookController extends Controller
{
    public function asaas()
    {
        $json = file_get_contents('php://input');
        $data = json_decode($json, true);

        if (!$data || !isset($data['event'])) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Invalid payload'], 400);
            return;
        }

        $event = $data['event'];
        $payment = $data['payment'] ?? null;

        if (!$payment || !isset($payment['id'])) {
            $this->jsonResponse(['status' => 'error', 'message' => 'Payment not found'], 400);
            return;
        }

        if (in_array($event, ['PAYMENT_CONFIRMED', 'PAYMENT_RECEIVED'])) {
            $transacaoModel = new Transacao();
            $transacao = $transacaoModel->findByAsaasId($payment['id']);

            if ($transacao) {
                $transacaoModel->updateStatus($transacao['id'], $event);

                $ilpiModel = new ILPI();
                $ilpi = $ilpiModel->find($transacao['ilpi_id']);

                if ($ilpi) {
                    $planoId = $transacao['plano_id'] ?? $ilpi['plano_id'];
                    $ilpiModel->update($ilpi['id'], ['status' => 'ativo', 'plano_id' => $planoId]);

                    $renovacaoModel = new Renovacao();
                        $historico = $renovacaoModel->findByIlpiId($ilpi['id']);
                        $ultima = $historico[0] ?? null;
                        $inicio = $ultima ? $ultima['data_vencimento'] : date('Y-m-d');
                        $vencimento = $ultima
                            ? date('Y-m-d', strtotime($ultima['data_vencimento'] . ' +1 month'))
                            : date('Y-m-d', strtotime('+1 month'));

                        $renovacaoModel->create([
                            'ilpi_id' => $ilpi['id'],
                            'plano_id' => $planoId,
                            'data_renovacao' => $inicio,
                            'data_vencimento' => $vencimento,
                            'valor' => $payment['value'] ?? 0.00,
                            'asaas_payment_id' => $payment['id'] ?? null
                        ]);
                }
            }
        }
        $this->jsonResponse(['status' => 'success']);
    }
}

