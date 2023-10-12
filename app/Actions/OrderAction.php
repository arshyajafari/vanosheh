<?php
    namespace App\Actions;

    use Genocide\Radiocrud\Services\ActionService\ActionService;
    use App\Models\OrderModel;

    class OrderAction extends ActionService {
        public function __construct() {
            $this
                ->setModel(OrderModel::class)
                ->setResource(OrderResource::class)
                ->setValidationRules([
                    'store' => [
                        'customer_id' => ['required', 'integer', 'exists:customers,id'],
                        'member_id' => ['required', 'integer', 'exists:members,id'],
                        'total_invoice' => ['required', 'integer', 'between:0,10000000000'],
                        'invoice_type' => ['nullable', 'string', 'max:50'],
                        'order_status' => ['nullable', 'string', 'max:50'],
                        'description' => ['nullable', 'string', 'max:1500'],
                    ],
                    'update' => [
                        'customer_id' => ['integer', 'exists:customers,id'],
                        'member_id' => ['integer', 'exists:members,id'],
                        'total_invoice' => ['integer', 'between:0,10000000000'],
                        'invoice_type' => ['string', 'max:50'],
                        'order_status' => ['string', 'max:50'],
                        'description' => ['string', 'max:1500'],
                    ],
                    'getQuery' => [
                        'search' => ['string', 'max:300'],
                        'from_date' => ['string'],
                        'to_date' => ['string'],
                        'from_amount' => ['string'],
                        'to_amount' => ['string'],
                        'invoice_type' => ['string', 'max:50'],
                        'order_status' => ['string', 'max:50'],
                        'member_id' => ['integer'],
                    ]
                ])
                ->setCasts([
                    'from_date' => ['jalali_to_gregorian:Y-m-d'],
                    'to_date' => ['jalali_to_gregorian:Y-m-d'],
                ])
                ->setQueryToEloquentClosures([
                    'search' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('full_name', 'LIKE', "%{$query['search']}%");
                    },
                    'from_date' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->whereDate('create_at', '>=', $query['from_date']);
                    },
                    'to_date' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->whereDate('create_at', '<=', $query['to_date']);
                    },
                    'from_amount' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->whereDate('total_invoice', '>=', $query['from_amount']);
                    },
                    'to_amount' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->whereDate('total_invoice', '<=', $query['to_amount']);
                    },
                    'invoice_type' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('invoice_type', $query['invoice_type']);
                    },
                    'order_status' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('order_status', $query['order_status']);
                    },
                    'member_id' => function (&$eloquent, $query) {
                        $eloquent = $eloquent->where('member_id', $query['member_id']);
                    },
                ])
                ->setOrderBy(['create_at' => 'DESC', 'id' => 'DESC']);
            parent::__construct();
        }
    }
