<?php
class EstadisticasController extends Controller
{

    private function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    public function index()
    {
        $this->view("estadisticas");
    }

    public function getDatosGenerales()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $estudiante = $this->model("Estudiantes");
                $data = $estudiante->getDatosGenerales();
                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }

            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

    }

    public function getPromedioPorPrograma()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $estudiante = $this->model("Estudiantes");
                $data = $estudiante->getPromedioPorPrograma();
                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }

            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function getRankingEstudiantes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $estudiante = $this->model("Estudiantes");
                $data = $estudiante->getTop5Estudiantes();
                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }

            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }

    }


    public function getRankingAllEstudiantes()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            try {
                $estudiante = $this->model("Estudiantes");
                $data = $estudiante->getRankingAllEstudiantes();
                if ($data) {
                    return $this->jsonResponse([
                        'status' => 'success',
                        'data' => $data
                    ], 200);
                } else {
                    return $this->jsonResponse([
                        'status' => 'error',
                        'message' => 'Estudiante no encontrado.'
                    ], 404);
                }

            } catch (Exception $e) {
                return $this->jsonResponse([
                    'status' => 'error',
                    'message' => $e->getMessage()
                ], 500);
            }
        }
    }

    public function Exportpdf()
    {
        require __DIR__ . '/../../vendor/autoload.php';
        $estudiante = $this->model("Estudiantes");
        $data = $estudiante->getEstudiantesForExport();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Listado Estudiante', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);

        foreach ($data[0] as $header) {
            $pdf->Cell(60, 10, $header, 1);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);

        for ($i = 1; $i < count($data); $i++) {
            foreach ($data[$i] as $cell) {
                $pdf->Cell(40, 10, $cell, 1);
            }
            $pdf->Ln();
        }

        $pdf->Output('D', 'estudiantes.pdf');
    }


    public function ExportExcel()
    {
        $estudiante = $this->model("Estudiantes");
        $data = $estudiante->getEstudiantesForExport();

        if (empty($data)) {
            exit("No hay datos para exportar.");
        }


        $filename = "estudiantes.csv";


        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);


        $output = fopen('php://output', 'w');


        $headers = ['Nombre', 'Apellido', 'Usuario', 'Email', 'Programa', 'Calificación'];
        fputcsv($output, $headers);

        foreach ($data as $row) {
            $row = array_values($row);
            fputcsv($output, $row);
        }

        fclose($output);
        exit;
    }

    public function exportRanking()
    {
        require __DIR__ . '/../../vendor/autoload.php';
        $estudiante = $this->model("Estudiantes");
        $data = $estudiante->getRankingAllEstudiantes();

        $pdf = new FPDF('L', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);
        $pdf->Cell(0, 10, 'Ranking de Estudiantes', 0, 1, 'C');
        $pdf->Ln(5);

        $pdf->SetFont('Arial', 'B', 12);

        foreach ($data[0] as $header) {
            $pdf->Cell(60, 10, $header, 1);
        }
        $pdf->Ln();

        $pdf->SetFont('Arial', '', 12);

        for ($i = 1; $i < count($data); $i++) {
            foreach ($data[$i] as $cell) {
                $pdf->Cell(40, 10, $cell, 1);
            }
            $pdf->Ln();
        }

        $pdf->Output('D', 'estudiantes.pdf');
    }

}