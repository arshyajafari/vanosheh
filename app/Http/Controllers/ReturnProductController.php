<?php
    namespace App\Http\Controllers;

    use App\Actions\ReturnProductAction;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class ReturnProductController extends Controller {
        public function store(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new ReturnProductAction())
                    ->setRequest($request)
                    ->setValidationRule('store')
                    ->storeByRequest()
            ]);
        }

        public function get(Request $request): JsonResponse {
            return response()->json(
                (new ReturnProductAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            );
        }

        public function getById(string $id): JsonResponse {
            return response()->json(
                (new ReturnProductAction())->getById($id)
            );
        }

        public function updateById(string $id, Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new ReturnProductAction())
                    ->setRequest($request)
                    ->setValidationRule('update')
                    ->updateByIdAndRequest($id)
            ]);
        }

        public function deleteById(string $id): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new ReturnProductAction())->deleteById($id)
            ]);
        }
    }
