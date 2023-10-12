<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use FinancialReportAction;

    class FinancialReportController extends Controller {
        public function get(Request $request): JsonResponse {
            return response()->json(
                (new FinancialReportAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            );
        }

        public function getById(string $id): JsonResponse {
            return response()->json(
                (new FinancialReportAction())
                    ->getById($id)
            );
        }
    }
