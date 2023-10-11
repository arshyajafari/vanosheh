<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class CustomerSettlementController extends Controller {
        public function store(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new CustomerSettlementAction())
                    ->setRequest($request)
                    ->setValidationRule('store')
                    ->storeByRequest()
            ]);
        }

        public function get(Request $request): JsonResponse {
            return response()->json(
                (new CustomerSettlementAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            );
        }

        public function getById(string $id): JsonResponse {
            return response()->json(
                (new CustomerSettlementAction())
                    ->getById($id)
            );
        }

        public function updateById(Request $request, string $id): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new CustomerSettlementAction())
                    ->setRequest($request)
                    ->setValidationRule('update')
                    ->updateByIdAndRequest($id)
            ]);
        }

        public function deleteById(string $id): JsonResponse {
            return response()->json([
                'message' => 'deleted',
                'data' => (new CustomerSettlementAction())
                    ->deleteById($id)
            ]);
        }
    }
