<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo Notarial Foliado - {{ $recibo->folio_recibo_impreso }}</title>
    <style>
        @page {
            size: letter portrait;
            margin: 15mm 20mm 15mm 20mm;
        }

        @media print {
            body {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
        }

        body {
            font-family: 'Courier New', Courier, monospace, 'Times New Roman', serif;
            color: #1a1a1a;
            margin: 0;
            padding: 0;
            font-size: 11pt;
            line-height: 1.4;
            background-color: #ffffff;
        }

        .actions-bar {
            background-color: #f1f5f9;
            padding: 12px 20px;
            border-bottom: 1px solid #cbd5e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: system-ui, -apple-system, sans-serif;
            font-size: 13px;
        }

        .btn {
            background-color: #0f172a;
            color: #ffffff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-secondary {
            background-color: #64748b;
            margin-right: 8px;
        }

        .recibo-container {
            max-width: 190mm;
            margin: 0 auto;
            padding: 10mm 5mm;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #000000;
            padding-bottom: 8mm;
            margin-bottom: 6mm;
        }

        .notaria-info {
            width: 65%;
        }

        .notaria-title {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .notaria-sub {
            font-size: 10pt;
            color: #333333;
        }

        .folio-box {
            width: 32%;
            text-align: right;
            border: 2px solid #000000;
            padding: 4mm 6mm;
            background-color: #fafafa;
        }

        .folio-label {
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .folio-numero {
            font-size: 14pt;
            font-weight: bold;
            color: #b91c1c;
        }

        .datos-principales {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 6mm;
        }

        .datos-principales td {
            padding: 3mm 2mm;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 25%;
            text-transform: uppercase;
            font-size: 9pt;
        }

        .value {
            border-bottom: 1px dotted #555555;
            width: 75%;
        }

        .monto-box {
            border: 2px solid #000000;
            background-color: #f8fafc;
            padding: 4mm 6mm;
            margin: 6mm 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .monto-numero {
            font-size: 16pt;
            font-weight: bold;
        }

        .monto-letras {
            font-size: 9pt;
            font-style: italic;
            margin-top: 2mm;
            color: #333333;
        }

        .firmas {
            margin-top: 18mm;
            display: flex;
            justify-content: space-between;
            text-align: center;
        }

        .firma-col {
            width: 45%;
        }

        .linea-firma {
            border-top: 1px solid #000000;
            margin-top: 15mm;
            padding-top: 2mm;
            font-size: 9pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .firma-puesto {
            font-size: 8pt;
            color: #555555;
        }

        .footer-foliado {
            margin-top: 10mm;
            border-top: 1px dashed #999999;
            padding-top: 3mm;
            font-size: 8pt;
            color: #666666;
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>
<body>

    <div class="actions-bar no-print">
        <div>
            <strong>Documento de Recibo Oficial Foliado</strong> | Folio: {{ $recibo->folio_recibo_impreso }}
        </div>
        <div>
            <button class="btn btn-secondary" onclick="window.close()">Cerrar Ventana</button>
            <button class="btn" onclick="window.print()">Imprimir Recibo (Ctrl+P)</button>
        </div>
    </div>

    <div class="recibo-container">
        <div class="header">
            <div class="notaria-info">
                <div class="notaria-title">NOTARÍA PÚBLICA</div>
                <div class="notaria-sub">Protocolo Ordinario y Caja Notarial</div>
                <div class="notaria-sub">Recepción y Control de Anticipos y Derechos</div>
            </div>
            <div class="folio-box">
                <div class="folio-label">FOLIO OFICIAL RECIBO</div>
                <div class="folio-numero">{{ $recibo->folio_recibo_impreso }}</div>
                <div style="font-size: 8pt; margin-top: 2mm;">FECHA: {{ $recibo->fecha_pago ? $recibo->fecha_pago->format('d/m/Y H:i') : now()->format('d/m/Y H:i') }}</div>
            </div>
        </div>

        <table class="datos-principales">
            <tr>
                <td class="label">RECIBIMOS DE:</td>
                <td class="value"><strong>{{ $recibo->cliente?->nombre_razon_social ?? 'PÚBLICO EN GENERAL' }}</strong> (RFC: {{ $recibo->cliente?->rfc ?? 'XAXX010101000' }})</td>
            </tr>
            <tr>
                <td class="label">EXPEDIENTE NOTARIAL:</td>
                <td class="value"><strong>{{ $recibo->expediente?->numero_expediente ?? 'TRÁMITE GENERAL' }}</strong></td>
            </tr>
            <tr>
                <td class="label">POR CONCEPTO DE:</td>
                <td class="value">{{ $recibo->nombre_concepto_recibo }}</td>
            </tr>
            <tr>
                <td class="label">FORMA DE PAGO:</td>
                <td class="value">
                    {{ strtoupper($recibo->forma_pago) }}
                    @if($recibo->banco_referencia)
                        - REF/BANCO: {{ $recibo->banco_referencia }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">CAJERO / EMISOR:</td>
                <td class="value">{{ $recibo->usuarioCobro?->name ?? 'Caja Central Notaría' }}</td>
            </tr>
        </table>

        <div class="monto-box">
            <div>
                <div style="font-size: 9pt; font-weight: bold; text-transform: uppercase;">IMPORTE TOTAL RECIBIDO</div>
                <div class="monto-letras">*** MONEDA NACIONAL (M.X.N.) ***</div>
            </div>
            <div class="monto-numero">
                ${{ number_format($recibo->importe, 2) }} MXN
            </div>
        </div>

        <div style="font-size: 8pt; color: #444444; margin-top: 4mm;">
            * Este documento ampara la recepción de fondos para la gestión de derechos, impuestos o gastos notariales vinculados al expediente en trámite.
            @if($recibo->requiere_cfdi)
                <br>** Se emitirá Comprobante Fiscal Digital por Internet (CFDI) conforme a las disposiciones fiscales vigentes.
            @endif
        </div>

        <div class="firmas">
            <div class="firma-col">
                <div class="linea-firma">{{ $recibo->cliente?->nombre_razon_social ?? 'ENTREGUÉ CONFORME' }}</div>
                <div class="firma-puesto">Compareciente / Cliente</div>
            </div>
            <div class="firma-col">
                <div class="linea-firma">{{ $recibo->usuarioCobro?->name ?? 'CAJA Y RECEPCIÓN NOTARIAL' }}</div>
                <div class="firma-puesto">Cajero Responsable Notaría</div>
            </div>
        </div>

        <div class="footer-foliado">
            <div>Impreso en Papel de Seguridad Notarial</div>
            <div>Fecha de Emisión: {{ now()->format('d/m/Y H:i:s') }}</div>
            <div>Estado de Folio: IMPRESO OFICIAL</div>
        </div>
    </div>

    <script>
        // Disparar diálogo de impresión automáticamente al abrir en pestaña
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
