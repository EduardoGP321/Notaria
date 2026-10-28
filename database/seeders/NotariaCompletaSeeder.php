<?php

namespace Database\Seeders;

use App\Models\Avaluo;
use App\Models\AvisosPreventivo;
use App\Models\CitasAgenda;
use App\Models\Cliente;
use App\Models\ConceptosCatalogo;
use App\Models\CortesCaja;
use App\Models\DeclaranotEnvio;
use App\Models\Escritura;
use App\Models\Expediente;
use App\Models\ExpedienteCompareciente;
use App\Models\ExpedienteDocumento;
use App\Models\Inmueble;
use App\Models\InstitucionesCredito;
use App\Models\LotesFolio;
use App\Models\Municipio;
use App\Models\OperacionRequisito;
use App\Models\PagosGestoria;
use App\Models\PeritosValuador;
use App\Models\Presupuesto;
use App\Models\PresupuestoDetalle;
use App\Models\Puesto;
use App\Models\RecibosIngreso;
use App\Models\RequisitosCatalogo;
use App\Models\TestimoniosExpedido;
use App\Models\TiposOperacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class NotariaCompletaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::transaction(function () {
            // ========================================================
            // 1. USUARIO SUPER ADMIN Y PUESTOS NOTARIALES
            // ========================================================
            $admin = User::firstOrCreate(
                ['email' => 'admin@notaria.mx'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('password'),
                    'telefono' => '2221234567',
                    'iniciales' => 'SA',
                    'activo' => true,
                    'email_verified_at' => now(),
                ]
            );

            // Asegurar puestos base
            $puestos = [
                ['clave' => 'NOTARIO', 'nombre' => 'Notario Titular'],
                ['clave' => 'ABOGADO', 'nombre' => 'Abogado Dictaminador'],
                ['clave' => 'SECRETARIA', 'nombre' => 'Secretaria de Seguimiento'],
                ['clave' => 'CAJERO', 'nombre' => 'Cajero / Administración de Fondos'],
                ['clave' => 'GESTOR', 'nombre' => 'Gestor Registral y Catastral'],
            ];

            foreach ($puestos as $puestoData) {
                $puesto = Puesto::firstOrCreate(
                    ['clave' => $puestoData['clave']],
                    [
                        'nombre' => $puestoData['nombre'],
                        'activo' => true,
                        'descripcion' => 'Puesto operativo de la notaría.',
                    ]
                );

                if (! $admin->puestos()->where('puesto_id', $puesto->id)->exists()) {
                    $admin->puestos()->attach($puesto->id, [
                        'fecha_asignacion' => now()->toDateString(),
                        'es_principal' => $puestoData['clave'] === 'NOTARIO',
                    ]);
                }
            }

            // ========================================================
            // 2. CATÁLOGOS BASE INDISPENSABLES
            // ========================================================

            // 2.1 Municipios
            $muniPuebla = Municipio::firstOrCreate(
                ['nombre' => 'Puebla'],
                [
                    'estado' => 'Puebla',
                    'porcentaje_isabi' => 2.00,
                    'dias_promedio_avaluo' => 14,
                    'activo' => true,
                ]
            );

            $muniSanAndres = Municipio::firstOrCreate(
                ['nombre' => 'San Andrés Cholula'],
                [
                    'estado' => 'Puebla',
                    'porcentaje_isabi' => 2.00,
                    'dias_promedio_avaluo' => 7,
                    'activo' => true,
                ]
            );

            $muniAtlixco = Municipio::firstOrCreate(
                ['nombre' => 'Atlixco'],
                [
                    'estado' => 'Puebla',
                    'porcentaje_isabi' => 1.80,
                    'dias_promedio_avaluo' => 5,
                    'activo' => true,
                ]
            );

            // 2.2 Instituciones de Crédito
            $instParticular = InstitucionesCredito::firstOrCreate(
                ['nombre' => 'Particular (Sin Crédito)'],
                [
                    'tipo' => 'particular',
                    'dias_limite_rpp' => null,
                    'dias_alerta_previa' => 15,
                    'activo' => true,
                ]
            );

            $instSantander = InstitucionesCredito::firstOrCreate(
                ['nombre' => 'Santander'],
                [
                    'tipo' => 'banco',
                    'dias_limite_rpp' => 70,
                    'dias_alerta_previa' => 15,
                    'activo' => true,
                ]
            );

            $instInfonavit = InstitucionesCredito::firstOrCreate(
                ['nombre' => 'Infonavit'],
                [
                    'tipo' => 'instituto_vivienda',
                    'dias_limite_rpp' => 90,
                    'dias_alerta_previa' => 15,
                    'activo' => true,
                ]
            );

            // 2.3 Tipos de Operación
            $tipoCompraParticular = TiposOperacion::firstOrCreate(
                ['nombre' => 'Compraventa Particular'],
                [
                    'requiere_avaluo' => true,
                    'requiere_rpp' => true,
                    'activo' => true,
                ]
            );

            $tipoCompraBanco = TiposOperacion::firstOrCreate(
                ['nombre' => 'Compraventa con Crédito Bancario'],
                [
                    'requiere_avaluo' => true,
                    'requiere_rpp' => true,
                    'activo' => true,
                ]
            );

            $tipoRatificacion = TiposOperacion::firstOrCreate(
                ['nombre' => 'Ratificación de Firmas'],
                [
                    'requiere_avaluo' => false,
                    'requiere_rpp' => false,
                    'activo' => true,
                ]
            );

            // 2.4 Conceptos Notariales (con categorías válidas del enum)
            $conIsabi = ConceptosCatalogo::firstOrCreate(
                ['clave' => 'IMP-ISABI'],
                [
                    'nombre' => 'ISABI (Impuesto sobre Adquisición de Bienes Inmuebles)',
                    'categoria' => 'impuesto',
                    'dependencia_destino' => 'Tesorería Municipal',
                    'dias_resolucion_est' => 3,
                    'activo' => true,
                ]
            );

            $conRpp = ConceptosCatalogo::firstOrCreate(
                ['clave' => 'DER-RPP'],
                [
                    'nombre' => 'Derechos de Inscripción RPP',
                    'categoria' => 'derecho',
                    'dependencia_destino' => 'Registro Público de la Propiedad',
                    'dias_resolucion_est' => 20,
                    'activo' => true,
                ]
            );

            $conHon = ConceptosCatalogo::firstOrCreate(
                ['clave' => 'HON-NOT'],
                [
                    'nombre' => 'Honorarios Notariales',
                    'categoria' => 'honorarios_notaria',
                    'dependencia_destino' => 'Notaría Pública',
                    'dias_resolucion_est' => 1,
                    'activo' => true,
                ]
            );

            $conAvaluo = ConceptosCatalogo::firstOrCreate(
                ['clave' => 'SRV-AVAL'],
                [
                    'nombre' => 'Avalúo Catastral y Comercial',
                    'categoria' => 'avaluo',
                    'dependencia_destino' => 'Padrón de Peritos Valuadores',
                    'dias_resolucion_est' => 10,
                    'activo' => true,
                ]
            );

            $conPredial = ConceptosCatalogo::firstOrCreate(
                ['clave' => 'IMP-PRED'],
                [
                    'nombre' => 'Impuesto Predial y Constancia de No Adeudo',
                    'categoria' => 'impuesto',
                    'dependencia_destino' => 'Catastro Municipal',
                    'dias_resolucion_est' => 2,
                    'activo' => true,
                ]
            );

            $conAviso = ConceptosCatalogo::firstOrCreate(
                ['clave' => 'DER-AVISO'],
                [
                    'nombre' => 'Aviso Preventivo RPP',
                    'categoria' => 'derecho',
                    'dependencia_destino' => 'Registro Público de la Propiedad',
                    'dias_resolucion_est' => 2,
                    'activo' => true,
                ]
            );

            // 2.5 Requisitos del Catálogo
            $reqIne = RequisitosCatalogo::firstOrCreate(
                ['nombre_documento' => 'Identificación Oficial Vigente (INE / Pasaporte)'],
                ['descripcion' => 'Copia legible y original para cotejo.']
            );

            $reqCurp = RequisitosCatalogo::firstOrCreate(
                ['nombre_documento' => 'CURP Certificada'],
                ['descripcion' => 'Formato emitido por RENAPO.']
            );

            $reqRfc = RequisitosCatalogo::firstOrCreate(
                ['nombre_documento' => 'Constancia de Situación Fiscal (SAT)'],
                ['descripcion' => 'Con código QR no mayor a 3 meses de antigüedad.']
            );

            $reqTitulo = RequisitosCatalogo::firstOrCreate(
                ['nombre_documento' => 'Título de Propiedad / Escritura Antecedente'],
                ['descripcion' => 'Primer testimonio con boleta de inscripción RPP.']
            );

            $reqPredial = RequisitosCatalogo::firstOrCreate(
                ['nombre_documento' => 'Boleta Predial del Ejercicio Vigente Pagada'],
                ['descripcion' => 'Recibo oficial emitido por tesorería municipal.']
            );

            $reqAgua = RequisitosCatalogo::firstOrCreate(
                ['nombre_documento' => 'Constancia de No Adeudo de Agua Potable'],
                ['descripcion' => 'Emitida por SOAPAP o comité municipal de agua.']
            );

            // Vincular requisitos a tipos de operación
            foreach ([$reqIne, $reqCurp, $reqRfc, $reqTitulo, $reqPredial, $reqAgua] as $req) {
                OperacionRequisito::firstOrCreate([
                    'tipo_operacion_id' => $tipoCompraParticular->id,
                    'requisito_id' => $req->id,
                ], [
                    'obligatorio' => true,
                ]);

                OperacionRequisito::firstOrCreate([
                    'tipo_operacion_id' => $tipoCompraBanco->id,
                    'requisito_id' => $req->id,
                ], [
                    'obligatorio' => true,
                ]);
            }

            // 2.6 Perito Valuador
            $perito = PeritosValuador::firstOrCreate(
                ['cedula_registro' => 'PUE-VAL-2024-88'],
                [
                    'nombre_completo' => 'Arq. Roberto Morales Santos',
                    'telefono' => '2221987654',
                    'email' => 'arq.morales@peritajes.mx',
                    'activo' => true,
                ]
            );

            // 2.7 Lote de Folios
            $lote = LotesFolio::firstOrCreate(
                ['numero_volumen_libro' => 124],
                [
                    'folio_inicial' => 1001,
                    'folio_final' => 1500,
                    'folios_disponibles' => 480,
                    'fecha_autorizacion' => now()->subMonths(6)->toDateString(),
                ]
            );

            // ========================================================
            // 3. CASO 1: FLUJO COMPLETO Y CONCLUIDO (Puebla - Inscrita y Entregada)
            // ========================================================

            // Clientes
            $vendedor1 = Cliente::firstOrCreate(
                ['rfc' => 'PELJ7504128N2'],
                [
                    'tipo_persona' => 'fisica',
                    'nombre_razon_social' => 'Juan Carlos Pérez López',
                    'curp' => 'PELJ750412HPLRNP03',
                    'estado_civil' => 'casado_sociedad_conyugal',
                    'telefono' => '2222345678',
                    'email' => 'jcperez.notaria@gmail.com',
                    'domicilio_fiscal' => 'Av. Juárez 2104, Col. La Paz, Puebla, Pue.',
                ]
            );

            $comprador1 = Cliente::firstOrCreate(
                ['rfc' => 'DORM820925KL3'],
                [
                    'tipo_persona' => 'fisica',
                    'nombre_razon_social' => 'María Elena Domínguez Ruiz',
                    'curp' => 'DORM820925MPLMNS02',
                    'estado_civil' => 'soltero',
                    'telefono' => '2228765432',
                    'email' => 'mdominguez.ruiz@hotmail.com',
                    'domicilio_fiscal' => 'Calle 16 de Septiembre 3502, Col. El Carmen, Puebla, Pue.',
                ]
            );

            // Presupuesto
            $presupuesto1 = Presupuesto::firstOrCreate(
                ['folio_presupuesto' => 'PRES-2026-0001'],
                [
                    'cliente_id' => $comprador1->id,
                    'tipo_operacion_id' => $tipoCompraParticular->id,
                    'municipio_id' => $muniPuebla->id,
                    'abogado_id' => $admin->id,
                    'valor_operacion' => 2500000.00,
                    'porcentaje_aplicado' => 2.00,
                    'subtotal_impuestos_der' => 50000.00,
                    'honorarios_notariales' => 30000.00,
                    'iva_honorarios' => 4800.00,
                    'total_presupuesto' => 84800.00,
                    'estatus' => 'aprobado_con_anticipo',
                ]
            );

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto1->id,
                'concepto_id' => $conIsabi->id,
            ], [
                'monto_estimado' => 50000.00,
                'observaciones' => '2.00% sobre valor de operación en Municipio de Puebla',
            ]);

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto1->id,
                'concepto_id' => $conRpp->id,
            ], [
                'monto_estimado' => 9500.00,
                'observaciones' => 'Derechos por inscripción de compraventa en RPP',
            ]);

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto1->id,
                'concepto_id' => $conAvaluo->id,
            ], [
                'monto_estimado' => 6500.00,
                'observaciones' => 'Dictamen catastral para pago de ISABI',
            ]);

            // Expediente
            $expediente1 = Expediente::firstOrCreate(
                ['numero_expediente' => 'EXP-2026-0001'],
                [
                    'presupuesto_id' => $presupuesto1->id,
                    'tipo_operacion_id' => $tipoCompraParticular->id,
                    'institucion_credito_id' => $instParticular->id,
                    'municipio_id' => $muniPuebla->id,
                    'abogado_id' => $admin->id,
                    'secretaria_id' => $admin->id,
                    'fecha_apertura' => now()->subDays(60)->toDateString(),
                    'fecha_limite_sla' => null,
                    'requiere_factura_hon' => true,
                    'estatus_proceso' => 'entregado',
                    'observaciones' => 'Operación concluida exitosamente. Testimonio inscrito entregado a la compradora.',
                ]
            );

            // Inmueble
            Inmueble::firstOrCreate(
                ['expediente_id' => $expediente1->id],
                [
                    'municipio_id' => $muniPuebla->id,
                    'cuenta_predial' => 'U-048291-01',
                    'clave_catastral' => '21-114-001-08-0045-001',
                    'direccion_completa' => 'Calle Teziutlán Sur No. 45, Col. La Paz, C.P. 72160, Puebla, Pue.',
                    'superficie_terreno_m2' => 250.00,
                    'superficie_construccion_m2' => 310.50,
                    'medidas_colindancias' => 'Al Norte 12.50m con Calle Teziutlán Sur; al Sur 12.50m con Lote 12; al Oriente 20.00m con Lote 44; al Poniente 20.00m con Lote 46.',
                    'antecedente_escritura' => 'Escritura Pública 24150 del 14/05/2012 ante Notaría Pública 3 de Puebla.',
                    'folio_real_antecedente' => '0048192-1',
                ]
            );

            // Comparecientes
            ExpedienteCompareciente::firstOrCreate([
                'expediente_id' => $expediente1->id,
                'cliente_id' => $vendedor1->id,
                'rol_compareciente' => 'vendedor',
            ], [
                'recibe_factura_catastro' => false,
            ]);

            ExpedienteCompareciente::firstOrCreate([
                'expediente_id' => $expediente1->id,
                'cliente_id' => $comprador1->id,
                'rol_compareciente' => 'comprador',
            ], [
                'recibe_factura_catastro' => true,
            ]);

            // Documentos
            foreach ([$reqIne, $reqCurp, $reqRfc, $reqTitulo, $reqPredial, $reqAgua] as $req) {
                ExpedienteDocumento::firstOrCreate([
                    'expediente_id' => $expediente1->id,
                    'requisito_id' => $req->id,
                ], [
                    'entregado_fisico' => true,
                    'fecha_recepcion' => now()->subDays(58),
                ]);
            }

            // Aviso Preventivo
            AvisosPreventivo::firstOrCreate(
                [
                    'expediente_id' => $expediente1->id,
                    'tipo_aviso' => 'primero',
                ],
                [
                    'fecha_presentacion_rpp' => now()->subDays(55)->toDateString(),
                    'fecha_vencimiento' => now()->subDays(10)->toDateString(),
                    'numero_entrada_rpp' => 'RPP-PUE-2026-11842',
                    'estatus' => 'autorizado',
                ]
            );

            // Avalúo
            Avaluo::firstOrCreate(
                ['expediente_id' => $expediente1->id],
                [
                    'perito_id' => $perito->id,
                    'fecha_solicitud' => now()->subDays(54)->toDateString(),
                    'fecha_estimada_entrega' => now()->subDays(40)->toDateString(),
                    'fecha_recepcion_real' => now()->subDays(42)->toDateString(),
                    'valor_catastral' => 2300000.00,
                    'valor_comercial' => 2550000.00,
                    'estatus' => 'entregado',
                ]
            );

            // Pagos Gestoría (Ambos liquidados con factura)
            PagosGestoria::firstOrCreate([
                'expediente_id' => $expediente1->id,
                'concepto_id' => $conIsabi->id,
            ], [
                'linea_captura_referencia' => 'LC-PUE-2026-88192',
                'banco_pago' => 'BBVA Bancomer',
                'monto_solicitado' => 50000.00,
                'admin_caja_id' => $admin->id,
                'gestor_id' => $admin->id,
                'secretaria_recibe_id' => $admin->id,
                'fecha_solicitud_recurso' => now()->subDays(42)->toDateString(),
                'fecha_entrega_gestor' => now()->subDays(41)->toDateString(),
                'fecha_pago_dependencia' => now()->subDays(40)->toDateString(),
                'fecha_factura_secretaria' => now()->subDays(35)->toDateString(),
                'estatus_pago' => 'factura_con_secretaria',
            ]);

            PagosGestoria::firstOrCreate([
                'expediente_id' => $expediente1->id,
                'concepto_id' => $conRpp->id,
            ], [
                'linea_captura_referencia' => 'LC-RPP-2026-00412',
                'banco_pago' => 'Santander',
                'monto_solicitado' => 9500.00,
                'admin_caja_id' => $admin->id,
                'gestor_id' => $admin->id,
                'secretaria_recibe_id' => $admin->id,
                'fecha_solicitud_recurso' => now()->subDays(42)->toDateString(),
                'fecha_entrega_gestor' => now()->subDays(41)->toDateString(),
                'fecha_pago_dependencia' => now()->subDays(40)->toDateString(),
                'fecha_factura_secretaria' => now()->subDays(35)->toDateString(),
                'estatus_pago' => 'factura_con_secretaria',
            ]);

            // Corte de Caja y Recibos
            $corte1 = CortesCaja::firstOrCreate(
                ['fecha_inicio' => now()->subDays(50)->toDateString(), 'fecha_fin' => now()->subDays(50)->toDateString()],
                [
                    'tipo_corte' => 'diario',
                    'total_efectivo' => 30000.00,
                    'total_bancos' => 54800.00,
                    'monto_total' => 84800.00,
                    'usuario_elabora_id' => $admin->id,
                ]
            );

            RecibosIngreso::firstOrCreate(
                ['folio_recibo_impreso' => 'REC-FOL-0890'],
                [
                    'expediente_id' => $expediente1->id,
                    'cliente_id' => $comprador1->id,
                    'usuario_cobro_id' => $admin->id,
                    'corte_caja_id' => $corte1->id,
                    'nombre_concepto_recibo' => 'Anticipo inicial para inicio de trámites, avalúo catastral e ISABI',
                    'importe' => 30000.00,
                    'forma_pago' => 'efectivo',
                    'impreso_en_foliado' => true,
                    'requiere_cfdi' => true,
                    'fecha_pago' => now()->subDays(55),
                ]
            );

            RecibosIngreso::firstOrCreate(
                ['folio_recibo_impreso' => 'REC-FOL-0891'],
                [
                    'expediente_id' => $expediente1->id,
                    'cliente_id' => $comprador1->id,
                    'usuario_cobro_id' => $admin->id,
                    'corte_caja_id' => $corte1->id,
                    'nombre_concepto_recibo' => 'Finiquito de honorarios notariales y derechos de inscripción RPP',
                    'importe' => 54800.00,
                    'forma_pago' => 'transferencia',
                    'banco_referencia' => 'SPEI-BBVA-781923',
                    'impreso_en_foliado' => true,
                    'requiere_cfdi' => true,
                    'fecha_pago' => now()->subDays(50),
                ]
            );

            // Escritura (Protocolo)
            $escritura1 = Escritura::firstOrCreate(
                ['numero_escritura' => 45210],
                [
                    'expediente_id' => $expediente1->id,
                    'lote_folio_id' => $lote->id,
                    'volumen' => 124,
                    'folio_inicio' => 1001,
                    'folio_fin' => 1005,
                    'cantidad_folios_usados' => 5,
                    'forma_liquidacion_partes' => 'transferencia',
                    'fecha_firma' => now()->subDays(45)->toDateString(),
                    'firmas_completas' => true,
                    'engargolada_lista' => true,
                    'fecha_ingreso_rpp' => now()->subDays(30)->toDateString(),
                    'fecha_inscripcion_rpp' => now()->subDays(10)->toDateString(),
                    'folio_real_rpp' => '0184729-1',
                    'fecha_entrega_cliente' => now()->subDays(5)->toDateString(),
                ]
            );

            // Testimonio
            TestimoniosExpedido::firstOrCreate(
                [
                    'escritura_id' => $escritura1->id,
                    'tipo_documento' => 'primer_testimonio',
                ],
                [
                    'destinatario_tipo' => 'adquirente_cliente',
                    'cliente_id' => $comprador1->id,
                    'numero_fojas' => 6,
                    'fecha_expedicion' => now()->subDays(8)->toDateString(),
                    'fecha_entrega' => now()->subDays(5)->toDateString(),
                    'persona_recibe_nombre' => 'María Elena Domínguez Ruiz',
                    'usuario_entrega_id' => $admin->id,
                ]
            );

            // DeclaraNOT SAT
            DeclaranotEnvio::firstOrCreate(
                ['escritura_id' => $escritura1->id],
                [
                    'vendedor_cliente_id' => $vendedor1->id,
                    'monto_isr_retenido' => 45800.00,
                    'folio_declaranot_sat' => 'DEC-SAT-2026-981245',
                    'enviado_por_correo' => true,
                    'fecha_envio' => now()->subDays(40),
                ]
            );

            // ========================================================
            // 4. CASO 2: EN PROCESO CON SEMÁFORO SLA ACTIVO (Santander - Alerta Amarilla)
            // ========================================================

            // Clientes
            $comprador2 = Cliente::firstOrCreate(
                ['rfc' => 'CAVF850714R89'],
                [
                    'tipo_persona' => 'fisica',
                    'nombre_razon_social' => 'Ing. Fernando Castillo Vega',
                    'curp' => 'CAVF850714HPLSRN08',
                    'estado_civil' => 'casado_separacion_bienes',
                    'telefono' => '2223456789',
                    'email' => 'fcastillo.vega@techpuebla.com',
                    'domicilio_fiscal' => 'Blvd. Europa 102, Lomas de Angelópolis, San Andrés Cholula, Pue.',
                ]
            );

            $vendedora2 = Cliente::firstOrCreate(
                ['rfc' => 'IAN1003159X4'],
                [
                    'tipo_persona' => 'moral',
                    'nombre_razon_social' => 'Inmobiliaria Angelópolis S.A. de C.V.',
                    'curp' => null,
                    'estado_civil' => null,
                    'telefono' => '2222114455',
                    'email' => 'contacto@inmobiliariaangelopolis.mx',
                    'domicilio_fiscal' => 'Vía Atlixcáyotl 5400, Torre Empresarial Piso 8, San Andrés Cholula, Pue.',
                ]
            );

            // Presupuesto
            $presupuesto2 = Presupuesto::firstOrCreate(
                ['folio_presupuesto' => 'PRES-2026-0045'],
                [
                    'cliente_id' => $comprador2->id,
                    'tipo_operacion_id' => $tipoCompraBanco->id,
                    'municipio_id' => $muniSanAndres->id,
                    'abogado_id' => $admin->id,
                    'valor_operacion' => 3800000.00,
                    'porcentaje_aplicado' => 2.00,
                    'subtotal_impuestos_der' => 76000.00,
                    'honorarios_notariales' => 42000.00,
                    'iva_honorarios' => 6720.00,
                    'total_presupuesto' => 124720.00,
                    'estatus' => 'aprobado_con_anticipo',
                ]
            );

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto2->id,
                'concepto_id' => $conIsabi->id,
            ], [
                'monto_estimado' => 76000.00,
                'observaciones' => 'ISABI San Andrés Cholula al 2.00%',
            ]);

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto2->id,
                'concepto_id' => $conRpp->id,
            ], [
                'monto_estimado' => 14200.00,
                'observaciones' => 'Derechos de inscripción compraventa e hipoteca Santander',
            ]);

            // Expediente: Apertura hace 58 días, límite de 70 días (quedan 12 días -> ALERTA AMARILLA)
            $fechaApertura2 = now()->subDays(58)->startOfDay();
            $fechaLimite2 = $fechaApertura2->copy()->addDays(70)->toDateString();

            $expediente2 = Expediente::firstOrCreate(
                ['numero_expediente' => 'EXP-2026-0045'],
                [
                    'presupuesto_id' => $presupuesto2->id,
                    'tipo_operacion_id' => $tipoCompraBanco->id,
                    'institucion_credito_id' => $instSantander->id,
                    'municipio_id' => $muniSanAndres->id,
                    'abogado_id' => $admin->id,
                    'secretaria_id' => $admin->id,
                    'fecha_apertura' => $fechaApertura2->toDateString(),
                    'fecha_limite_sla' => $fechaLimite2,
                    'requiere_factura_hon' => true,
                    'estatus_proceso' => 'pagando_impuestos',
                    'observaciones' => 'Crédito Santander en semáforo amarillo (12 días restantes). Urge pago en banco de derechos RPP para armar e ingresar.',
                ]
            );

            // Inmueble
            Inmueble::firstOrCreate(
                ['expediente_id' => $expediente2->id],
                [
                    'municipio_id' => $muniSanAndres->id,
                    'cuenta_predial' => 'C-109284-02',
                    'clave_catastral' => '21-119-002-04-0012-008',
                    'direccion_completa' => 'Cluster Parque Habana Lote 12 Int. Depto 402, Lomas de Angelópolis II, San Andrés Cholula, Pue.',
                    'superficie_terreno_m2' => 95.00,
                    'superficie_construccion_m2' => 142.80,
                    'medidas_colindancias' => 'Al Norte 8.50m con pasillo común; al Sur 8.50m con vacío; al Oriente 16.80m con Depto 401; al Poniente 16.80m con Depto 403.',
                    'antecedente_escritura' => 'Escritura 38100 del 20/09/2018 ante Notaría Pública 1 de San Andrés Cholula.',
                    'folio_real_antecedente' => '0091823-1',
                ]
            );

            // Comparecientes
            ExpedienteCompareciente::firstOrCreate([
                'expediente_id' => $expediente2->id,
                'cliente_id' => $comprador2->id,
                'rol_compareciente' => 'comprador',
            ], [
                'recibe_factura_catastro' => true,
            ]);

            ExpedienteCompareciente::firstOrCreate([
                'expediente_id' => $expediente2->id,
                'cliente_id' => $vendedora2->id,
                'rol_compareciente' => 'vendedor',
            ], [
                'recibe_factura_catastro' => false,
            ]);

            // Aviso Preventivo
            AvisosPreventivo::firstOrCreate(
                [
                    'expediente_id' => $expediente2->id,
                    'tipo_aviso' => 'primero',
                ],
                [
                    'fecha_presentacion_rpp' => now()->subDays(52)->toDateString(),
                    'fecha_vencimiento' => now()->addDays(38)->toDateString(),
                    'numero_entrada_rpp' => 'RPP-SACH-2026-04918',
                    'estatus' => 'presentado',
                ]
            );

            // Avalúo
            Avaluo::firstOrCreate(
                ['expediente_id' => $expediente2->id],
                [
                    'perito_id' => $perito->id,
                    'fecha_solicitud' => now()->subDays(54)->toDateString(),
                    'fecha_estimada_entrega' => now()->subDays(45)->toDateString(),
                    'fecha_recepcion_real' => now()->subDays(46)->toDateString(),
                    'valor_catastral' => 3600000.00,
                    'valor_comercial' => 3850000.00,
                    'estatus' => 'entregado',
                ]
            );

            // Pagos de Gestoría:
            // 1. ISABI pagado y con factura en secretaría
            PagosGestoria::firstOrCreate([
                'expediente_id' => $expediente2->id,
                'concepto_id' => $conIsabi->id,
            ], [
                'linea_captura_referencia' => 'LC-SACH-ISABI-2026-092',
                'banco_pago' => 'Santander',
                'monto_solicitado' => 76000.00,
                'admin_caja_id' => $admin->id,
                'gestor_id' => $admin->id,
                'secretaria_recibe_id' => $admin->id,
                'fecha_solicitud_recurso' => now()->subDays(20)->toDateString(),
                'fecha_entrega_gestor' => now()->subDays(18)->toDateString(),
                'fecha_pago_dependencia' => now()->subDays(15)->toDateString(),
                'fecha_factura_secretaria' => now()->subDays(12)->toDateString(),
                'estatus_pago' => 'factura_con_secretaria',
            ]);

            // 2. Derechos RPP: Pendiente en estatus recurso_solicitado (detona el candado de engargolado)
            PagosGestoria::firstOrCreate([
                'expediente_id' => $expediente2->id,
                'concepto_id' => $conRpp->id,
            ], [
                'linea_captura_referencia' => 'LC-RPP-SACH-2026-0182',
                'banco_pago' => 'Santander',
                'monto_solicitado' => 14200.00,
                'admin_caja_id' => $admin->id,
                'gestor_id' => $admin->id,
                'secretaria_recibe_id' => null,
                'fecha_solicitud_recurso' => now()->subDays(3)->toDateString(),
                'fecha_entrega_gestor' => null,
                'fecha_pago_dependencia' => null,
                'fecha_factura_secretaria' => null,
                'estatus_pago' => 'recurso_solicitado',
            ]);

            // Recibo de Anticipo
            RecibosIngreso::firstOrCreate(
                ['folio_recibo_impreso' => 'REC-FOL-0899'],
                [
                    'expediente_id' => $expediente2->id,
                    'cliente_id' => $comprador2->id,
                    'usuario_cobro_id' => $admin->id,
                    'nombre_concepto_recibo' => 'Anticipo para pago de ISABI y avalúo comercial bancario',
                    'importe' => 50000.00,
                    'forma_pago' => 'transferencia',
                    'banco_referencia' => 'SPEI-SANT-99124',
                    'impreso_en_foliado' => true,
                    'requiere_cfdi' => true,
                    'fecha_pago' => now()->subDays(50),
                ]
            );

            // Escritura: Firmada, pero engargolada_lista = false (por el candado del pago RPP pendiente)
            Escritura::firstOrCreate(
                ['numero_escritura' => 45211],
                [
                    'expediente_id' => $expediente2->id,
                    'lote_folio_id' => $lote->id,
                    'volumen' => 124,
                    'folio_inicio' => 1006,
                    'folio_fin' => 1010,
                    'cantidad_folios_usados' => 5,
                    'forma_liquidacion_partes' => 'transferencia',
                    'fecha_firma' => now()->subDays(10)->toDateString(),
                    'firmas_completas' => true,
                    'engargolada_lista' => false,
                    'fecha_ingreso_rpp' => null,
                    'fecha_inscripcion_rpp' => null,
                ]
            );

            // Cita de firma en agenda
            CitasAgenda::firstOrCreate([
                'expediente_id' => $expediente2->id,
                'tipo_cita' => 'firma_escritura',
            ], [
                'abogado_id' => $admin->id,
                'nombre_interesado' => 'Ing. Fernando Castillo Vega',
                'fecha_hora_inicio' => now()->subDays(10)->setTime(12, 0),
                'fecha_hora_fin' => now()->subDays(10)->setTime(13, 0),
                'estatus' => 'completada',
            ]);

            // ========================================================
            // 5. CASO 3: EXPEDIENTE INICIAL / RECIÉN CREADO (Atlixco - Asesoría y Anticipo)
            // ========================================================

            // Cliente
            $compradora3 = Cliente::firstOrCreate(
                ['rfc' => 'MOLG9005183P5'],
                [
                    'tipo_persona' => 'fisica',
                    'nombre_razon_social' => 'Lic. Gabriela Montesinos Lozada',
                    'curp' => 'MOLG900518MPLNT01',
                    'estado_civil' => 'soltero',
                    'telefono' => '2441029384',
                    'email' => 'gaby.montesinos@gmail.com',
                    'domicilio_fiscal' => 'Calle 4 Poniente 308, Centro, Atlixco, Pue.',
                ]
            );

            // Presupuesto
            $presupuesto3 = Presupuesto::firstOrCreate(
                ['folio_presupuesto' => 'PRES-2026-0102'],
                [
                    'cliente_id' => $compradora3->id,
                    'tipo_operacion_id' => $tipoCompraParticular->id,
                    'municipio_id' => $muniAtlixco->id,
                    'abogado_id' => $admin->id,
                    'valor_operacion' => 950000.00,
                    'porcentaje_aplicado' => 1.80,
                    'subtotal_impuestos_der' => 17100.00,
                    'honorarios_notariales' => 18000.00,
                    'iva_honorarios' => 2880.00,
                    'total_presupuesto' => 37980.00,
                    'estatus' => 'aprobado_con_anticipo',
                ]
            );

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto3->id,
                'concepto_id' => $conIsabi->id,
            ], [
                'monto_estimado' => 17100.00,
                'observaciones' => 'ISABI Municipio de Atlixco al 1.80%',
            ]);

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto3->id,
                'concepto_id' => $conRpp->id,
            ], [
                'monto_estimado' => 5200.00,
                'observaciones' => 'Derechos de registro público Atlixco',
            ]);

            PresupuestoDetalle::firstOrCreate([
                'presupuesto_id' => $presupuesto3->id,
                'concepto_id' => $conAvaluo->id,
            ], [
                'monto_estimado' => 4500.00,
                'observaciones' => 'Avalúo catastral Atlixco',
            ]);

            // Expediente aperturado hoy
            $expediente3 = Expediente::firstOrCreate(
                ['numero_expediente' => 'EXP-2026-0089'],
                [
                    'presupuesto_id' => $presupuesto3->id,
                    'tipo_operacion_id' => $tipoCompraParticular->id,
                    'institucion_credito_id' => $instParticular->id,
                    'municipio_id' => $muniAtlixco->id,
                    'abogado_id' => $admin->id,
                    'secretaria_id' => $admin->id,
                    'fecha_apertura' => now()->toDateString(),
                    'fecha_limite_sla' => null,
                    'requiere_factura_hon' => false,
                    'estatus_proceso' => 'aviso_preventivo',
                    'observaciones' => 'Expediente aperturado el día de hoy con anticipo de $10,000.00 en efectivo en caja de recepción.',
                ]
            );

            // Inmueble
            Inmueble::firstOrCreate(
                ['expediente_id' => $expediente3->id],
                [
                    'municipio_id' => $muniAtlixco->id,
                    'cuenta_predial' => 'A-004921-00',
                    'clave_catastral' => '21-019-001-05-0089-000',
                    'direccion_completa' => 'Fraccionamiento Las Calandrias Lote 8 Manzana 2, Atlixco, Pue.',
                    'superficie_terreno_m2' => 400.00,
                    'superficie_construccion_m2' => 180.00,
                    'medidas_colindancias' => 'Al Norte 20.00m con Calle Las Calandrias; al Sur 20.00m con Lote 9; al Oriente 20.00m con Lote 7; al Poniente 20.00m con área verde común.',
                    'antecedente_escritura' => 'Escritura 15420 de fecha 05/11/2005 ante Notaría Pública 1 de Atlixco.',
                    'folio_real_antecedente' => '0019284-1',
                ]
            );

            // Compareciente
            ExpedienteCompareciente::firstOrCreate([
                'expediente_id' => $expediente3->id,
                'cliente_id' => $compradora3->id,
                'rol_compareciente' => 'comprador',
            ], [
                'recibe_factura_catastro' => true,
            ]);

            // Documentos: algunos entregados y otros pendientes
            ExpedienteDocumento::firstOrCreate([
                'expediente_id' => $expediente3->id,
                'requisito_id' => $reqIne->id,
            ], [
                'entregado_fisico' => true,
                'fecha_recepcion' => now(),
            ]);

            ExpedienteDocumento::firstOrCreate([
                'expediente_id' => $expediente3->id,
                'requisito_id' => $reqCurp->id,
            ], [
                'entregado_fisico' => true,
                'fecha_recepcion' => now(),
            ]);

            ExpedienteDocumento::firstOrCreate([
                'expediente_id' => $expediente3->id,
                'requisito_id' => $reqTitulo->id,
            ], [
                'entregado_fisico' => true,
                'fecha_recepcion' => now(),
            ]);

            ExpedienteDocumento::firstOrCreate([
                'expediente_id' => $expediente3->id,
                'requisito_id' => $reqPredial->id,
            ], [
                'entregado_fisico' => false,
                'fecha_recepcion' => null,
            ]);

            ExpedienteDocumento::firstOrCreate([
                'expediente_id' => $expediente3->id,
                'requisito_id' => $reqAgua->id,
            ], [
                'entregado_fisico' => false,
                'fecha_recepcion' => null,
            ]);

            // Recibo de Anticipo (sin corte_caja_id, pendiente de corte semanal)
            RecibosIngreso::firstOrCreate(
                ['folio_recibo_impreso' => 'REC-FOL-0915'],
                [
                    'expediente_id' => $expediente3->id,
                    'cliente_id' => $compradora3->id,
                    'usuario_cobro_id' => $admin->id,
                    'corte_caja_id' => null,
                    'nombre_concepto_recibo' => 'Anticipo inicial para apertura de expediente, trámite de avalúo catastral y primer aviso preventivo',
                    'importe' => 10000.00,
                    'forma_pago' => 'efectivo',
                    'impreso_en_foliado' => true,
                    'requiere_cfdi' => false,
                    'fecha_pago' => now(),
                ]
            );

            // Avalúo Solicitado (5 días promedio en Atlixco)
            Avaluo::firstOrCreate(
                ['expediente_id' => $expediente3->id],
                [
                    'perito_id' => $perito->id,
                    'fecha_solicitud' => now()->toDateString(),
                    'fecha_estimada_entrega' => now()->addDays(5)->toDateString(),
                    'fecha_recepcion_real' => null,
                    'valor_catastral' => null,
                    'valor_comercial' => null,
                    'estatus' => 'solicitado',
                ]
            );

            // Cita de asesoría programada para mañana
            CitasAgenda::firstOrCreate([
                'expediente_id' => $expediente3->id,
                'tipo_cita' => 'asesoria_inicial',
            ], [
                'abogado_id' => $admin->id,
                'nombre_interesado' => 'Lic. Gabriela Montesinos Lozada',
                'fecha_hora_inicio' => now()->addDay()->setTime(10, 0),
                'fecha_hora_fin' => now()->addDay()->setTime(11, 0),
                'estatus' => 'programada',
            ]);

            // Escritura = NULL (demuestra que aún no se baja a folios en esta etapa inicial)
        });
    }
}
