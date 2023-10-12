<?php
    namespace App\Http\Controllers;

    use App\Models\MessageMemberPivotModel;
    use Illuminate\Http\JsonResponse;
    use App\Actions\MessageAction;
    use Illuminate\Http\Request;

    class MessageController extends Controller {
        public function store(Request $request): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new MessageAction())
                    ->setRequest($request)
                    ->setValidationRule('store')
                    ->storeByRequest()
            ]);
        }

        public function get(Request $request): JsonResponse {
            return response()->json(
                (new MessageAction())
                    ->setRequest($request)
                    ->setValidationRule('getQuery')
                    ->makeEloquentViaRequest()
                    ->getByRequestAndEloquent()
            );
        }

        public function messageSeenByMember(Request $request, string $id): JsonResponse {
            $member = $request->user();
            MessageMemberPivotModel::query()
                ->where('member_id', $member->id)
                ->where('message_id', $id)
                ->delete();
            MessageMemberPivotModel::query()->create([
                'member_id' => $member->id,
                'message_id' => $id
            ]);
            return response()->json(
                (new MessageAction())->getById($id)
            );
        }

        public function deleteById(string $id): JsonResponse {
            return response()->json([
                'message' => 'ok',
                'data' => (new MessageAction())->deleteById($id)
            ]);
        }
    }
