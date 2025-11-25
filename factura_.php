<?php
session_start();
if (!isset($_SESSION['usuario_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Gestión de Facturas - Roly007ram</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .invoice-container {
            max-width: 800px;
            margin: 20px auto;
            background: white;
            border: 2px solid #000;
            font-family: Arial, sans-serif;
            font-size: 12px;
            border-radius: 8px; /* Rounded corners */
        }

        .invoice-header {
            border-bottom: 2px solid #000;
            padding: 0;
            border-top-left-radius: 6px;
            border-top-right-radius: 6px;
        }

        .company-info {
            border-right: 2px solid #faf8f8;
            padding: 10px;
        }

        .invoice-type {
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
        }

        .invoice-details {
            border-bottom: 2px solid #000;
            padding: 10px;
        }

        .customer-info {
            border-bottom: 2px solid #000;
            padding: 10px;
        }

        .invoice-table {
            border-collapse: collapse;
            width: 100%;
        }

        .invoice-table th,
        .invoice-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }

        .invoice-table th {
            background: #f8f9fa;
            font-weight: bold;
        }

        .totals-section {
            border-top: 2px solid #000;
        }

        .total-row {
            border: 1px solid #000;
            padding: 5px 10px;
            background: #f8f9fa;
        }
        .total-final {
            text-align: right;
        }
        .cai {
            font-size: 9px;
            text-align: center;
        }
        .pie_factura {
            font-size: 10px;
            text-align: left;

        }
        .barcode-section {
            text-align: center;
            padding: 10px;
            border-top: 1px solid #000;
            font-size: 10px;
            border-bottom-left-radius: 6px;
            border-bottom-right-radius: 6px;
        }
        .barcode-section .barcode-svg {
            width: 80%; /* Adjust width as needed */
            height: 40px; /* Adjust height as needed */
        }
        .form-control, .form-select {
            font-size: 11px;
            border-radius: 5px;
        }

        .btn-sm {
            font-size: 11px;
            border-radius: 5px;
        }

        .management-panel {
            background: #f8f9fa;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .invoice-list {
            max-height: 300px;
            overflow-y: auto;
        }

        .editable {
            border: none;
            background: transparent;
            width: 100%;
        }

        .editable:focus {
            background: #fff3cd;
            border: 1px solid #ffc107;
        }

        .modal-content {
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="bg-light">

    <div class="container-fluid">
        <div class="management-panel">
            <div class="row">
                <div class="col-md-12">
                    <h4><i class="fas fa-file-invoice"></i> Sistema de Gestión de Facturas</h4>
                </div>
            </div>

    <div class="row mt-3">
        <div class="col-md-3">
            <button class="btn btn-success btn-sm w-100" onclick="nuevaFactura()">
                <i class="fas fa-plus"></i> Nueva Factura
            </button>
        </div>
        <div class="col-md-3">
            <button type="button" class="btn btn-outline-success btn-sm w-100" id="btnCuentaCorriente" onclick="abrirCuentaCorrienteModal()">
                <i class="fas fa-exchange-alt"></i> Cuenta Corriente
            </button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary btn-sm w-100" type="submit" form="invoiceForm" name="guardar">
                <i class="fas fa-save"></i> Guardar
            </button>
        </div>
        <div class="col-md-3">
            <button class="btn btn-info btn-sm w-100 no-print" onclick="imprimirFactura()">
                <i class="fas fa-print"></i> Imprimir
            </button>
        </div>
    </div>
            <div class="row mt-2">
                <div class="col-6">
                    <button class="btn btn-warning btn-sm w-100 no-print" onclick="exportarPDF()">
                        <i class="fas fa-file-pdf"></i> Exportar PDF
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-dark btn-sm w-100 no-print" onclick="imprimirTique80mm()">
                        <i class="fas fa-receipt"></i> Imprimir Tique 80mm
                    </button>
                </div>
                <div class="col-6">
                    <button class="btn btn-secondary btn-sm w-100 no-print" onclick="imprimirTique80mmSinQR()">
                        <i class="fas fa-receipt"></i> Imprimir Tique 80mm (texto)
                    </button>
                </div>
                <div class="col-6 mt-2">
                    <button class="btn btn-info btn-sm w-100 no-print" onclick="exportarPDF()">
                        <i class="fas fa-file-pdf"></i> Imprimir por modelo
                    </button>
                </div>
            </div>
            <div class="mt-2">
            </div>

            <div class="row mt-3">
                <div class="col-md-6">
                    <label class="form-label">Buscar Factura:</label>
                    <input type="text" class="form-control form-control-sm" id="buscarFactura" placeholder="Número de factura o cliente...">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Facturas Guardadas:</label>
                    <div class="input-group">
                        <select class="form-select form-select-sm" id="listaFacturas" onchange="cargarFactura()">
                            <option value="">Seleccionar factura...</option>
                            <?php
                            // Populate the dropdown with invoices from the database
                            require_once('db_config.php');

                            // Fetch the last invoice number for new invoice suggestion
                            $last_invoice_number = "00000000"; // Default
                            $stmt_last_invoice = $conn->prepare("SELECT numero_factura FROM facturas ORDER BY id DESC LIMIT 1");
                            if ($stmt_last_invoice) {
                                $stmt_last_invoice->execute();
                                $result_last_invoice = $stmt_last_invoice->get_result();
                                if ($row_last_invoice = $result_last_invoice->fetch_assoc()) {
                                    $last_full_number = $row_last_invoice["numero_factura"];
                                    // Assuming format "PREFIJO-NUMERO" e.g., "00012-00000016"
                                    if (strpos($last_full_number, '-') !== false) {
                                        $parts = explode('-', $last_full_number);
                                        $last_invoice_number = $parts[1]; // Get the numeric part
                                    } else {
                                        $last_invoice_number = $last_full_number; // Use full if no prefix
                                    }
                                }
                                $stmt_last_invoice->close();
                            }
                            // Increment the number
                            $next_invoice_num_int = intval($last_invoice_number) + 1;
                            $next_invoice_number_formatted = str_pad($next_invoice_num_int, 8, '0', STR_PAD_LEFT);
                            $default_numero_factura = "00012-" . $next_invoice_number_formatted;


                            $result = $conn->query("SELECT id, numero_factura, cliente_nombre, total FROM facturas ORDER BY id DESC");
                            if ($result->num_rows > 0) {
                                while($row = $result->fetch_assoc()) {
                                    echo '<option value="' . $row["id"] . '">' . $row["numero_factura"] . ' - ' . $row["cliente_nombre"] . ' - $' . number_format($row["total"], 2, ',', '.') . '</option>';
                                }
                            }
                            $conn->close();
                            ?>
                        </select>
                        <button class="btn btn-danger btn-sm" type="button" onclick="eliminarFacturaSeleccionada()" title="Eliminar factura seleccionada">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <form action="guardar_factura.php" method="post" id="invoiceForm">
        <!-- Hidden inputs for selected Empresa and Cliente IDs -->
        <input type="hidden" id="selectedEmpresaId" name="empresaId" value="">
        <input type="hidden" id="selectedClienteId" name="clienteId" value="">

        <div class="invoice-container" id="facturaContainer">
            <div class="invoice-header row g-0">
                <div class="col-4 company-info">
                    <h5 class="mb-1"><strong><span id="displayEmpresaNombre">ROSA RUBEN</span></strong></h5>
                    <small><strong><span id="displayEmpresaDireccion">AV EL LIBERTADOR N°829</span></strong></small><br>
                    <small><strong><span id="displayEmpresaCP">3366</span> -<span id="displayEmpresaLocalidad">MISIONES</span></strong></small><br>
                    <small><strong><span id="displayEmpresaTipoContribuyente">RESPONSABLE INSCRIPTO</span></strong></small><br>
                    <button type="button" class="btn btn-sm btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#empresaModal">
                        <i class="fas fa-building"></i> Gestionar Empresa
                    </button>
                </div>
                <div class="col-4 text-center" style="padding: 10px; background: #f8f9fa;">
                    <div style="border: 3px solid #000; width: 80px; height: 60px; margin: 0 auto; display: flex; align-items: center; justify-content: center; font-size: 36px; font-weight: bold; border-radius: 5px;">
                        A
                    </div>
                    <small class="mt-2 d-block">CODIGO 01</small>
                </div>
                <div class="col-4 invoice-type">
                    <h5><strong>ORIGINAL</strong></h5>
                    <div class="mt-2">
                        <small>Nº <input type="text" class="editable" id="numeroFactura" name="numeroFactura" value="<?php echo $default_numero_factura; ?>" style="width: 120px;"></small>
                    </div>
                    <div class="mt-3">
                        <table class="table table-borderless table-sm">
                            <tr>
                                <td><small>C.U.I.T.</small></td>
                                <td><small><input type="text" class="editable" id="empresaCuit" name="empresaCuit" value="20125027378" style="width: 100px;"></small></td>
                            </tr>
                            <tr>
                                <td><small>Ing. Brutos.</small></td>
                                <td><small><input type="text" class="editable" id="empresaIB" name="empresaIB" value=" C.M.: 905-302000-1" style="width: 100px;"></small></td>
                            </tr>
                            <tr>
                                <td><small>Inicio de Activ.</small></td>
                                <td><small><input type="text" class="editable" id="inicioActividad" name="inicioActividad" value="01/04/2021" style="width: 60px;"></small></td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="customer-info">
                <div class="row">
                    <div class="col-md-6">
                        <small><strong>Señor(es):</strong> <input type="text" class="editable" id="clienteNombre" name="clienteNombre" value="" style="width: 200px;"></small><br>
                        <small><strong>Domicilio:</strong> <input type="text" class="editable" id="clienteDomicilio" name="clienteDomicilio" value="" style="width: 200px;"></small>
                    </div>
                    <div class="col-md-6">
                        <small><strong>FECHA:</strong> <input type="date" class="editable" id="fecha" name="fecha" value="<?php echo date('Y-m-d'); ?>" style="width: 120px;"></small><br>
                        <small><strong>LOCALIDAD:</strong> <input type="text" class="editable" id="localidad" name="localidad" value="" style="width: 200px;"></small>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-4">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td><small><strong>I.V.A.</strong></small></td>
                                <td><small>
                                    <select class="form-select form-select-sm editable" id="clienteIva" name="clienteIva" style="font-size: 10px;">
                                        <option value="Consumidor Final">Consumidor Final</option>
                                        <option value="Resp. Inscripto">Resp. Inscripto</option>
                                        <option value="Monotributo">Monotributo</option>
                                        <option value="Exento">Exento</option>
                                    </select>
                                </small></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td><small><strong>C.U.I.T Nº</strong></small></td>
                                <td><small><input type="text" class="editable" id="clienteCuit" name="clienteCuit" value="" style="width: 100px;"></small></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-4">
                        <table class="table table-bordered table-sm">
                            <tr>
                                <td><small><strong>Cond. de venta</strong></small></td>
                                <td><small>
                                    <select class="form-select form-select-sm editable" id="condicionVenta" name="condicionVenta" style="font-size: 10px;">
                                        <option value="Contado">Contado</option>
                                        <option value="Cuenta Corriente">Cuenta Corriente</option>
                                        <option value="Cheque">Cheque</option>
                                    </select>
                                </small></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <button type="button" class="btn btn-sm btn-outline-secondary mt-2" data-bs-toggle="modal" data-bs-target="#clienteModal">
                    <i class="fas fa-users"></i> Gestionar Clientes
                </button>
            </div>

            <div class="invoice-items">
                <table class="invoice-table">
                    <thead>
                        <tr>
                            <th style="width: 8%"><small>CANT.</small></th>
                            <th style="width: 60%"><small>DETALLE</small></th>
                            <th style="width: 16%"><small>P. UNITARIO</small></th>
                            <th style="width: 16%"><small>TOTAL</small></th>
                        </tr>
                    </thead>
                    <tbody id="itemsTableBody">
                        <?php for ($i = 0; $i < 6; $i++): ?>
                        <tr>
                            <td><input type="number" class="editable" name="cantidad[]" value="" onchange="calcularTotal(this.closest('tr'))" step="1"></td>
                            <td><input type="text" class="editable" name="detalle[]" value="" style="width: 100%;"></td>
                            <td><input type="number" class="editable" name="precio_unitario[]" value="" step="0.01" onchange="calcularTotal(this.closest('tr'))" style="text-align: right;"></td>
                            <td class="total-item" style="text-align: right;">0.00</td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
                <!-- Botón para abrir el modal de productos/servicios -->
                <div class="text-end mt-2">
                    <button type="button" class="btn btn-primary btn-sm no-print" data-bs-toggle="modal" data-bs-target="#detalleModal">
                        <i class="fas fa-box"></i> Productos/Servicios
                    </button>
                </div>
    <style>
    /* Ocultar elementos con la clase no-print al imprimir */
    @media print {
        .no-print {
            display: none !important;
        }
    }
    </style>
            </div>

            <div class="totals-section">
                <div class="row g-0">
                    <div class="col-3 total-row">
                        <small><strong>Sub-Total</strong></small><br>
                        <span id="subtotal">0.00</span>
                    </div>
                    <div class="col-3 total-row">
                        <small><strong>Impuestos</strong></small><br>
                        <span id="impuestos">0.00</span>
                    </div>
                    <div class="col-3 total-row">
                        <small><strong>Sub-Total</strong></small><br>
                        <span id="subtotal2">0.00</span>
                    </div>
                    <div class="col-3 total-row">
                        <small><strong>IVA Total</strong></small><br>
                        <span id="ivaTotal">0.00</span>
                    </div>
                </div>
                <div class="row g-0">
                     <div class="col-12 total-row total-final">
                        <h5><strong>TOTAL: $<span id="totalGeneral">0.00</span></strong></h5>
                     </div>
                </div>
            </div>

            <div class="barcode-section">
                <div style="font-family: monospace; font-size: 14px; letter-spacing: 2px;">
                   <svg class="barcode-svg"
                                jsbarcode-format="code128"
                                jsbarcode-value="20309038682010012501722067317062230420258"
                                jsbarcode-textmargin="0"
                                jsbarcode-fontoptions="bold">
                            </svg>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; font-size: 9px;">
                    <div style="text-align: left;">
                        <span id="displayRegistradoraFiscal">CF HAB5503059</span><br>
                        DGI
                    </div>
                    <div style="text-align: right;">
                        FECHA DE VENCIMIENTO: <span id="displayFechaVencimientoCAI">12/05/25</span><br>
                        V: 01.00
                    </div>
                </div>
            </div>
        </div>
        </form>

        <div class="text-center mt-3">
            <button class="btn btn-secondary btn-sm" onclick="agregarFila()">
                <i class="fas fa-plus"></i> Agregar Fila
            </button>
            <button class="btn btn-danger btn-sm ms-2" onclick="limpiarFactura()">
                <i class="fas fa-trash"></i> Limpiar Factura
            </button>
        </div>
    </div>

    <!-- Empresa Modal -->
    <div class="modal fade" id="empresaModal" tabindex="-1" aria-labelledby="empresaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="empresaModalLabel">Gestionar Empresas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary btn-sm mb-3" onclick="showEmpresaForm()">Nueva Empresa</button>
                    <button class="btn btn-warning btn-sm mb-3 ms-2" onclick="exportarEmpresasExcel()">Exportar Excel</button>
                    <!-- Buscador por nombre o CUIT -->
                    <div class="row mb-2">
                        <div class="col-md-8">
                            <input type="text" class="form-control form-control-sm" id="buscarEmpresa" placeholder="Buscar por nombre o CUIT...">
                        </div>
                        <div class="col-md-4">
                            <select class="form-select form-select-sm" id="filtroEmpresaTipoFac" onchange="loadEmpresas()">
                                <option value="">Todos los tipos</option>
                            </select>
                        </div>
                    </div>
                    <div id="empresaFormContainer" style="display:none;">
                        <h5><span id="empresaFormTitle"></span> Empresa</h5>
                        <form id="empresaCrudForm">
                            <input type="hidden" id="empresaIdCrud" name="id">
                            <input type="hidden" id="modelo_pdf_actual" name="modelo_pdf_actual">
                            <div class="mb-3"><label>Nombre:</label><input type="text" class="form-control form-control-sm" id="empresaNombreCrud" name="nombre" required></div>
                            <div class="mb-3"><label>Dirección:</label><input type="text" class="form-control form-control-sm" id="empresaDireccionCrud" name="direccion"></div>
                            <div class="mb-3"><label>Código Postal:</label><input type="text" class="form-control form-control-sm" id="empresaCodigoPostalCrud" name="codigo_postal"></div>
                            <div class="mb-3"><label>Tipo Contribuyente:</label><input type="text" class="form-control form-control-sm" id="empresaTipoContribuyenteCrud" name="tipo_contribuyente"></div>
                            <div class="mb-3"><label>Actividad:</label><input type="text" class="form-control form-control-sm" id="empresaActividadCrud" name="actividad"></div>
                            <div class="mb-3"><label>Tipo de Factura:</label>
                                <select class="form-select form-select-sm" id="empresaTipoFacCrud" name="tipo_fac">
                                    <option value="">Seleccione tipo...</option>
                                </select>
                            </div>
                            <div class="mb-3"><label>CUIT:</label><input type="text" class="form-control form-control-sm" id="empresaCuitCrud" name="cuit"></div>
                            <div class="mb-3"><label>Ingresos Brutos:</label><input type="text" class="form-control form-control-sm" id="empresaIngresosBrutosCrud" name="ingresos_brutos"></div>
                            <div class="mb-3"><label>Inicio Actividad:</label><input type="date" class="form-control form-control-sm" id="empresaInicioActividadCrud" name="inicio_actividad"></div>
                            <div class="mb-3"><label>Registradora Fiscal:</label><input type="text" class="form-control form-control-sm" id="empresaRegistradoraFiscalCrud" name="registradora_fiscal"></div>
                            <div class="mb-3"><label>Código Barra CAI:</label><input type="text" class="form-control form-control-sm" id="empresaCodigoBarraCaiCrud" name="codigo_barra_cai"></div>
                            <div class="mb-3"><label>Fecha Vencimiento CAI:</label><input type="date" class="form-control form-control-sm" id="empresaFechaVencimientoCaiCrud" name="fecha_vencimiento_cai"></div>
                            <div class="mb-3">
                                <label>Modelo PDF:</label>
                                <input type="file" class="form-control form-control-sm" id="empresaModeloPdfCrud" name="modelo_pdf" accept="application/pdf">
                                <small class="form-text text-muted">Solo PDF. El archivo se guardará en la carpeta pdfmodelo.</small>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" onclick="saveEmpresa()">Guardar</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideEmpresaForm()">Cancelar</button>
                        </form>
                        <hr>
                    </div>
                    <div id="empresaListContainer">
                        <h5>Empresas Existentes</h5>
                        <table id="empresasTable" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>CUIT</th>
                                    <th>Tipo Factura</th>
                                    <th>Actividad</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="empresasTableBody">
                                <!-- Empresas will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cliente Modal -->
    <div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="clienteModalLabel">Gestionar Clientes</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary btn-sm mb-3" onclick="showClienteForm()">Nuevo Cliente</button>
                    <!-- Campo de búsqueda por nombre -->
                    <div class="mb-2">
                        <input type="text" class="form-control form-control-sm" id="buscarClienteNombre" placeholder="Buscar por nombre...">
                    </div>
                    <div id="clienteFormContainer" style="display:none;">
                        <h5><span id="clienteFormTitle"></span> Cliente</h5>
                        <form id="clienteCrudForm">
                            <input type="hidden" id="clienteIdCrud" name="id">
                            <div class="mb-3"><label>Nombre:</label><input type="text" class="form-control form-control-sm" id="clienteNombreCrud" name="nombre" required></div>
                            <div class="mb-3"><label>Domicilio:</label><input type="text" class="form-control form-control-sm" id="clienteDomicilioCrud" name="domicilio"></div>
                            <div class="mb-3"><label>Localidad:</label><input type="text" class="form-control form-control-sm" id="clienteLocalidadCrud" name="localidad"></div>
                            <div class="mb-3"><label>Tipo IVA:</label><input type="text" class="form-control form-control-sm" id="clienteTipoIvaCrud" name="tipo_iva"></div>
                            <div class="mb-3"><label>CUIT:</label><input type="text" class="form-control form-control-sm" id="clienteCuitCrud" name="cuit"></div>
                            <div class="mb-3"><label>Condición Venta Default:</label><input type="text" class="form-control form-control-sm" id="clienteCondicionVentaDefaultCrud" name="condicion_venta_default"></div>
                            <button type="button" class="btn btn-success btn-sm" onclick="saveCliente()">Guardar</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideClienteForm()">Cancelar</button>
                        </form>
                        <hr>
                    </div>
                    <div id="clienteListContainer">
                        <h5>Clientes Existentes</h5>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>CUIT</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="clientesTableBody">
                                <!-- Clientes will be loaded here via JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Detalles Modal (CRUD Productos/Servicios) -->
    <div class="modal fade" id="detalleModal" tabindex="-1" aria-labelledby="detalleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detalleModalLabel">Productos y Servicios</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary btn-sm mb-3" onclick="showDetalleForm()">Nuevo Producto/Servicio</button>
                    <!-- Filtros de búsqueda -->
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <input type="text" class="form-control form-control-sm" id="buscarDetalleDescripcion" placeholder="Buscar por descripción...">
                        </div>
                        <div class="col-md-6">
                            <select class="form-select form-select-sm" id="filtroRubro" onchange="filtrarDetalles()">
                                <option value="">Todos los rubros</option>
                            </select>
                        </div>
                    </div>
                    <!-- Formulario CRUD -->
                    <div id="detalleFormContainer" style="display:none;">
                        <h5><span id="detalleFormTitle"></span> Producto/Servicio</h5>
                        <form id="detalleCrudForm">
                            <input type="hidden" id="detalleIdCrud" name="id">
                            <div class="mb-2">
                                <label>Descripción:</label>
                                <input type="text" class="form-control form-control-sm" id="detalleDescripcionCrud" name="descripcion" required>
                            </div>
                            <div class="mb-2">
                                <label>Rubro:</label>
                                <div class="input-group">
                                    <select class="form-select form-select-sm" id="detalleRubroCrud" name="rubro_id" required>
                                        <option value="">Seleccione un rubro...</option>
                                    </select>
                                    <button class="btn btn-outline-secondary btn-sm" type="button" onclick="openRubroModal()">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-2">
                                <label>Precio:</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" id="detallePrecioCrud" name="precio" required>
                            </div>
                            <button type="button" class="btn btn-success btn-sm" onclick="saveDetalle()">Guardar</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideDetalleForm()">Cancelar</button>
                        </form>
                        <hr>
                    </div>
                    <!-- Tabla de productos/servicios -->
                    <div id="detalleListContainer">
                        <h5>Productos/Servicios Existentes</h5>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Descripción</th>
                                    <th>Rubro</th>
                                    <th>Precio</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="detallesTableBody">
                                <!-- Detalles se cargan por JS -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Rubros -->
    <div class="modal fade" id="rubroModal" tabindex="-1" aria-labelledby="rubroModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rubroModalLabel">Gestionar Rubros</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <button class="btn btn-primary btn-sm mb-3" onclick="showRubroForm()">Nuevo Rubro</button>
                    <div id="rubroFormContainer" style="display:none;">
                        <h5><span id="rubroFormTitle"></span> Rubro</h5>
                        <form id="rubroCrudForm">
                            <input type="hidden" id="rubroIdCrud" name="id">
                            <div class="mb-3">
                                <label>Nombre:</label>
                                <input type="text" class="form-control form-control-sm" id="rubroNombreCrud" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label>Descripción:</label>
                                <input type="text" class="form-control form-control-sm" id="rubroDescripcionCrud" name="descripcion">
                            </div>
                            <button type="button" class="btn btn-success btn-sm" onclick="saveRubro()">Guardar</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hideRubroForm()">Cancelar</button>
                        </form>
                        <hr>
                    </div>
                    <div id="rubroListContainer">
                        <h5>Rubros Existentes</h5>
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="rubrosTableBody">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Pagos Cuenta Corriente -->
    <div class="modal fade" id="pagosModal" tabindex="-1" aria-labelledby="pagosModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="pagosModalLabel">Pagos de Cuenta Corriente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="pagosListContainer">
                        <table class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Monto</th>
                                    <th>Fecha de Pago</th>
                                    <th>Observación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody id="pagosTableBody">
                                <!-- Pagos se cargan por JS -->
                            </tbody>
                        </table>
                    </div>
                    <button class="btn btn-primary btn-sm mb-2" onclick="showPagoForm()">Nuevo Pago</button>
                    <div id="pagoFormContainer" style="display:none;">
                        <form id="pagoCrudForm">
                            <input type="hidden" id="pagoIdCrud" name="id">
                            <div class="mb-2"><label>Monto:</label><input type="number" step="0.01" class="form-control form-control-sm" id="pagoMontoCrud" name="monto" required></div>
                            <div class="mb-2"><label>Fecha de Pago:</label><input type="date" class="form-control form-control-sm" id="pagoFechaCrud" name="fecha_pago" required></div>
                            <div class="mb-2"><label>Observación:</label><input type="text" class="form-control form-control-sm" id="pagoObsCrud" name="observacion"></div>
                            <button type="button" class="btn btn-success btn-sm" onclick="savePago()">Guardar</button>
                            <button type="button" class="btn btn-secondary btn-sm" onclick="hidePagoForm()">Cancelar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal Cuenta Corriente Cliente -->
    <div class="modal fade" id="cuentaCorrienteModal" tabindex="-1" aria-labelledby="cuentaCorrienteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cuentaCorrienteModalLabel">Cuenta Corriente del Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="saldoCuentaCorriente" class="mb-3">
                        <strong>Saldo del Cliente: </strong>
                        <span id="saldoCC" style="font-size:1.5em;font-weight:bold;"></span>
                        <span id="saldoIVA" style="font-size:1.1em;font-weight:bold; color:#007bff; margin-left:20px;"></span>
                    </div>
                    <div id="movimientosCuentaCorriente">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <strong>Movimientos</strong>
                            <button type="button" class="btn btn-warning btn-sm" onclick="exportarMovimientosExcel()">Exportar Excel</button>
                        </div>
                        <table id="tablaMovimientosCCTabla" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>Fecha</th>
                                    <th>Tipo</th>
                                    <th>Monto</th>
                                    <th>Observación</th>
                                </tr>
                            </thead>
                            <tbody id="tablaMovimientosCC">
                                <!-- Movimientos por JS -->
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h6>Registrar Pago</h6>
                    <form id="formPagoCC" onsubmit="registrarPagoCC(event)">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="number" step="0.01" class="form-control form-control-sm" id="pagoCCMonto" name="monto" placeholder="Monto" required>
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control form-control-sm" id="pagoCCFecha" name="fecha" required>
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="pagoCCObs" name="observacion" placeholder="Observación">
                            </div>
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="btn btn-success btn-sm">Registrar Pago</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.0/dist/JsBarcode.all.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script>
        // Imprimir tique en formato texto plano y QR
        function imprimirTique80mm() {
            // Extraer datos principales
            const empresa = document.getElementById('displayEmpresaNombre').textContent;
            const direccion = document.getElementById('displayEmpresaDireccion').textContent;
            const cp = document.getElementById('displayEmpresaCP').textContent;
            const localidad = document.getElementById('displayEmpresaLocalidad').textContent;
            const tipoContribuyente = document.getElementById('displayEmpresaTipoContribuyente').textContent;
            const cuit = document.getElementById('empresaCuit').value;
            const ib = document.getElementById('empresaIB').value;
            const inicioActividad = aFechaDMY(document.getElementById('inicioActividad').value);
            const registradora = document.getElementById('displayRegistradoraFiscal').textContent;
            const caiVencimiento = document.getElementById('displayFechaVencimientoCAI').textContent;
            const numeroFactura = document.getElementById('numeroFactura').value;
            const fechaISO = document.getElementById('fecha').value;
            const fecha = (function(f){ if(!f) return ''; const [y,m,d] = f.split('-'); return [d,m,y].join('/'); })(fechaISO);
            const clienteNombre = document.getElementById('clienteNombre').value;
            const clienteDomicilio = document.getElementById('clienteDomicilio').value;
            const clienteLocalidad = document.getElementById('localidad').value;
            const clienteCuit = document.getElementById('clienteCuit').value;
            const clienteIva = document.getElementById('clienteIva').value;
            const condicionVenta = document.getElementById('condicionVenta').value;
            // Ítems
            let items = '';
            document.querySelectorAll('#itemsTableBody tr').forEach(row => {
                const cant = row.querySelector('input[name="cantidad[]"]').value;
                const det = row.querySelector('input[name="detalle[]"]').value;
                const precio = row.querySelector('input[name="precio_unitario[]"]').value;
                const total = row.querySelector('.total-item').textContent;
                if (det.trim() !== '') {
                    items += `${cant} x ${det}\n   $${precio}   $${total}\n`;
                }
            });
            // Totales
            const subtotal = document.getElementById('subtotal').textContent;
            const ivaTotal = document.getElementById('ivaTotal').textContent;
            const totalGeneral = document.getElementById('totalGeneral').textContent;
            // Código de barra para QR
            let codigoBarra = document.querySelector('.barcode-svg').getAttribute('jsbarcode-value');
            if (!codigoBarra) codigoBarra = '20309038682010012501722067317062230420258';

            // Configuración de alineación a la derecha en el tique de texto
            const dash = '-----------------------------';
            const lineWidth = dash.length;
            const rightAlign = (text, width) => {
                text = String(text);
                return text.length >= width ? text : ' '.repeat(width - text.length) + text;
            };

            // Generar texto plano del tique
            let tique = '';
            tique += empresa + '\n';
            tique += direccion  + '\n';
            tique += cp + ' - ' + localidad + '\n';
            tique += tipoContribuyente + '\n';
            tique += 'CUIT: ' + cuit + '\n';
            tique += ' IB: ' + ib + '\n';
            tique += 'Inicio Actividad: ' + inicioActividad + '\n';
            tique += 'N° ' + numeroFactura + '\n';
            tique += 'Fecha  ' + fecha + '\n';
            tique += '---------------------------------\n';
            tique += 'Cliente: ' + clienteNombre + '\n';
            tique += 'Domicilio: ' + clienteDomicilio + ' - ' + clienteLocalidad + '\n';
            tique += 'CUIT: ' + clienteCuit + ' IVA: ' + clienteIva + '\n';
            tique += 'Cond. Venta: ' + condicionVenta + '\n';
            tique += '---------------------------------\n';
            tique += items;
            tique += dash + '\n';
            tique += rightAlign('SUBTOTAL: $' + subtotal, lineWidth) + '\n';
            tique += rightAlign('IVA: $' + ivaTotal, lineWidth) + '\n';
            tique += rightAlign('TOTAL: $' + totalGeneral, lineWidth) + '\n';
            tique += dash + '\n';

            // Mostrar en modal personalizado (registradora se mostrará debajo del QR)
            mostrarTiqueModal(tique, codigoBarra, registradora);
        }

        // Modal para mostrar el tique y QR
        function mostrarTiqueModal(tique, qrData, registradoraText) {
            let modal = document.getElementById('tiqueModal');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'tiqueModal';
                modal.className = 'modal fade';
                modal.tabIndex = -1;
                            modal.innerHTML = `
                                    <style>
                                        @media print {
                                            body * { visibility: hidden !important; }
                                            #tiqueModal, #tiqueModal * { visibility: visible !important; }
                                            #tiqueModal { position: absolute; left: 0; top: 0; width: 80mm !important; min-width: 80mm !important; max-width: 80mm !important; }
                                            .modal-content { box-shadow: none !important; border: none !important; }
                                            .modal-header, .modal-footer, .btn, .btn-close { display: none !important; }
                                        }
                                        #tiqueModal .modal-dialog { max-width: 80mm; min-width: 80mm; }
                                        #tiqueModal .modal-content { font-family: monospace; font-size: 12px; padding: 8px; }
                                        #tiqueTexto { font-family: monospace; font-size: 12px; white-space: pre-wrap; }
                                        #qrTique { display: block; margin: 0 auto; }
                                    </style>
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Tique 80mm</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <pre id="tiqueTexto"></pre>
                                                <div class="text-center mt-2">
                                                    <canvas id="qrTique"></canvas>
                                                </div>
                                                <div id="registradoraDebajoQR" class="text-center mt-1"></div>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-primary" onclick="imprimirSoloTique()">Imprimir tique</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar tique</button>
                                            </div>
                                        </div>
                                    </div>
                                    `;
                document.body.appendChild(modal);
            }
            // Mostrar texto
            document.getElementById('tiqueTexto').textContent = tique;
            // Generar QR
            generarQR(qrData);
            // Colocar "Registradora Fiscal" debajo del QR
            (function() {
                var regEl = document.getElementById('registradoraDebajoQR');
                if (regEl) {
                    var fallback = (document.getElementById('displayRegistradoraFiscal') ? document.getElementById('displayRegistradoraFiscal').textContent : '');
                    regEl.textContent = (typeof registradoraText !== 'undefined' && registradoraText !== null && String(registradoraText).trim() !== '')
                        ? registradoraText
                        : fallback;
                }
            })();
            // Mostrar modal
                        let bsModal = bootstrap.Modal.getOrCreateInstance(modal);
                        bsModal.show();

                        // Función para imprimir solo el contenido del tique
                        window.imprimirSoloTique = function() {
                            // Oculta el resto de la página y muestra solo el modal para impresión
                            window.print();
                        };
        }

        // Variante: imprimir tique SOLO texto (sin QR)
        function imprimirTique80mmSinQR() {
            // Reutilizar la extracción de datos del tique, igual que imprimirTique80mm
            const empresa = document.getElementById('displayEmpresaNombre').textContent;
            const direccion = document.getElementById('displayEmpresaDireccion').textContent;
            const cp = document.getElementById('displayEmpresaCP').textContent;
            const localidad = document.getElementById('displayEmpresaLocalidad').textContent;
            const tipoContribuyente = document.getElementById('displayEmpresaTipoContribuyente').textContent;
            const cuit = document.getElementById('empresaCuit').value;
            const ib = document.getElementById('empresaIB').value;
            const inicioActividad = aFechaDMY(document.getElementById('inicioActividad').value);
            const registradora = document.getElementById('displayRegistradoraFiscal').textContent;
            const caiVencimiento = document.getElementById('displayFechaVencimientoCAI').textContent;
            const numeroFactura = document.getElementById('numeroFactura').value;
            const fechaISO = document.getElementById('fecha').value;
            const fechaFormateada = (function(f){ if(!f) return ''; const [y,m,d] = f.split('-'); return [d,m,y].join('/'); })(fechaISO);
            const clienteNombre = document.getElementById('clienteNombre').value;
            const clienteDomicilio = document.getElementById('clienteDomicilio').value;
            const clienteLocalidad = document.getElementById('localidad').value;
            const clienteCuit = document.getElementById('clienteCuit').value;
            const clienteIva = document.getElementById('clienteIva').value;
            const condicionVenta = document.getElementById('condicionVenta').value;

            // Ítems
            let items = '';
            document.querySelectorAll('#itemsTableBody tr').forEach(row => {
                const cant = row.querySelector('input[name="cantidad[]"]').value;
                const det = row.querySelector('input[name="detalle[]"]').value;
                const precio = row.querySelector('input[name="precio_unitario[]"]').value;
                const total = row.querySelector('.total-item').textContent;
                if (det && det.trim() !== '') {
                    items += `${cant} x ${det}\n   $${precio}   $${total}\n`;
                }
            });

            // Totales
            const subtotal = document.getElementById('subtotal').textContent;
            const ivaTotal = document.getElementById('ivaTotal').textContent;
            const totalGeneral = document.getElementById('totalGeneral').textContent;

            let tique = '';
            tique += '<strong>' + empresa + '</strong>' + '\n';
            tique += direccion + ' - ' + cp + ' - ' + localidad + '\n';
            tique += tipoContribuyente + '\n';
            tique += 'CUIT: ' + cuit + ' IB: ' + ib + '\n';
            tique += 'Inicio Actividad: ' + inicioActividad + '\n';
            tique += '-----------------------------\n';
            tique +=  numeroFactura + '  ' + fechaFormateada + '\n';
            tique += 'Cliente: ' + clienteNombre + '\n';
            tique += 'Domicilio: ' + clienteDomicilio + ' - ' + clienteLocalidad + '\n';
            tique += 'CUIT: ' + clienteCuit + ' IVA: ' + clienteIva + '\n';
            tique += 'Cond. Venta: ' + condicionVenta + '\n';
            tique += '-----------------------------\n';
            tique += items;
            tique += '-----------------------------\n';
            tique += '<div style="text-align:right">SUBTOTAL: $' + subtotal + '</div>\n';
            tique += '<div style="text-align:right">IVA: $' + ivaTotal + '</div>\n';
            tique += '<div style="text-align:right"><strong>TOTAL: $' + totalGeneral + '</strong></div>\n';
            tique +=   registradora + '\n';


            mostrarTiqueModalSinQR(tique);
        }

        // Modal para mostrar tique solo texto (sin QR)
        function mostrarTiqueModalSinQR(tique) {
            let modal = document.getElementById('tiqueModalSinQR');
            if (!modal) {
                modal = document.createElement('div');
                modal.id = 'tiqueModalSinQR';
                modal.className = 'modal fade';
                modal.tabIndex = -1;
                modal.innerHTML = `
                    <style>
                        @media print {
                            body * { visibility: hidden !important; }
                            #tiqueModalSinQR, #tiqueModalSinQR * { visibility: visible !important; }
                            #tiqueModalSinQR { position: absolute; left: 0; top: 0; width: 80mm !important; min-width: 80mm !important; max-width: 80mm !important; }
                            .modal-content { box-shadow: none !important; border: none !important; }
                            .modal-header, .modal-footer, .btn, .btn-close { display: none !important; }
                        }
                        #tiqueModalSinQR .modal-dialog { max-width: 80mm; min-width: 80mm; }
                        #tiqueModalSinQR .modal-content { font-family: monospace; font-size: 14px; padding: 8px; }
                        #tiqueTextoSinQR { font-family: monospace; font-size: 14px; white-space: pre-wrap; }
                    </style>
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Tique 80mm (texto)</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div id="tiqueTextoSinQR"></div> <!-- Cambiado de <pre> a <div> -->
                            </div>
                            <div class="modal-footer d-flex justify-content-end gap-2">
                                <button type="button" class="btn btn-primary" onclick="imprimirSoloTique()">Imprimir tique</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar tique</button>
                            </div>
                        </div>
                    </div>
                `;
                document.body.appendChild(modal);
            }

            // Usar textContent en lugar de innerHTML para evitar que contenido dinámico
            // (posiblemente proveniente del usuario) sea interpretado como HTML/JS.
            // `tique` es texto plano formateado: asignarlo a textContent evita XSS.
            const tiqueTextEl = document.getElementById('tiqueTextoSinQR');
            if (tiqueTextEl) {
                // Si se desea conservar saltos y formato, usar un <pre> en la plantilla del modal.
                tiqueTextEl.textContent = tique;
            }
            let bsModal = bootstrap.Modal.getOrCreateInstance(modal);
            bsModal.show();
            // Re-use imprimirSoloTique
            window.imprimirSoloTique = function() { window.print(); };
        }

        // Generar QR usando qrious
        function generarQR(data) {
            let canvas = document.getElementById('qrTique');
            if (canvas) {
                if (window.QRious) {
                    new QRious({ element: canvas, value: data, size: 120 });
                } else {
                    // Cargar la librería si no está
                    let script = document.getElementById('qriousScript');
                    if (!script) {
                        script = document.createElement('script');
                        script.id = 'qriousScript';
                        script.src = 'https://cdn.jsdelivr.net/npm/qrious@4.0.2/dist/qrious.min.js';
                        script.onload = function() {
                            new QRious({ element: canvas, value: data, size: 120 });
                        };
                        document.body.appendChild(script);
                    } else {
                        script.onload = function() {
                            new QRious({ element: canvas, value: data, size: 120 });
                        };
                    }
                }
            }
        }
        // Control para habilitar/deshabilitar acciones en detalles
        // Eliminado control de bloqueo de edición. Todos los controles estarán siempre habilitados.

    // Use a global variable to store the initial suggested invoice number from PHP
    let nextSuggestedInvoiceNumber = "<?php echo $next_invoice_number_formatted; ?>";
    let invoicePrefix = "00012-";
    // Control global para permitir/modificar los detalles (siempre habilitado)
    let puedeModificarDetalles = true;

        /**
         * Actualiza el estado de los controles relacionados con los detalles (productos/servicios).
         * Esta función debe habilitar/deshabilitar botones dentro del modal de detalles y
         * actualizar cualquier indicador visual dependiendo de `puedeModificarDetalles`.
         * Se implementa de forma defensiva para evitar ReferenceErrors cuando se llama.
         */
        function actualizarEstadoDetalles() {
            try {
                // Mantener siempre habilitado el botón "Nuevo Producto/Servicio" dentro del modal de detalles
                const nuevoBtn = document.querySelector('#detalleModal .btn.btn-primary');
                if (nuevoBtn) nuevoBtn.disabled = false;

                // Habilitar siempre Editar y Eliminar en la lista de detalles
                const filas = document.querySelectorAll('#detallesTableBody tr');
                filas.forEach(fila => {
                    const btnEditar = fila.querySelector('button[onclick^="editDetalle"]');
                    const btnEliminar = fila.querySelector('button[onclick^="deleteDetalle"]');
                    if (btnEditar) btnEditar.disabled = false;
                    if (btnEliminar) btnEliminar.disabled = false;
                });

                // Si el modal de detalles está abierto, recargar la lista para reflejar cambios
                const detalleModalEl = document.getElementById('detalleModal');
                if (detalleModalEl && detalleModalEl.classList.contains('show')) {
                    loadDetalles();
                }
            } catch (e) {
                console.warn('actualizarEstadoDetalles: error defensivo', e);
            }
        }

        function calcularTotal(fila) {
            const cantidad = parseFloat(fila.querySelector('input[name="cantidad[]"]').value) || 0;
            const precioInput = fila.querySelector('input[name="precio_unitario[]"]');
            const precio = parseFloat(precioInput.value) || 0;
            const total = cantidad * precio;

            // Actualizar el total de la fila
            const totalCell = fila.querySelector('.total-item');
            totalCell.textContent = total.toFixed(2);

            calcularTotales();
        }

        function calcularTotales() {
            let subtotal = 0;

            // Sumar todos los totales de las filas
            document.querySelectorAll('.total-item').forEach(cell => {
                const valor = parseFloat(cell.textContent) || 0;
                subtotal += valor;
            });

            // Calcular IVA según la condición del cliente
            const clienteIva = document.getElementById('clienteIva').value;
            let iva = 0;

            if (clienteIva === 'Resp. Inscripto') {
                iva = subtotal * 0.21; // 21% de IVA
            } else if (clienteIva === 'Monotributo') {
                iva = subtotal * 0.105; // 10.5% de IVA para monotributo
            }
            // Para Consumidor Final y Exento, IVA = 0

            const total = subtotal + iva;

            // Actualizar todos los campos de totales
            document.getElementById('subtotal').textContent = formatearNumero(subtotal);
            document.getElementById('subtotal2').textContent = formatearNumero(subtotal);
            document.getElementById('impuestos').textContent = formatearNumero(0); // Otros impuestos
            document.getElementById('ivaTotal').textContent = formatearNumero(iva);
            document.getElementById('totalGeneral').textContent = formatearNumero(total);
        }

        function formatearNumero(numero) {
            return numero.toLocaleString('es-AR', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            });
        }

        // Convierte fechas a formato DD/MM/YYYY cuando vienen como YYYY-MM-DD o YYYY/MM/DD.
        // Si ya está en DD/MM/YYYY u otro formato desconocido, lo deja igual.
        function aFechaDMY(valor) {
            if (!valor) return '';
            // Ya en DD/MM/YYYY
            if (/^\d{2}\/\d{2}\/\d{4}$/.test(valor)) return valor;
            // Detecta YYYY-MM-DD o YYYY/MM/DD
            const m = valor.match(/^(\d{4})[-\/](\d{2})[-\/](\d{2})$/);
            if (m) {
                const [, y, mo, d] = m;
                return `${d}/${mo}/${y}`;
            }
            return valor;
        }

        function nuevaFactura() {
            // Increment the counter for the invoice number
            let currentNum = parseInt(nextSuggestedInvoiceNumber, 10);
            currentNum++;
            nextSuggestedInvoiceNumber = String(currentNum).padStart(8, '0');
            document.getElementById('numeroFactura').value = invoicePrefix + nextSuggestedInvoiceNumber;

            limpiarFactura();
            puedeModificarDetalles = true;
            actualizarEstadoDetalles();
            // Habilitar todos los inputs y selects por si algún otro bloqueo quedó
            const inputs = document.querySelectorAll('input, textarea, select');
            inputs.forEach(input => {
                input.disabled = false;
            });
            document.getElementById('fecha').value = new Date().toISOString().split('T')[0];

            // Reset company and client selection
            document.getElementById('selectedEmpresaId').value = '';
            document.getElementById('selectedClienteId').value = '';

            // Restore default company display
            document.getElementById('displayEmpresaNombre').textContent = 'ROSA RUBEN';
            document.getElementById('displayEmpresaDireccion').textContent = 'AV EL LIBERTADOR N°829';
            document.getElementById('displayEmpresaCP').textContent = '3366';
            document.getElementById('displayEmpresaLocalidad').textContent = 'MISIONES';
            document.getElementById('displayEmpresaTipoContribuyente').textContent = 'RESPONSABLE INSCRIPTO';
            document.getElementById('empresaCuit').value = '20125027378';
            document.getElementById('empresaIB').value = ' C.M.: 905-302000-1';
            document.getElementById('inicioActividad').value = '01/04/2021';
            document.getElementById('displayRegistradoraFiscal').textContent = 'CF HAB5503059';
            document.getElementById('displayFechaVencimientoCAI').textContent = '12/05/25';

            const barcodeSvgElement = document.querySelector(".barcode-svg"); // Obtener el elemento SVG
            if (barcodeSvgElement) { // Verificar si el elemento existe
                JsBarcode(barcodeSvgElement, "20309038682010012501722067317062230420258", {
                    format: "code128",
                    width: 1.0,
                    height: 30,
                    textMargin: 0,
                    fontOptions: "bold"
                }).init();
            }
        }

        function limpiarFactura() {
            // Keep the generated invoice number, just clear other fields
            document.getElementById('clienteNombre').value = '';
            document.getElementById('clienteDomicilio').value = '';
            document.getElementById('localidad').value = '';
            document.getElementById('clienteCuit').value = '';
            document.getElementById('clienteIva').value = 'Consumidor Final';
            document.getElementById('condicionVenta').value = 'Contado';

            // Clear all editable fields for items
            const tbody = document.getElementById('itemsTableBody');
            tbody.innerHTML = ''; // Clear all existing rows

            // Add back 6 empty rows
            for (let i = 0; i < 6; i++) {
                agregarFila();
            }

            // Restablecer totales a 0
            calcularTotales();
        }

        function agregarFila() {
            const tbody = document.getElementById('itemsTableBody');
            const nuevaFila = document.createElement('tr');
            nuevaFila.className = 'empty-row';

            // Cantidad
            const tdCantidad = document.createElement('td');
            const inputCantidad = document.createElement('input');
            inputCantidad.type = 'number';
            inputCantidad.className = 'editable';
            inputCantidad.name = 'cantidad[]';
            inputCantidad.step = '1';
            inputCantidad.value = '';
            inputCantidad.addEventListener('change', function() { calcularTotal(this.closest('tr')); });
            tdCantidad.appendChild(inputCantidad);

            // Detalle
            const tdDetalle = document.createElement('td');
            const inputDetalle = document.createElement('input');
            inputDetalle.type = 'text';
            inputDetalle.className = 'editable';
            inputDetalle.name = 'detalle[]';
            inputDetalle.style.width = '100%';
            tdDetalle.appendChild(inputDetalle);

            // Precio unitario
            const tdPrecio = document.createElement('td');
            const inputPrecio = document.createElement('input');
            inputPrecio.type = 'number';
            inputPrecio.className = 'editable';
            inputPrecio.name = 'precio_unitario[]';
            inputPrecio.step = '0.01';
            inputPrecio.style.textAlign = 'right';
            inputPrecio.addEventListener('change', function() { calcularTotal(this.closest('tr')); });
            tdPrecio.appendChild(inputPrecio);

            // Total
            const tdTotal = document.createElement('td');
            tdTotal.className = 'total-item';
            tdTotal.style.textAlign = 'right';
            tdTotal.textContent = '0.00';

            nuevaFila.appendChild(tdCantidad);
            nuevaFila.appendChild(tdDetalle);
            nuevaFila.appendChild(tdPrecio);
            nuevaFila.appendChild(tdTotal);

            tbody.appendChild(nuevaFila);
        }

        function imprimirFactura() {
            // Ocultar elementos de gestión antes de imprimir
            document.querySelector('.management-panel').style.display = 'none';
            document.querySelector('.text-center.mt-3').style.display = 'none';

            window.print();

            // Restaurar elementos después de imprimir
            setTimeout(() => {
                document.querySelector('.management-panel').style.display = 'block';
                document.querySelector('.text-center.mt-3').style.display = 'block';
            }, 100);
        }

        function exportarPDF() {
            // Obtener el ID de la empresa seleccionada
            const empresaId = document.getElementById('selectedEmpresaId').value;
            
            // Verificar si hay una empresa seleccionada
            if (!empresaId) {
                alert('Por favor, seleccione una empresa primero.');
                return;
            }

            // Verificar si hay cambios sin guardar
            const hayInformacionSinGuardar = document.getElementById('invoiceForm').dataset.modificado === 'true';
            if (hayInformacionSinGuardar) {
                if (!confirm('Hay cambios sin guardar. ¿Desea guardar la factura antes de exportar a PDF?')) {
                    return;
                }
                // Guardar la factura primero
                document.getElementById('invoiceForm').submit();
                return;
            }

            // Obtener el ID de la factura actual
            const facturaId = document.getElementById('listaFacturas').value;
            if (!facturaId) {
                alert('Por favor, guarde la factura primero o seleccione una factura existente.');
                return;
            }

            // Redirigir a imprimir_factura_pdf.php con el ID de la factura
            try {
                window.open('imprimir_factura_pdf.php?factura_id=' + encodeURIComponent(facturaId), '_blank');
            } catch (error) {
                console.error('Error al abrir el PDF:', error);
                alert('Error al generar el PDF. Por favor, inténtelo de nuevo.');
            }
        }

        // Búsqueda de facturas
        document.getElementById('buscarFactura').addEventListener('input', function() {
            const termino = this.value.toLowerCase();
            const select = document.getElementById('listaFacturas');

            for (let i = 0; i < select.options.length; i++) {
                const option = select.options[i];
                if (option.textContent.toLowerCase().includes(termino)) {
                    option.style.display = 'block';
                } else {
                    option.style.display = 'none';
                }
            }
        });

        // Event listeners para recalcular totales automáticamente
        document.getElementById('clienteIva').addEventListener('change', calcularTotales);

        // Funciones para gestión de Rubros
        function loadRubros() {
            fetch('get_rubros.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('rubrosTableBody');
                    const select = document.getElementById('detalleRubroCrud');

                    // Limpiar tabla y select
                    tbody.innerHTML = '';
                    select.innerHTML = '<option value="">Seleccione un rubro...</option>';

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(rubro => {
                            // Agregar a la tabla
                            const tr = document.createElement('tr');
                            const tdNombre = document.createElement('td');
                            const tdDesc = document.createElement('td');
                            const tdAcciones = document.createElement('td');

                            tdNombre.textContent = rubro.nombre;
                            tdDesc.textContent = rubro.descripcion || '';

                            const btnEdit = document.createElement('button');
                            btnEdit.type = 'button';
                            btnEdit.className = 'btn btn-info btn-sm';
                            btnEdit.textContent = 'Editar';
                            btnEdit.onclick = () => editRubro(rubro.id);

                            const btnDelete = document.createElement('button');
                            btnDelete.type = 'button';
                            btnDelete.className = 'btn btn-danger btn-sm ms-1';
                            btnDelete.textContent = 'Eliminar';
                            btnDelete.onclick = () => deleteRubro(rubro.id);

                            tdAcciones.appendChild(btnEdit);
                            tdAcciones.appendChild(btnDelete);

                            tr.appendChild(tdNombre);
                            tr.appendChild(tdDesc);
                            tr.appendChild(tdAcciones);
                            tbody.appendChild(tr);

                            // Agregar al select
                            const option = document.createElement('option');
                            option.value = rubro.id;
                            option.textContent = rubro.nombre;
                            select.appendChild(option);
                        });
                    } else {
                        const tr = document.createElement('tr');
                        const td = document.createElement('td');
                        td.colSpan = 3;
                        td.textContent = 'No hay rubros registrados';
                        tr.appendChild(td);
                        tbody.appendChild(tr);
                    }
                })
                .catch(error => {
                    console.error('Error al cargar rubros:', error);
                    alert('Error al cargar rubros');
                });
        }

        function showRubroForm(isEdit = false) {
            document.getElementById('rubroFormContainer').style.display = 'block';
            document.getElementById('rubroListContainer').style.display = 'none';
            document.getElementById('rubroFormTitle').textContent = isEdit ? 'Editar' : 'Nuevo';
            if (!isEdit) {
                document.getElementById('rubroCrudForm').reset();
                document.getElementById('rubroIdCrud').value = '';
            }
        }

        function hideRubroForm() {
            document.getElementById('rubroFormContainer').style.display = 'none';
            document.getElementById('rubroListContainer').style.display = 'block';
            document.getElementById('rubroCrudForm').reset();
            document.getElementById('rubroIdCrud').value = '';
            loadRubros();
        }

        function openRubroModal() {
            const rubroModal = new bootstrap.Modal(document.getElementById('rubroModal'));
            rubroModal.show();
            loadRubros();
        }

        function editRubro(id) {
            fetch(`get_rubro.php?id=${id}`)
                .then(response => response.json())
                .then(rubro => {
                    if (rubro.error) {
                        alert(rubro.error);
                        return;
                    }
                    document.getElementById('rubroIdCrud').value = rubro.id;
                    document.getElementById('rubroNombreCrud').value = rubro.nombre;
                    document.getElementById('rubroDescripcionCrud').value = rubro.descripcion || '';
                    showRubroForm(true);
                })
                .catch(error => {
                    console.error('Error al editar rubro:', error);
                    alert('Error al editar rubro');
                });
        }

        function saveRubro() {
            const formData = new FormData(document.getElementById('rubroCrudForm'));
            fetch('save_rubro.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Rubro guardado exitosamente');
                    hideRubroForm();
                    loadRubros();
                } else {
                    alert('Error al guardar rubro: ' + (data.error || 'Error desconocido'));
                }
            })
            .catch(error => {
                console.error('Error al guardar rubro:', error);
                alert('Error al guardar rubro');
            });
        }

        function deleteRubro(id) {
            if (!confirm('¿Está seguro de que desea eliminar este rubro?')) return;

            fetch(`delete_rubro.php?id=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Rubro eliminado exitosamente');
                        loadRubros();
                    } else {
                        alert('Error al eliminar rubro: ' + (data.error || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar rubro:', error);
                    alert('Error al eliminar rubro');
                });
        }

        // Cargar rubros cuando se abre el modal de detalles
        document.getElementById('detalleModal').addEventListener('shown.bs.modal', function () {
            loadRubros();
        });

        // Global variables for modals
        let empresaModal = new bootstrap.Modal(document.getElementById('empresaModal'));
        let clienteModal = new bootstrap.Modal(document.getElementById('clienteModal'));

        // --- Empresa CRUD Functions ---

        function loadEmpresas() {
            // Populate tipo_fac select options (static set)
            const tipos = ['Tique', 'Electrónica', 'Offline', 'Modelo PDF'];
            const filtroSelect = document.getElementById('filtroEmpresaTipoFac');
            const empresaTipoSelect = document.getElementById('empresaTipoFacCrud');
            if (filtroSelect && empresaTipoSelect) {
                // fill both selects only if empty
                if (!filtroSelect.dataset.filled) {
                    tipos.forEach(t => {
                        const opt = document.createElement('option'); opt.value = t; opt.textContent = t; filtroSelect.appendChild(opt);
                    });
                    filtroSelect.dataset.filled = 'true';
                }
                if (!empresaTipoSelect.dataset.filled) {
                    tipos.forEach(t => {
                        const opt = document.createElement('option'); opt.value = t; opt.textContent = t; empresaTipoSelect.appendChild(opt);
                    });
                    empresaTipoSelect.dataset.filled = 'true';
                }
            }

            // Apply tipo_fac filter in fetch
            let url = 'get_empresas.php';
            const filtroTipo = document.getElementById('filtroEmpresaTipoFac');
            if (filtroTipo && filtroTipo.value) {
                url += '?tipo_fac=' + encodeURIComponent(filtroTipo.value);
            }
            fetch(url)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('empresasTableBody');
                    tbody.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(empresa => {
                            const tr = document.createElement('tr');
                            const tdNombre = document.createElement('td'); tdNombre.textContent = empresa.nombre || '';
                            const tdCuit = document.createElement('td'); tdCuit.textContent = empresa.cuit || '';
                            const tdTipo = document.createElement('td'); tdTipo.textContent = empresa.tipo_fac || '';
                            const tdActividad = document.createElement('td'); tdActividad.textContent = empresa.actividad || '';
                            const tdAcciones = document.createElement('td');

                            const btnSelect = document.createElement('button');
                            btnSelect.type = 'button'; btnSelect.className = 'btn btn-success btn-sm'; btnSelect.textContent = 'Seleccionar';
                            btnSelect.addEventListener('click', () => selectEmpresa(empresa.id));

                            const btnEdit = document.createElement('button');
                            btnEdit.type = 'button'; btnEdit.className = 'btn btn-info btn-sm'; btnEdit.textContent = 'Editar';
                            btnEdit.addEventListener('click', () => editEmpresa(empresa.id));

                            const btnDelete = document.createElement('button');
                            btnDelete.type = 'button'; btnDelete.className = 'btn btn-danger btn-sm'; btnDelete.textContent = 'Eliminar';
                            btnDelete.addEventListener('click', () => deleteEmpresa(empresa.id));

                            tdAcciones.appendChild(btnSelect);
                            tdAcciones.appendChild(document.createTextNode(' '));
                            tdAcciones.appendChild(btnEdit);
                            tdAcciones.appendChild(document.createTextNode(' '));
                            tdAcciones.appendChild(btnDelete);

                            tr.appendChild(tdNombre);
                            tr.appendChild(tdCuit);
                            tr.appendChild(tdTipo);
                            tr.appendChild(tdActividad);
                            tr.appendChild(tdAcciones);
                            tbody.appendChild(tr);
                        });
                    } else {
                        const tr = document.createElement('tr');
                        const td = document.createElement('td'); td.colSpan = 4; td.textContent = 'No hay empresas registradas.';
                        tr.appendChild(td); tbody.appendChild(tr);
                    }
                })
                .catch(error => console.error('Error al cargar empresas:', error));
        }

        function showEmpresaForm(isEdit = false) {
            document.getElementById('empresaFormContainer').style.display = 'block';
            document.getElementById('empresaListContainer').style.display = 'none';
            document.getElementById('empresaFormTitle').textContent = isEdit ? 'Editar' : 'Nueva';
            if (!isEdit) { // Clear form for new entry
                document.getElementById('empresaCrudForm').reset();
                document.getElementById('empresaIdCrud').value = '';
            }
            // Poner foco en el primer campo para mejorar la experiencia
            setTimeout(() => {
                const nombreEl = document.getElementById('empresaNombreCrud');
                if (nombreEl) nombreEl.focus();
            }, 50);
        }

        function hideEmpresaForm() {
            document.getElementById('empresaFormContainer').style.display = 'none';
            document.getElementById('empresaListContainer').style.display = 'block';
            document.getElementById('empresaCrudForm').reset(); // Clear form
            document.getElementById('empresaIdCrud').value = ''; // Clear ID
            loadEmpresas(); // Reload list
        }

        function selectEmpresa(id) {
            fetch(`get_empresa.php?id=${id}`)
                .then(response => response.json())
                .then(empresa => {
                    if (empresa.error) {
                        alert(empresa.error);
                        return;
                    }
                    document.getElementById('selectedEmpresaId').value = empresa.id;
                    document.getElementById('displayEmpresaNombre').textContent = empresa.nombre;
                    document.getElementById('displayEmpresaDireccion').textContent = empresa.direccion;
                    document.getElementById('displayEmpresaCP').textContent = empresa.codigo_postal;
                    document.getElementById('displayEmpresaLocalidad').textContent = empresa.localidad || 'MISIONES'; // Default if not specified in DB
                    document.getElementById('displayEmpresaTipoContribuyente').textContent = empresa.tipo_contribuyente;

                    document.getElementById('empresaCuit').value = empresa.cuit;
                    document.getElementById('empresaIB').value = empresa.ingresos_brutos;
                    document.getElementById('inicioActividad').value = aFechaDMY(empresa.inicio_actividad);

                    // Update barcode and CAI info
                    let codigoBarra = empresa.codigo_barra_cai;
                    if (!codigoBarra || codigoBarra === 'null' || codigoBarra === null) {
                        codigoBarra = "20309038682010012501722067317062230420258";
                    }
                    // Actualizar el atributo jsbarcode-value del SVG
                    const barcodeSvg = document.querySelector('.barcode-svg');
                    if (barcodeSvg) {
                        barcodeSvg.setAttribute('jsbarcode-value', codigoBarra);
                        barcodeSvg.innerHTML = '';
                    }
                    JsBarcode(".barcode-svg", {
                        value: codigoBarra,
                        format: "code128",
                        width: 1.0,
                        height: 30,
                        textMargin: 0,
                        fontOptions: "bold"
                    }).init();
                    document.getElementById('displayRegistradoraFiscal').textContent = empresa.registradora_fiscal;
                    document.getElementById('displayFechaVencimientoCAI').textContent = empresa.fecha_vencimiento_cai;

                    empresaModal.hide();
                })
                .catch(error => console.error('Error al seleccionar empresa:', error));
        }

        function editEmpresa(id) {
            fetch(`get_empresa.php?id=${id}`)
                .then(response => response.json())
                .then(empresa => {
                    if (empresa.error) {
                        alert(empresa.error);
                        return;
                    }
                    document.getElementById('empresaIdCrud').value = empresa.id;
                    document.getElementById('empresaNombreCrud').value = empresa.nombre;
                    document.getElementById('empresaDireccionCrud').value = empresa.direccion;
                    document.getElementById('empresaCodigoPostalCrud').value = empresa.codigo_postal;
                    document.getElementById('empresaTipoContribuyenteCrud').value = empresa.tipo_contribuyente;
                    document.getElementById('empresaActividadCrud').value = empresa.actividad; // Añadir esta línea
                    document.getElementById('empresaCuitCrud').value = empresa.cuit;
                    document.getElementById('empresaIngresosBrutosCrud').value = empresa.ingresos_brutos;
                    document.getElementById('empresaInicioActividadCrud').value = empresa.inicio_actividad;
                    document.getElementById('empresaRegistradoraFiscalCrud').value = empresa.registradora_fiscal;
                    document.getElementById('empresaCodigoBarraCaiCrud').value = empresa.codigo_barra_cai;
                    document.getElementById('empresaFechaVencimientoCaiCrud').value = empresa.fecha_vencimiento_cai;
                    document.getElementById('modelo_pdf_actual').value = empresa.modelo_pdf;
                    // Tipo de factura
                    document.getElementById('empresaTipoFacCrud').value = empresa.tipo_fac || '';

                    // Mostrar el formulario en modo edición dentro del modal
                    showEmpresaForm(true);
                })
                .catch(error => console.error('Error al editar empresa:', error));
        }

        function saveEmpresa() {
            const form = document.getElementById('empresaCrudForm');
            const btn = form.querySelector('button.btn-success');
            if (btn) btn.disabled = true;

            const formData = new FormData(form);

            // Validar datos requeridos
            if (!formData.get('nombre') || !formData.get('tipo_contribuyente') || !formData.get('cuit')) {
                alert('Por favor complete los campos requeridos: Nombre, Tipo de Contribuyente y CUIT');
                if (btn) btn.disabled = false;
                return;
            }

            fetch('save_empresa.php', {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                // Asegurarse de que sea JSON válido
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new TypeError("La respuesta del servidor no es JSON");
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    alert('Empresa guardada exitosamente.');
                    hideEmpresaForm();
                    loadEmpresas(); // Recargar la lista de empresas
                } else {
                    throw new Error(data.error || 'Error desconocido al guardar la empresa');
                }
            })
            .catch(error => {
                console.error('Error al guardar empresa:', error);
                alert('Error al guardar empresa: ' + error.message);
            })
            .finally(() => {
                if (btn) btn.disabled = false;
            });
        }

        function deleteEmpresa(id) {
            if (confirm('¿Estás seguro de que quieres eliminar esta empresa?')) {
                fetch(`delete_empresa.php?id=${id}`, { method: 'GET' }) // For simplicity, GET is used here
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Empresa eliminada exitosamente.');
                            loadEmpresas();
                        } else {
                            alert('Error al eliminar empresa: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error al eliminar empresa:', error));
            }
        }

        // --- Cliente CRUD Functions ---

        function loadClientes() {
            fetch('get_clientes.php')
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('clientesTableBody');
                    tbody.innerHTML = '';
                    // Si la respuesta es un objeto con propiedad 'clientes', úsala
                    if (Array.isArray(data)) {
                        if (data.length > 0) {
                            data.forEach(cliente => {
                                const tr = document.createElement('tr');
                                const tdNombre = document.createElement('td'); tdNombre.textContent = cliente.nombre || '';
                                const tdCuit = document.createElement('td'); tdCuit.textContent = cliente.cuit || '';
                                const tdAcciones = document.createElement('td');

                                const btnSelect = document.createElement('button');
                                btnSelect.type = 'button'; btnSelect.className = 'btn btn-success btn-sm'; btnSelect.textContent = 'Seleccionar';
                                btnSelect.addEventListener('click', () => selectCliente(cliente.id));

                                const btnEdit = document.createElement('button');
                                btnEdit.type = 'button'; btnEdit.className = 'btn btn-info btn-sm'; btnEdit.textContent = 'Editar';
                                btnEdit.addEventListener('click', () => editCliente(cliente.id));

                                const btnDelete = document.createElement('button');
                                btnDelete.type = 'button'; btnDelete.className = 'btn btn-danger btn-sm'; btnDelete.textContent = 'Eliminar';
                                btnDelete.addEventListener('click', () => deleteCliente(cliente.id));

                                tdAcciones.appendChild(btnSelect);
                                tdAcciones.appendChild(document.createTextNode(' '));
                                tdAcciones.appendChild(btnEdit);
                                tdAcciones.appendChild(document.createTextNode(' '));
                                tdAcciones.appendChild(btnDelete);

                                tr.appendChild(tdNombre);
                                tr.appendChild(tdCuit);
                                tr.appendChild(tdAcciones);
                                tbody.appendChild(tr);
                            });
                        } else {
                            const tr = document.createElement('tr'); const td = document.createElement('td'); td.colSpan = 3; td.textContent = 'No hay clientes registrados.'; tr.appendChild(td); tbody.appendChild(tr);
                        }
                    } else if (data && Array.isArray(data.clientes)) {
                        // Si la API devuelve {clientes: [...]}
                        if (data.clientes.length > 0) {
                            data.clientes.forEach(cliente => {
                                const tr = document.createElement('tr');
                                const tdNombre = document.createElement('td'); tdNombre.textContent = cliente.nombre || '';
                                const tdCuit = document.createElement('td'); tdCuit.textContent = cliente.cuit || '';
                                const tdAcciones = document.createElement('td');

                                const btnSelect = document.createElement('button');
                                btnSelect.type = 'button'; btnSelect.className = 'btn btn-success btn-sm'; btnSelect.textContent = 'Seleccionar';
                                btnSelect.addEventListener('click', () => selectCliente(cliente.id));

                                const btnEdit = document.createElement('button');
                                btnEdit.type = 'button'; btnEdit.className = 'btn btn-info btn-sm'; btnEdit.textContent = 'Editar';
                                btnEdit.addEventListener('click', () => editCliente(cliente.id));

                                const btnDelete = document.createElement('button');
                                btnDelete.type = 'button'; btnDelete.className = 'btn btn-danger btn-sm'; btnDelete.textContent = 'Eliminar';
                                btnDelete.addEventListener('click', () => deleteCliente(cliente.id));

                                tdAcciones.appendChild(btnSelect);
                                tdAcciones.appendChild(document.createTextNode(' '));
                                tdAcciones.appendChild(btnEdit);
                                tdAcciones.appendChild(document.createTextNode(' '));
                                tdAcciones.appendChild(btnDelete);

                                tr.appendChild(tdNombre);
                                tr.appendChild(tdCuit);
                                tr.appendChild(tdAcciones);
                                tbody.appendChild(tr);
                            });
                        } else {
                            const tr = document.createElement('tr'); const td = document.createElement('td'); td.colSpan = 3; td.textContent = 'No hay clientes registrados.'; tr.appendChild(td); tbody.appendChild(tr);
                        }
                    } else {
                        const tr = document.createElement('tr'); const td = document.createElement('td'); td.colSpan = 3; td.textContent = 'No hay clientes registrados.'; tr.appendChild(td); tbody.appendChild(tr);
                    }
                    filtrarClientesPorNombre();
                })
                .catch(error => {
                    const tbody = document.getElementById('clientesTableBody');
                    tbody.innerHTML = '<tr><td colspan="3">Error al cargar clientes.</td></tr>';
                    console.error('Error al cargar clientes:', error);
                });
        }

        function showClienteForm(isEdit = false) {
            document.getElementById('clienteFormContainer').style.display = 'block';
            document.getElementById('clienteListContainer').style.display = 'none';
            document.getElementById('clienteFormTitle').textContent = isEdit ? 'Editar' : 'Nuevo';
            if (!isEdit) { // Clear form for new entry
                document.getElementById('clienteCrudForm').reset();
                document.getElementById('clienteIdCrud').value = '';
            }
        }

        function hideClienteForm() {
            document.getElementById('clienteFormContainer').style.display = 'none';
            document.getElementById('clienteListContainer').style.display = 'block';
            document.getElementById('clienteCrudForm').reset();
            document.getElementById('clienteIdCrud').value = '';
            loadClientes();
        }

        function selectCliente(id) {
            fetch(`get_cliente.php?id=${id}`)
                .then(response => response.json())
                .then(cliente => {
                    if (cliente.error) {
                        alert(cliente.error);
                        return;
                    }
                    // Populate main invoice fields
                    document.getElementById('selectedClienteId').value = cliente.id;
                    document.getElementById('clienteNombre').value = cliente.nombre;
                    document.getElementById('clienteDomicilio').value = cliente.domicilio;
                    document.getElementById('localidad').value = cliente.localidad;
                    document.getElementById('clienteCuit').value = cliente.cuit;
                    document.getElementById('clienteIva').value = cliente.tipo_iva;


                    document.getElementById('condicionVenta').value = cliente.condicion_venta_default;
                    calcularTotales(); // Recalculate totals as IVA type might change
                    clienteModal.hide(); // Close the modal
                })
                .catch(error => console.error('Error al seleccionar cliente:', error));
        }

        function editCliente(id) {
            fetch(`get_cliente.php?id=${id}`)
                .then(response => response.json())
                .then(cliente => {
                    if (cliente.error) {
                        alert(cliente.error);
                        return;
                    }
                    document.getElementById('clienteIdCrud').value = cliente.id;
                    document.getElementById('clienteNombreCrud').value = cliente.nombre;
                    document.getElementById('clienteDomicilioCrud').value = cliente.domicilio;
                    document.getElementById('clienteLocalidadCrud').value = cliente.localidad;
                    document.getElementById('clienteTipoIvaCrud').value = cliente.tipo_iva;
                    document.getElementById('clienteCuitCrud').value = cliente.cuit;
                    document.getElementById('clienteCondicionVentaDefaultCrud').value = cliente.condicion_venta_default;
                    showClienteForm(true);
                })
                .catch(error => console.error('Error al editar cliente:', error));
        }

        function saveCliente() {
            const formData = new FormData(document.getElementById('clienteCrudForm'));
            fetch('save_cliente.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Cliente guardado exitosamente.');
                    hideClienteForm();
                } else {
                    alert('Error al guardar cliente: ' + data.error);
                }
            })
            .catch(error => console.error('Error al guardar cliente:', error));
        }

        function deleteCliente(id) {
            if (!confirm('¿Estás seguro de que quieres eliminar este cliente?')) return;

            fetch(`delete_cliente.php?id=${id}`, { method: 'GET' })
                .then(async response => {
                        if (!response.ok) {
                            // Intentar leer el cuerpo para obtener más información
                            let text = await response.text();
                            // Si es 409, mostrar un mensaje más específico
                            if (response.status === 409) {
                                try {
                                    const parsed = JSON.parse(text || '{}');
                                    throw new Error(parsed.error || 'Conflicto: el cliente tiene dependencias. Revise movimientos.');
                                } catch (e) {
                                    throw new Error(text ? text : 'Conflicto: el cliente tiene dependencias. Revise movimientos.');
                                }
                            }
                            try {
                                const parsed = JSON.parse(text || '{}');
                                throw new Error(parsed.error || `Error HTTP ${response.status}`);
                            } catch (e) {
                                throw new Error(text ? text : `Error HTTP ${response.status}`);
                            }
                        }
                    // OK - parsear JSON
                    return response.json();
                })
                .then(data => {
                    if (data && data.success) {
                                               alert('Cliente eliminado exitosamente.');
                        loadClientes();
                    } else {
                        alert('Error al eliminar cliente: ' + (data && data.error ? data.error : 'Respuesta inválida.'));
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar cliente:', error);
                    alert('Error al eliminar cliente: ' + (error.message || error));
                });
        }

        // --- Detalle CRUD Functions ---

        function loadDetalles() {
            // Cargar los rubros primero para el filtro y el select del formulario
            return fetch('get_rubros.php')
                .then(response => {
                    if (!response.ok) {
                        if (response.status === 404) {
                            return []; // Si no existe el archivo o la tabla, devolver array vacío
                        }
                        throw new Error(`HTTP ${response.status}`);
                    }
                    return response.json();
                })
                .then(rubros => {
                    const filtroRubro = document.getElementById('filtroRubro');
                    const selectRubro = document.getElementById('detalleRubroCrud');

                    // Limpiar ambos selects
                    filtroRubro.innerHTML = '<option value="">Todos los rubros</option>';
                    selectRubro.innerHTML = '<option value="">Seleccione un rubro...</option>';

                    if (Array.isArray(rubros) && rubros.length > 0) {
                        rubros.forEach(rubro => {
                            // Agregar al filtro
                            const optionFiltro = document.createElement('option');
                            optionFiltro.value = rubro.id;
                            optionFiltro.textContent = rubro.nombre;
                            filtroRubro.appendChild(optionFiltro);

                            // Agregar al select del formulario
                            const optionSelect = document.createElement('option');
                            optionSelect.value = rubro.id;
                            optionSelect.textContent = rubro.nombre;
                            selectRubro.appendChild(optionSelect);
                        });
                    }
                    
                    // Ahora cargar los detalles después de que los rubros estén cargados
                    return fetch('get_detalles.php');
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => { throw new Error(`HTTP ${response.status}: ${text}`); });
                    }
                    return response.json();
                })
                .then(data => {
                    const tbody = document.getElementById('detallesTableBody');
                    if (!tbody) {
                        console.error('detallesTableBody no encontrado en el DOM');
                        return;
                    }
                    tbody.innerHTML = ''; // Limpiar tabla actual

                    // Si la API devolvió un objeto con error, mostrarlo
                    if (data && typeof data === 'object' && !Array.isArray(data) && data.error) {
                        const tr = document.createElement('tr');
                        const td = document.createElement('td');
                        td.colSpan = 4; // 4 columnas: Descripción, Rubro, Precio, Acciones
                        td.textContent = 'Error: ' + String(data.error);
                        tr.appendChild(td);
                        tbody.appendChild(tr);
                        console.warn('get_detalles.php returned error:', data.error);
                        return;
                    }

                    // Verificar si los datos son un array y no está vacío
                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(detalle => {
                            const descripcion = detalle.descripcion || '';
                            const precio = detalle.precio || '0.00';
                            const id = detalle.id || '';
                            const rubroId = detalle.rubro_id || '';
                            const rubroNombre = detalle.rubro_nombre || 'Sin Rubro';

                            const tr = document.createElement('tr');
                            tr.setAttribute('data-rubro-id', rubroId);
                            // Agregar el ID como atributo de datos para el filtrado
                            tr.setAttribute('data-detalle-id', id);                            const tdDesc = document.createElement('td'); tdDesc.textContent = descripcion;
                            const tdRubro = document.createElement('td'); tdRubro.textContent = rubroNombre;
                            const tdPrecio = document.createElement('td'); tdPrecio.textContent = precio;
                            const tdAcc = document.createElement('td');

                            const btnSelect = document.createElement('button'); btnSelect.type = 'button'; btnSelect.className = 'btn btn-success btn-sm'; btnSelect.textContent = 'Seleccionar'; btnSelect.addEventListener('click', () => selectDetalle(id));
                            const btnEdit = document.createElement('button'); btnEdit.type = 'button'; btnEdit.className = 'btn btn-info btn-sm'; btnEdit.textContent = 'Editar'; btnEdit.addEventListener('click', () => editDetalle(id));
                            const btnDelete = document.createElement('button'); btnDelete.type = 'button'; btnDelete.className = 'btn btn-danger btn-sm'; btnDelete.textContent = 'Eliminar'; btnDelete.addEventListener('click', () => deleteDetalle(id));

                            tdAcc.appendChild(btnSelect); tdAcc.appendChild(document.createTextNode(' ')); tdAcc.appendChild(btnEdit); tdAcc.appendChild(document.createTextNode(' ')); tdAcc.appendChild(btnDelete);

                            tr.appendChild(tdDesc); tr.appendChild(tdRubro); tr.appendChild(tdPrecio); tr.appendChild(tdAcc);
                            tbody.appendChild(tr);
                        });
                    } else {
                        // Mostrar mensaje si no hay datos o si los datos no son un array
                        const tr = document.createElement('tr'); const td = document.createElement('td'); td.colSpan = 3; td.textContent = 'No hay productos/servicios registrados.'; tr.appendChild(td); tbody.appendChild(tr);
                        console.warn('La respuesta de get_detalles.php no es un array o está vacía:', data);
                    }
                    // Aplicar filtro inicial después de cargar
                    filtrarDetallesPorDescripcion();
                })
                .catch(error => {
                    // Manejar errores de red o de parsing JSON de forma segura
                    const tbody = document.getElementById('detallesTableBody');
                    if (tbody) {
                        const tr = document.createElement('tr');
                        const td = document.createElement('td');
                        td.colSpan = 3;
                        td.textContent = 'Error al cargar productos/servicios: ' + String(error.message);
                        tr.appendChild(td);
                        tbody.appendChild(tr);
                    }
                    console.error('Error al cargar detalles:', error);
                });
        }

        function showDetalleForm(isEdit = false) {
            document.getElementById('detalleFormContainer').style.display = 'block';
            document.getElementById('detalleListContainer').style.display = 'none';
            document.getElementById('detalleFormTitle').textContent = isEdit ? 'Editar' : 'Nuevo';
            if (!isEdit) { // Clear form for new entry
                document.getElementById('detalleCrudForm').reset();
                document.getElementById('detalleIdCrud').value = '';
            }
        }

        function hideDetalleForm() {
            document.getElementById('detalleFormContainer').style.display = 'none';
            document.getElementById('detalleListContainer').style.display = 'block';
            document.getElementById('detalleCrudForm').reset();
            document.getElementById('detalleIdCrud').value = '';
            loadDetalles();
        }

        function selectDetalle(id) {
            fetch(`get_detalle.php?id=${id}`)
                .then(response => response.json())
                .then(detalle => {
                    if (detalle.error) {
                        alert(detalle.error);
                        return;
                    }
                    // Find first empty row or add new row if needed
                    let emptyRow = findEmptyRow();
                    if (!emptyRow) {
                        agregarFila();
                        emptyRow = findEmptyRow();
                    }

                    // Fill the row with product details
                    emptyRow.querySelector('input[name="detalle[]"]').value = detalle.descripcion;
                    emptyRow.querySelector('input[name="precio_unitario[]"]').value = detalle.precio;
                    emptyRow.querySelector('input[name="cantidad[]"]').value = '1';
                    calcularTotal(emptyRow);

                    const detalleModal = bootstrap.Modal.getInstance(document.getElementById('detalleModal'));
                    detalleModal.hide(); // Close the modal
                })
                .catch(error => console.error('Error al seleccionar detalle:', error));
        }

        function findEmptyRow() {
            const rows = document.querySelectorAll('#itemsTableBody tr');
            for (let row of rows) {
                if (!row.querySelector('input[name="detalle[]"]').value) {
                    return row;
                }
            }
            return null;
        }

        function editDetalle(id) {
            fetch(`get_detalle.php?id=${id}`)
                .then(response => response.json())
                .then(detalle => {
                    if (detalle.error) {
                        alert(detalle.error);
                        return;
                    }
                    document.getElementById('detalleIdCrud').value = detalle.id;
                    document.getElementById('detalleDescripcionCrud').value = detalle.descripcion;
                    document.getElementById('detallePrecioCrud').value = detalle.precio;
                    showDetalleForm(true);
                })
                .catch(error => console.error('Error al editar detalle:', error));
        }

        function saveDetalle() {
            const formData = new FormData(document.getElementById('detalleCrudForm'));
            fetch('save_detalle.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Producto/Servicio guardado exitosamente.');
                    hideDetalleForm();
                } else {
                    alert('Error al guardar producto/servicio: ' + data.error);
                }
            })
            .catch(error => console.error('Error al guardar producto/servicio:', error));
        }

        function deleteDetalle(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este producto/servicio?')) {
                fetch(`delete_detalle.php?id=${id}`, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Producto/Servicio eliminado exitosamente.');
                            loadDetalles();
                        } else {
                            alert('Error al eliminar producto/servicio: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error al eliminar producto/servicio:', error));
            }
        }

        // --- Event Listeners and Initial Load ---

        document.getElementById('empresaModal').addEventListener('shown.bs.modal', function () {
            // Load tipo_fac options dynamically and then companies
            fetch('get_tipo_fac.php')
                .then(r => r.json())
                .then(types => {
                    const filtro = document.getElementById('filtroEmpresaTipoFac');
                    const tipoSel = document.getElementById('empresaTipoFacCrud');
                    if (filtro) {
                        // keep default
                    }
                    // fill both selects
                    if (Array.isArray(types)) {
                        types.forEach(t => {
                            if (!Array.from(filtro.options).some(o => o.value === t)) {
                                const opt = document.createElement('option'); opt.value = t; opt.textContent = t; filtro.appendChild(opt);
                            }
                            if (!Array.from(tipoSel.options).some(o => o.value === t)) {
                                const opt2 = document.createElement('option'); opt2.value = t; opt2.textContent = t; tipoSel.appendChild(opt2);
                            }
                        });
                    }
                    loadEmpresas();
                })
                .catch(e => { console.error('Error al cargar tipos de factura:', e); loadEmpresas(); });
            hideEmpresaForm(); // Ensure form is hidden on modal open inicialmente
            // Limpiar búsqueda y aplicar filtro tras cargar
            const inp = document.getElementById('buscarEmpresa');
            if (inp) inp.value = '';
            setTimeout(filtrarEmpresas, 100);
        });

        document.getElementById('clienteModal').addEventListener('shown.bs.modal', function () {
            loadClientes();
            hideClienteForm(); // Ensure form is hidden on modal open inicialmente
            // Limpiar búsqueda al abrir modal
            document.getElementById('buscarClienteNombre').value = '';
            // Esperar a que se carguen los clientes antes de filtrar
            setTimeout(filtrarClientesPorNombre, 100);
        });

        document.getElementById('buscarClienteNombre').addEventListener('input', filtrarClientesPorNombre);
        // Búsqueda por nombre o CUIT en empresas
        document.getElementById('buscarEmpresa').addEventListener('input', filtrarEmpresas);

        // Cargar productos/servicios al abrir el modal de detalles
        document.getElementById('detalleModal').addEventListener('shown.bs.modal', function () {
            loadDetalles();
            hideDetalleForm();
            // Mantener habilitado el botón de nuevo producto/servicio
            document.querySelector('#detalleModal .btn.btn-primary').disabled = false;
        });

        // Función de filtrado combinado por descripción y rubro
        function filtrarDetalles() {
            console.log('Iniciando filtrado...');
            const filtroDescripcion = document.getElementById('buscarDetalleDescripcion').value.toLowerCase();
            const filtroRubroId = document.getElementById('filtroRubro').value;
            console.log('Filtros:', { filtroDescripcion, filtroRubroId });

            const filas = document.querySelectorAll('#detallesTableBody tr');
            filas.forEach(fila => {
                // Si es una fila de mensaje (sin productos)
                if (fila.cells.length === 1 && fila.cells[0].getAttribute('colspan')) {
                    fila.style.display = 'none';
                    return;
                }

                const celdas = fila.querySelectorAll('td');
                if (celdas.length >= 4) { // Debe tener las 4 columnas
                    const descripcion = celdas[0].textContent.toLowerCase();
                    const rubroId = fila.getAttribute('data-rubro-id');
                    console.log('Fila:', { descripcion, rubroId });

                    const coincideDescripcion = !filtroDescripcion || descripcion.includes(filtroDescripcion);
                    const coincideRubro = !filtroRubroId || String(rubroId) === String(filtroRubroId);

                    console.log('Coincidencias:', { coincideDescripcion, coincideRubro });
                    fila.style.display = (coincideDescripcion && coincideRubro) ? '' : 'none';
                }
            });
        }

        // Event listeners para filtrado
        document.getElementById('buscarDetalleDescripcion').addEventListener('input', filtrarDetalles);
        document.getElementById('filtroRubro').addEventListener('change', filtrarDetalles);

        function filtrarDetallesPorDescripcion() {
            const filtro = document.getElementById('buscarDetalleDescripcion').value.toLowerCase();
            const filas = document.querySelectorAll('#detallesTableBody tr');
            filas.forEach(fila => {
                const descripcion = fila.querySelector('td') ? fila.querySelector('td').textContent.toLowerCase() : '';
                if (descripcion.includes(filtro) || filtro === '') {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        }

        function filtrarClientesPorNombre() {
            const filtro = document.getElementById('buscarClienteNombre').value.toLowerCase();
            const filas = document.querySelectorAll('#clientesTableBody tr');
            filas.forEach(fila => {
                // Solo filtra filas que tengan celdas (evita filas vacías)
                const celdas = fila.querySelectorAll('td');
                if (celdas.length > 0) {
                    const nombre = celdas[0].textContent.toLowerCase();
                    if (nombre.includes(filtro) || filtro === '') {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                } else {
                    // Si la fila no tiene celdas (por ejemplo, mensaje "No hay clientes registrados"), siempre mostrarla
                    fila.style.display = filtro === '' ? '' : 'none';
                }
            });
        }

        // Filtro de empresas por nombre o CUIT
        function filtrarEmpresas() {
            const input = document.getElementById('buscarEmpresa');
            if (!input) return;
            const filtro = input.value.toLowerCase();
            const filas = document.querySelectorAll('#empresasTableBody tr');
            filas.forEach(fila => {
                const celdas = fila.querySelectorAll('td');
                if (celdas.length > 0) {
                    const nombre = celdas[0] ? celdas[0].textContent.toLowerCase() : '';
                    const cuit = celdas[1] ? celdas[1].textContent.toLowerCase() : '';
                    const tipo = celdas[2] ? celdas[2].textContent.toLowerCase() : '';
                    const tipoFiltro = (document.getElementById('filtroEmpresaTipoFac') && document.getElementById('filtroEmpresaTipoFac').value) ? document.getElementById('filtroEmpresaTipoFac').value.toLowerCase() : '';
                    if ((filtro === '' || nombre.includes(filtro) || cuit.includes(filtro)) && (tipoFiltro === '' || tipo.includes(tipoFiltro))) {
                        fila.style.display = '';
                    } else {
                        fila.style.display = 'none';
                    }
                } else {
                    // Para filas de mensaje "No hay empresas registradas"
                    fila.style.display = filtro === '' ? '' : 'none';
                }
            });
        }

        // --- Utilidad para forzar backdrop estático en modales Bootstrap 5 ---
        function setStaticBackdrop(modalId) {
            const modalEl = document.getElementById(modalId);
            if (!modalEl) return;
            modalEl.addEventListener('show.bs.modal', function (event) {
                // Forzar backdrop estático
                const modal = bootstrap.Modal.getOrCreateInstance(modalEl, { backdrop: 'static', keyboard: false });
            });
        }

        // Aplica backdrop estático a los modales principales
        setStaticBackdrop('empresaModal');
        setStaticBackdrop('clienteModal');
        setStaticBackdrop('detalleModal');
        setStaticBackdrop('pagosModal');
        setStaticBackdrop('cuentaCorrienteModal');

        function cargarFactura() {
            const select = document.getElementById('listaFacturas');
            const facturaId = select.value;

            if (facturaId === '') {
                nuevaFactura();
                return;
            }

            fetch(`cargar_factura.php?id=${facturaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    document.getElementById('numeroFactura').value = data.factura.numero_factura;
                    document.getElementById('fecha').value = data.factura.fecha;

                    // Populate company details if available
                    if (data.factura.empresa_id) {
                        document.getElementById('selectedEmpresaId').value = data.factura.empresa_id;
                        document.getElementById('displayEmpresaNombre').textContent = data.factura.empresa_nombre;
                        document.getElementById('displayEmpresaDireccion').textContent = data.factura.empresa_direccion;
                        document.getElementById('displayEmpresaCP').textContent = data.factura.empresa_codigo_postal;
                        document.getElementById('displayEmpresaLocalidad').textContent = data.factura.empresa_localidad || 'MISIONES'; // Default if not specified in DB
                        document.getElementById('displayEmpresaTipoContribuyente').textContent = data.factura.empresa_tipo_contribuyente;

                        document.getElementById('empresaCuit').value = data.factura.empresa_cuit;
                        document.getElementById('empresaIB').value = data.factura.empresa_ingresos_brutos;
                        document.getElementById('inicioActividad').value = aFechaDMY(data.factura.empresa_inicio_actividad);

                        // Update barcode and CAI info
                        JsBarcode(".barcode-svg", {
                            value: data.factura.empresa_codigo_barra_cai,
                            format: "code128",
                            width: 1.0,
                            height: 30,
                            textMargin: 0,
                            fontOptions: "bold"
                        }).init();
                        document.getElementById('displayRegistradoraFiscal').textContent = data.factura.empresa_registradora_fiscal;
                        document.getElementById('displayFechaVencimientoCAI').textContent = data.factura.empresa_fecha_vencimiento_cai;

                    } else {
                        // Clear company fields and revert to default if no company is associated
                        document.getElementById('selectedEmpresaId').value = '';
                        document.getElementById('displayEmpresaNombre').textContent = 'ROSA RUBEN';
                        document.getElementById('displayEmpresaDireccion').textContent = 'AV EL LIBERTADOR N°829';
                        document.getElementById('displayEmpresaCP').textContent = '3366';
                        document.getElementById('displayEmpresaLocalidad').textContent = 'MISIONES';
                        document.getElementById('displayEmpresaTipoContribuyente').textContent = 'RESPONSABLE INSCRIPTO';
                        document.getElementById('empresaCuit').value = '20125027378';
                        document.getElementById('empresaIB').value = ' C.M.: 905-302000-1';
                        document.getElementById('inicioActividad').value = '01/04/2021';
                        JsBarcode(".barcode-svg", {
                            value: "20309038682010012501722067317062230420258",
                            format: "code128",
                            width: 1.0,
                            height: 30,
                            textMargin: 0,
                            fontOptions: "bold"
                        }).init();
                         document.getElementById('displayRegistradoraFiscal').textContent = 'CF HAB5503059';
                         document.getElementById('displayFechaVencimientoCAI').textContent = '12/05/25';
                    }

                    // Populate client details if available (from linked client table or from facturas table)
                    if (data.factura.cliente_id) {
                         document.getElementById('selectedClienteId').value = data.factura.cliente_id;
                        document.getElementById('clienteNombre').value = data.factura.cliente_nombre_db;
                        document.getElementById('clienteDomicilio').value = data.factura.cliente_domicilio_db;
                        document.getElementById('localidad').value = data.factura.cliente_localidad_db;
                        document.getElementById('clienteCuit').value = data.factura.cliente_cuit_db;
                        document.getElementById('clienteIva').value = data.factura.cliente_tipo_iva_db;
                        document.getElementById('condicionVenta').value = data.factura.cliente_condicion_venta_default_db;
                    } else {
                        // If no client_id, use the values saved directly in the facturas table (for older invoices or manually entered)
                        document.getElementById('selectedClienteId').value = '';
                        document.getElementById('clienteNombre').value = data.factura.cliente_nombre;
                        document.getElementById('clienteDomicilio').value = data.factura.cliente_domicilio;
                        document.getElementById('localidad').value = data.factura.localidad;
                        document.getElementById('clienteCuit').value = data.factura.cliente_cuit;
                        document.getElementById('clienteIva').value = data.factura.cliente_iva;
                        document.getElementById('condicionVenta').value = data.factura.condicion_venta;
                    }


                    // Clear existing items
                    const tbody = document.getElementById('itemsTableBody');
                    tbody.innerHTML = '';

                    // Populate items
                    data.items.forEach(item => {
                        // Asegura valores válidos para los inputs number
                        const cantidad = (typeof item.cantidad !== "undefined" && item.cantidad !== null && item.cantidad !== "") ? item.cantidad : "";
                        const precio_unitario = (typeof item.precio_unitario !== "undefined" && item.precio_unitario !== null && item.precio_unitario !== "") ? item.precio_unitario : "";
                        const detalle = (typeof item.detalle !== "undefined" && item.detalle !== null) ? item.detalle : "";
                        const total = (cantidad !== "" && precio_unitario !== "") ? (parseFloat(cantidad) * parseFloat(precio_unitario)).toFixed(2) : "0.00";

                        const fila = document.createElement('tr');

                        const tdCantidad = document.createElement('td');
                        const inCantidad = document.createElement('input');
                        inCantidad.type = 'number'; inCantidad.className = 'editable'; inCantidad.name = 'cantidad[]'; inCantidad.step = '1';
                        inCantidad.value = cantidad;
                        inCantidad.addEventListener('change', function(){ calcularTotal(this.closest('tr')); });
                        tdCantidad.appendChild(inCantidad);

                        const tdDetalle = document.createElement('td');
                        const inDetalle = document.createElement('input');
                        inDetalle.type = 'text'; inDetalle.className = 'editable'; inDetalle.name = 'detalle[]'; inDetalle.style.width = '100%';
                        inDetalle.value = detalle;
                        tdDetalle.appendChild(inDetalle);

                        const tdPrecio = document.createElement('td');
                        const inPrecio = document.createElement('input');
                        inPrecio.type = 'number'; inPrecio.className = 'editable'; inPrecio.name = 'precio_unitario[]'; inPrecio.step = '0.01';
                        inPrecio.style.textAlign = 'right'; inPrecio.value = precio_unitario;
                        inPrecio.addEventListener('change', function(){ calcularTotal(this.closest('tr')); });
                        tdPrecio.appendChild(inPrecio);

                        const tdTotal = document.createElement('td');
                        tdTotal.className = 'total-item'; tdTotal.style.textAlign = 'right'; tdTotal.textContent = total;

                        fila.appendChild(tdCantidad);
                        fila.appendChild(tdDetalle);
                        fila.appendChild(tdPrecio);
                        fila.appendChild(tdTotal);

                        tbody.appendChild(fila);
                    });

                    // Add empty rows to fill up to 6 or more if needed
                    const currentRows = tbody.querySelectorAll('tr').length;
                    for (let i = currentRows; i < Math.max(6, currentRows + 3); i++) {
                        agregarFila();
                    }

                    calcularTotales();
                })
                .catch(error => console.error('Error al cargar la factura:', error));
        }

        function eliminarFacturaSeleccionada() {
            const select = document.getElementById('listaFacturas');
            const facturaId = select.value;

            if (facturaId === '') {
                alert('Por favor, seleccione una factura para eliminar.');
                return;
            }

            const facturaText = select.options[select.selectedIndex].text;

            if (!confirm(`¿Estás seguro de que deseas eliminar la factura?\n\n${facturaText}`)) {
                return;
            }

            fetch(`delete_factura.php?id=${facturaId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Factura eliminada exitosamente.');
                        // Recargar la página para actualizar la lista de facturas
                        location.reload();
                    } else {
                        alert('Error al eliminar factura: ' + (data.error || 'Error desconocido'));
                    }
                })
                .catch(error => {
                    console.error('Error al eliminar factura:', error);
                    alert('Error al eliminar factura: ' + error.message);
                });
        }

        document.addEventListener('DOMContentLoaded', (event) => {
            // Initialize barcode on page load
            JsBarcode(".barcode-svg", {
                width: 1.0,
                height: 30,
                textMargin: 0,
                fontOptions: "bold",
                value: "20309038682010012501722067317062230420258"
            }).init();
            calcularTotales();
        });

        // Funciones para gestionar pagos de cuenta corriente
        function abrirPagosModal() {
            const select = document.getElementById('listaFacturas');
            const facturaId = select.value;

            if (facturaId === '') {
                alert('Seleccione una factura primero.');
                return;
            }

            // Limpiar formulario de pago
            document.getElementById('pagoCrudForm').reset();
            document.getElementById('pagoIdCrud').value = '';

            // Cargar pagos existentes para la factura
            fetch(`get_pagos.php?factura_id=${facturaId}`)
                .then(response => response.json())
                .then(data => {
                    const tbody = document.getElementById('pagosTableBody');
                    tbody.innerHTML = '';

                    if (Array.isArray(data) && data.length > 0) {
                        data.forEach(pago => {
                            const tr = document.createElement('tr');
                            const tdMonto = document.createElement('td'); tdMonto.textContent = pago.monto || '';
                            const tdFecha = document.createElement('td'); tdFecha.textContent = pago.fecha_pago || '';
                            const tdObs = document.createElement('td'); tdObs.textContent = pago.observacion || '';
                            const tdAcc = document.createElement('td');

                            const btnEdit = document.createElement('button');
                            btnEdit.className = 'btn btn-info btn-sm'; btnEdit.textContent = 'Editar';
                            btnEdit.addEventListener('click', () => editPago(pago.id));

                            const btnDelete = document.createElement('button');
                            btnDelete.className = 'btn btn-danger btn-sm'; btnDelete.textContent = 'Eliminar';
                            btnDelete.addEventListener('click', () => deletePago(pago.id));

                            tdAcc.appendChild(btnEdit);
                            tdAcc.appendChild(document.createTextNode(' '));
                            tdAcc.appendChild(btnDelete);

                            tr.appendChild(tdMonto);
                            tr.appendChild(tdFecha);
                            tr.appendChild(tdObs);
                            tr.appendChild(tdAcc);
                            tbody.appendChild(tr);
                        });
                    } else {
                        const tr = document.createElement('tr'); const td = document.createElement('td'); td.colSpan = 4; td.textContent = 'No hay pagos registrados para esta factura.'; tr.appendChild(td); tbody.appendChild(tr);
                    }
                })
                .catch(error => console.error('Error al cargar pagos:', error));

            // Mostrar modal de pagos
            const pagosModal = new bootstrap.Modal(document.getElementById('pagosModal'));
            pagosModal.show();
        }

        function showPagoForm() {
            document.getElementById('pagoFormContainer').style.display = 'block';
            document.getElementById('pagosListContainer').style.display = 'none';
        }

        function hidePagoForm() {
            document.getElementById('pagoFormContainer').style.display = 'none';
            document.getElementById('pagosListContainer').style.display = 'block';
            document.getElementById('pagoCrudForm').reset();
            document.getElementById('pagoIdCrud').value = '';
        }

        function savePago() {
            const formData = new FormData(document.getElementById('pagoCrudForm'));
            fetch('save_pago.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Pago guardado exitosamente.');
                    hidePagoForm();
                    abrirPagosModal(); // Refresh pagos list
                } else {
                    alert('Error al guardar pago: ' + data.error);
                }
            })
            .catch(error => console.error('Error al guardar pago:', error));
        }

        function editPago(id) {
            fetch(`get_pago.php?id=${id}`)
                .then(response => response.json())
                .then(pago => {
                    if (pago.error) {
                        alert(pago.error);
                        return;
                    }
                    document.getElementById('pagoIdCrud').value = pago.id;
                    document.getElementById('pagoMontoCrud').value = pago.monto;
                    document.getElementById('pagoFechaCrud').value = pago.fecha_pago;
                    document.getElementById('pagoObsCrud').value = pago.observacion || '';
                    showPagoForm();
                })
                .catch(error => console.error('Error al editar pago:', error));
        }

        function deletePago(id) {
            if (confirm('¿Estás seguro de que quieres eliminar este pago?')) {
                fetch(`delete_pago.php?id=${id}`, { method: 'GET' })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Pago eliminado exitosamente.');
                            abrirPagosModal(); // Refresh pagos list
                        } else {
                            alert('Error al eliminar pago: ' + data.error);
                        }
                    })
                    .catch(error => console.error('Error al eliminar pago:', error));
            }
        }

        function abrirCuentaCorrienteModal() {
    const clienteId = document.getElementById('selectedClienteId').value;
    if (!clienteId) {
        alert('Seleccione un cliente primero.');
        return;
    }
    fetch('get_movimientos_cc.php?cliente_id=' + clienteId)
        .then(response => response.json())
        .then(data => {
            document.getElementById('saldoCC').textContent = data.saldo;

            // Mostrar el saldo de IVA que viene del servidor
            if (data.saldo_iva && parseFloat(data.saldo_iva.replace(',', '.')) > 0) {
                document.getElementById('saldoIVA').textContent = 'Saldo IVA: ' + data.saldo_iva;
            } else {
                document.getElementById('saldoIVA').textContent = '';
            }

            const tbody = document.getElementById('tablaMovimientosCC');
            tbody.innerHTML = '';

            // Ordenar por id descendente; si no hay id, por fecha descendente
            const movimientos = Array.isArray(data.movimientos) ? data.movimientos.slice() : [];
            // Compatibilidad ES5: evitar arrow functions y nullish coalescing
            movimientos.sort(function(a, b) {
                var ida = (typeof a.id === 'number') ? a.id : parseInt((a.id != null ? a.id : '0'), 10);
                var idb = (typeof b.id === 'number') ? b.id : parseInt((b.id != null ? b.id : '0'), 10);
                if (!isNaN(idb) && !isNaN(ida) && idb !== ida) return idb - ida;
                var fa = new Date(a.fecha);
                var fb = new Date(b.fecha);
                return fb - fa;
            });

            movimientos.forEach(mov => {
                const tr = document.createElement('tr');
                const tdFecha = document.createElement('td'); tdFecha.textContent = mov.fecha || '';
                const tdTipo = document.createElement('td'); tdTipo.textContent = mov.tipo || '';
                const tdMonto = document.createElement('td'); tdMonto.textContent = mov.monto || '';
                const tdObs = document.createElement('td'); tdObs.textContent = mov.observacion || '';
                tr.appendChild(tdFecha); tr.appendChild(tdTipo); tr.appendChild(tdMonto); tr.appendChild(tdObs);
                tbody.appendChild(tr);
            });

            new bootstrap.Modal(document.getElementById('cuentaCorrienteModal')).show();
        });
}

        function registrarPagoCC(e) {
            e.preventDefault();
            const clienteId = document.getElementById('selectedClienteId').value;
            const monto = document.getElementById('pagoCCMonto').value;
            const fecha = document.getElementById('pagoCCFecha').value;
            const observacion = document.getElementById('pagoCCObs').value;
            if (!clienteId) {
                alert('Seleccione un cliente primero.');
                return;
            }
            fetch('registrar_pago_cc.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ cliente_id: clienteId, monto, fecha, observacion })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    abrirCuentaCorrienteModal();
                    document.getElementById('formPagoCC').reset();
                } else {
                    alert('Error al registrar pago: ' + data.error);
                }
            });
        }
// Exporta la tabla de movimientos a un archivo Excel (.xlsx)
function exportarMovimientosExcel() {
    var table = document.getElementById('tablaMovimientosCCTabla');
    if (!table) {
        alert('La tabla de movimientos no está disponible.');
        return;
    }

    function doExport() {
        try {
            var wb = XLSX.utils.table_to_book(table, { sheet: 'Movimientos' });
            var elem = document.getElementById('clienteNombre');
            var cliente = (elem && elem.value) ? elem.value : 'cliente';
            if (cliente && cliente.trim) {
                cliente = cliente.trim();
            } else {
                cliente = 'cliente';
            }
            var fecha = new Date().toISOString().slice(0, 10);
            var nombreArchivo = 'CuentaCorriente_' + cliente + '_' + fecha + '.xlsx';
            XLSX.writeFile(wb, nombreArchivo);
        } catch (e) {
            console.error('Error exportando Excel:', e);
            alert('No se pudo exportar a Excel.');
        }
    }

    if (window.XLSX) {
        doExport();
    } else {
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js';
        script.onload = doExport;
        script.onerror = function() {
            alert('No se pudo cargar la librería de Excel.');
        };
        document.body.appendChild(script);
    }
}
// Exporta la tabla de empresas a un archivo Excel (.xlsx)
function exportarEmpresasExcel() {
    var table = document.getElementById('empresasTable');
    if (!table) {
        alert('La tabla de empresas no está disponible.');
        return;
    }

    function doExport() {
        try {
            var wb = XLSX.utils.table_to_book(table, { sheet: 'Empresas' });
            var fecha = new Date().toISOString().slice(0, 10);
            var nombreArchivo = 'Empresas_' + fecha + '.xlsx';
            XLSX.writeFile(wb, nombreArchivo);
        } catch (e) {
            console.error('Error exportando Excel:', e);
            alert('No se pudo exportar a Excel.');
        }
    }

    if (window.XLSX) {
        doExport();
    } else {
        var script = document.createElement('script');
        script.src = 'https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js';
        script.onload = doExport;
        script.onerror = function() {
            alert('No se pudo cargar la librería de Excel.');
        };
        document.body.appendChild(script);
    }
}
    </script>

</body>
</html>
