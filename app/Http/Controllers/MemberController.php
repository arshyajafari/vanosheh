<?php
    namespace App\Http\Controllers;

    use Illuminate\Http\JsonResponse;
    use App\Actions\MemberAction;
    use Illuminate\Http\Request;

    class MemberController extends Controller {
        public function storeByAdmin(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new MemberAction())
                    ->setRequest($request)
                    ->setValidationRule('storeByAdmin')
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

        public function updateInfo(Request $request): JsonResponse {
            (new MemberAction())
                ->setRequest($request)
                ->queryEloquentById($request->user()->id)
                ->updateInfoByRequest();
            return response()->json([
                'message' => 'updated successfully'
            ]);
        }
    }
