<?php
    namespace App\Http\Controllers;

    use App\Http\Resources\AdminResource;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;
    use App\Actions\AdminAction;

    class AdminController extends Controller {
        public function register(Request $request): JsonResponse {
            return response()->json([
                'message' => 'Registered Successfully',
                'data' => (new AdminAction())
                    ->setRequest($request)
                    ->setValidationRule('store')
                    ->storeByRequest()
            ]);
        }

        public function login(Request $request): JsonResponse {
            return response()->json([
                'message' => 'logged in successfully',
                'token' => (new AdminAction())
                    ->setRequest($request)
                    ->setValidationRule('login')
                    ->loginByRequest()->plainTextToken
            ]);
        }

        public function get(Request $request): JsonResponse {
            return response()->json([
                'message' => 'Admins: ',
                'data' => (new AdminAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            ]);
        }

        public function getById(string $id): JsonResponse {
            return response()->json([
                (new AdminAction())->getById($id)
            ]);
        }

        public function getInfo(Request $request): JsonResponse {
            return response()->json(new AdminResource($request->user()));
        }

        public function updateById(string $id, Request $request): JsonResponse {
            return response()->json([
                'message' => 'Updated Successfully',
                'data' => (new AdminAction())
                    ->setRequest($request)
                    ->setValidationRule('update')
                    ->updateByIdAndRequest($id)
            ]);
        }

        public function deleteById(string $id): JsonResponse {
            (new AdminAction())->deleteById($id);
            return response()->json([
                'message' => 'deleted'
            ]);
        }
    }
