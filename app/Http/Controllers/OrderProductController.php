<?php
    namespace App\Http\Controllers;

    use App\Actions\OrderProductAction;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class OrderProductController extends Controller {
        public function store(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new OrderProductAction())
                    ->setRequest($request)
                    ->setValidationRule('store')
                    ->storeByRequest()
            ]);
        }

        public function get(Request $request): JsonResponse {
            return response()->json(
                (new OrderProductAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            );
        }

        public function getById(string $id): JsonResponse {
            return response()->json(
                (new OrderProductAction())->getById($id)
            );
        }

        public function updateById(string $id, Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new OrderProductAction())
                    ->setRequest($request)
                    ->setValidationRule('update')
                    ->updateByIdAndRequest($id)
            ]);
        }

        public function deleteById(string $id): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new OrderProductAction())->deleteById($id)
            ]);
        }
    }
