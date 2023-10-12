<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\JsonResponse;
    use App\Actions\CustomerAction;
    use Illuminate\Http\Request;

    class CustomerController extends Controller {
        public function store(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new CustomerAction())
                    ->setRequest($request)
                    ->setValidationRule('store')
                    ->storeByRequest()
            ]);
        }

        public function get(Request $request): JsonResponse {
            return response()->json(
                (new CustomerAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            );
        }

        public function getById(string $id): JsonResponse {
            return response()->json(
                (new CustomerAction())->getById($id)
            );
        }

        public function updateById(string $studentId, Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new CustomerAction())
                    ->setRequest($request)
                    ->setValidationRule('update')
                    ->updateByIdAndRequest($studentId)
            ]);
        }
    }
