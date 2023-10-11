<?php
    namespace App\Http\Controllers;

    use App\Actions\CustomerAction;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class CustomerController extends Controller {
        public function store(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new CustomerAction())
                    ->setRequest($request)
                    ->setValidationRule('store')
                    ->setDefaultRegisterStatus('added')
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
                    ->setValidationRule('updateByMember')
                    ->updateByIdAndRequest($studentId)
            ]);
        }

        public function deleteById(string $id): JsonResponse {
            return response()->json([
                'message' => 'deleted',
                'data' => (new CustomerAction())
                    ->deleteById($id)
            ]);
        }
    }
