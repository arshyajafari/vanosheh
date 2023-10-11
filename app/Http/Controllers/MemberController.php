<?php
    namespace App\Http\Controllers;

    use App\Actions\MemberAction;
    use App\Http\Resources\MemberResource;
    use Illuminate\Http\JsonResponse;
    use Illuminate\Http\Request;

    class MemberController extends Controller {
        public function storeByAdmin(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new MemberAction())
                    ->setRequest($request)
                    ->setValidationRule('storeByAdmin')
                    ->setDefaultRegisterStatus('added')
                    ->storeByRequest()
            ]);
        }

        public function login(Request $request): JsonResponse {
            return response()->json([
                'message' => 'logged in successfully',
                'token' => (new MemberAction())
                    ->setRequest($request)
                    ->setValidationRule('login')
                    ->loginByRequest()->plainTextToken
            ]);
        }

        public function get(Request $request): JsonResponse {
            return response()->json([
                'message' => 'Members: ',
                'data' => (new MemberAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            ]);
        }

        public function getById(string $id): JsonResponse {
            return response()->json([
                (new MemberAction())->getById($id)
            ]);
        }

        public function getInfo(Request $request): JsonResponse {
            return response()->json(new MemberResource($request->user()));
        }

        public function updateById(string $id, Request $request): JsonResponse {
            return response()->json([
                'message' => 'Updated Successfully',
                'data' => (new MemberAction())
                    ->setRequest($request)
                    ->setValidationRule('updateByAdmin')
                    ->updateByIdAndRequest($id)
            ]);
        }

        public function deleteById(string $id): JsonResponse {
            (new MemberAction())->deleteById($id);
            return response()->json([
                'message' => 'deleted'
            ]);
        }

        public function changePassword(Request $request): JsonResponse {
            (new MemeberAction())
                ->setRequest($request)
                ->setValidationRule('changePassword')
                ->changePasswordByRequest();
            return response()->json([
                'message' => 'password changed successfully'
            ]);
        }
    }
