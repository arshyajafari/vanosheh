<?php
    namespace App\Actions;

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use App\Models\MessageModel;

    class MessageAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(MessageModel::class)
                ->setResource(MessageResource::class)
                ->setValidationRules([
                    'store' => [
                        'text' => ['required', 'string', 'max:1500'],
                    ],
                    'getQuery' => [
                        'from_date' => ['string'],
                        'to_date' => ['string'],
                    ]
                ])
                ->setCasts([
                    'from_date' => ['jalali_to_gregorian:Y-m-d'],
                    'to_date' => ['jalali_to_gregorian:Y-m-d'],
                ])
                ->setQueryToEloquentClosures([
                    'from_date' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->whereDate('create_at', '>=', $query['from_date']);
                    },
                    'to_date' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->whereDate('create_at', '<=', $query['to_date']);
                    },
                ])
                ->setOrderBy(['create_at' => 'DESC', 'id' => 'DESC']);
            parent::__construct();
        }
    }
